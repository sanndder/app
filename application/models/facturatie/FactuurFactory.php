<?php

namespace models\facturatie;

use models\Connector;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurMarge;
use models\pdf\PdfFactuurVerkoopUren;
use models\uitzenders\Uitzender;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\verloning\Invoer;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;
use models\werknemers\PlaatsingGroup;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactuurFactory extends Connector
{
	private $_sessie_id = NULL;
	
	private $_uitzender_bedrijfsgegevens = NULL;

	private $_inlener_bedrijfsgegevens = NULL;
	private $_inlener_factuurgegevens = NULL;
	private $_inlener_werknemers = NULL;
	
	private $_setting_split_project = 0;
	private $_setting_split_werknemer = 0;
	
	private $_setting_termijn = 30;
	
	private $_setting_btw_verleggen = 0;
	
	private $_setting_g_rekening = 0;
	private $_setting_g_rekening_percentage = 0;
	
	//array met alle ruwe data
	private $_invoer_array = NULL;
	
	//array met alles opgeteld en gegroepeerd
	private $_group_array = NULL;
	
	//invoer object laden, wordt meerdere malen gebruikt
	private $invoer = NULL;
	
	protected $_inlener_id = NULL;
	protected $_uitzender_id = NULL;
	
	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//start van de factuur sessie
		$this->_sessieStart();
		
		//invoer class laden
		$this->invoer = new Invoer();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Functie die alles aanstuurt
	 *
	 */
	public function run(): bool
	{
		//invoer ophalen en wegzetten in database
		
		$this->invoer->setTijdvak( $_POST );
		$this->invoer->setInlener( $_POST['inlener_id'] );
		$this->invoer->setUitzender( $this->_uitzender_id );
		
		//invoer ophalen en wegzetten in database
		if( !$this->_werknemersForInlener() )
		{
			$this->_sessieFinish( 'geen werknemers zijn gevonden' );
			return false;
		}
		
		//ruwe invoer ophalen en naar array
		if( !$this->_invoerToArray() )
		{
			$this->_sessieFinish( 'fout in bij laden invoer in array' );
			return false;
		}
		
		//invoer groeperen en optellen, indien nogdig per project en/of werknemer
		if( !$this->_groupInvoer() )
		{
			$this->_sessieFinish( 'fout in bij groeperen van invoer' );
			return false;
		}
		
		//invoer controelren
		if( !$this->_checkInvoer() )
		{
			$this->_sessieFinish( 'fout bij controleren van invoer' );
			return false;
		}
		
		// alle data is gereed, nu facturen in database aanmaken
		if( !$this->_setConceptVerkoopFacturen() )
		{
			$this->_sessieFinish( 'fout bij aanmaken concept facturen' );
			return false;
		}
		
		//TODO hier krediet check
		
		//nu pdf's maken voor alle facturen
		if( !$this->_createPdfs() )
		{
			$this->_sessieFinish( "fout bij aanmaken pdf's" );
			return false;
		}
		
		//alle pdf's zijn gemaakt, van concept af
		if( !$this->_conceptToFinal() )
		{
			$this->_sessieFinish( "fout definitief maken facturen" );
			return false;
		}
		
		//alles is klaar, beeindig de sessie
		$this->_sessieFinish();
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle pdf's van de sessie van concept af
	 *
	 */
	private function _conceptToFinal(): bool
	{
		$this->db_user->query( "UPDATE facturen SET concept = 0 WHERE sessie_id = $this->_sessie_id" );
		if( $this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * PDF's maken: inkoop, kosten en dan marge
	 *
	 */
	private function _createPdfs(): bool
	{
		//start van de facturen
		$this->_sessieLog( 'action', "pdf's maken van concepten" );
		
		//alle concept facturen voor sessie ophalen
		$concepten = $this->_getSessieConcepten();
		
		//stoppen als er geen concepten zijn
		if( $concepten === NULL )
			return false;
		
		//dan alle concept facturen pdf's maken
		foreach( $concepten as $factuur_id => $concept )
		{
			//details en regels hier ophalen voor hergebruik
			$factuur = $this->getFactuurDetails( $factuur_id );
			$factuur_regels = $this->getFactuurRegels( $factuur_id );
			
			//eerst de verkoopfactuur maken
			if( !$this->_pdfVerkoop( $factuur, $factuur_regels ) )
			{
				$this->_sessieLog( 'error', "verkoop pdf kon niet worden aangemaakt", $factuur_id, 1 );
				$this->_errorDeleteAll();
				return false;
			}
			
			//wanneer factuur gelukt is kostenoverzicht maken
			if( !$this->_pdfKosten( $factuur, $factuur_regels ) )
			{
				$this->_sessieLog( 'error', "kosten pdf kon niet worden aangemaakt", $factuur_id, 1 );
				$this->_errorDeleteAll();
				return false;
			}
			
			//wanneer beiden gelukt zijn dan de marge factuur maken
			$marge_id = $this->_setConceptMargeFactuur( $factuur );
			if( !$this->_pdfMarge( $marge_id ))
			{
				$this->_sessieLog( 'error', "marge pdf kon niet worden aangemaakt", $factuur_id, 1 );
				$this->_errorDeleteAll();
				return false;
			}
			
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur nummer bepalen
	 * nummer ook gelijk toevoegen aan factuur
	 */
	private function _getFactuurNr( $factuur_id = NULL ): ?int
	{
		//log action
		$this->_sessieLog( 'action', "factuur nr ophalen", $factuur_id );
		
		$query = $this->db_user->query( "SELECT MAX(factuur_nr) AS factuur_nr FROM facturen" );
		
		if( $query->num_rows() == 0 )
			$data['factuur_nr'] = 0;
		else
			$data = $query->row_array();
		
		$factuur_nr = $data['factuur_nr'] + 1;
		
		$this->db_user->where( 'factuur_id', $factuur_id );
		$this->db_user->update( 'facturen', array( 'factuur_nr' => $factuur_nr ) );
		
		//log action
		$this->_sessieLog( 'action', "factuur bekend: " . $factuur_nr, $factuur_id );
		
		return $factuur_nr;
	}




	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf ksotenoverzicht maken
	 *
	 */
	private function _pdfMarge( $marge_id ): bool
	{
		//details en regels hier ophalen voor hergebruik
		$factuur = $this->getFactuurDetails( $marge_id );
		$regels = $this->getFactuurRegels( $marge_id );
		
		//log action
		$this->_sessieLog( 'action', "start margefactuur pdf", $factuur['parent_id']);
		
		$pdf = new PdfFactuurMarge();
		
		$pdf->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ) );
		$pdf->setType( 'marge' );
		
		$pdf->setRelatieGegevens( $this->_uitzender_bedrijfsgegevens, NULL );
		$pdf->setFactuurdatum( $factuur['factuur_datum'] );
		
		//factuur nur ophalen
		$factuur_nr = $this->_getFactuurNr( $marge_id );
		
		$pdf->setFactuurNr( $factuur_nr );
		
		$pdf->setFooter();
		$pdf->setHeader();
		
		$pdf->setBody( $factuur, $regels );
		
		$update['file_dir'] = 'facturen/' . $factuur['jaar'];
		$update['file_name'] = $factuur['parent_id'] . '_marge_' . $factuur_nr . '_' . generateRandomString( 4 ) . '.pdf';
		
		$pdf->setFileDir( $update['file_dir'] );
		$pdf->setFileName( $update['file_name'] );
		
		$this->_sessieLog( 'action', "generate marge pdf", $factuur['parent_id']);
		//$pdf->preview();

		if( !$pdf->generate() )
			return false;
		
		//update met juiste gegevens
		$this->db_user->where( 'factuur_id', $marge_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() < 1 )
		{
			$this->_sessieLog( 'error', "update margefactuur mislukt", $factuur['parent_id']);
			return false;
		}
		
		
		//log action
		$this->_sessieLog( 'action', "einde margefactuur pdf", $factuur['parent_id']);
		
		return true;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf ksotenoverzicht maken
	 *
	 */
	private function _pdfKosten( $factuur, $regels ): bool
	{
		//log action
		$this->_sessieLog( 'action', "start kostenoverzicht pdf", $factuur['factuur_id']);
		
		$pdf = new PdfFactuurVerkoopUren();
		
		$pdf->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ) );
		$pdf->setType( 'kosten' );
		
		$pdf->setFooter();
		$pdf->setHeader();
		
		$pdf->setBody( $factuur, $regels );
		
		$insert['file_dir'] = 'facturen/' . $factuur['jaar'];
		$insert['file_name'] = $factuur['factuur_id'] . '_kostenoverzicht_' . generateRandomString( 4 ) . '.pdf';
		
		$pdf->setFileDir( $insert['file_dir'] );
		$pdf->setFileName( $insert['file_name'] );
		
		$this->_sessieLog( 'action', "generate kosten pdf", $factuur['factuur_id']);
		if( !$pdf->generate() )
			return false;
		
		//insert met juiste gegevens
		$insert['sessie_id'] = $this->_sessie_id;
		$insert['factuur_id'] = $factuur['factuur_id'];
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'facturen_kostenoverzicht', $insert );
		if( $this->db_user->insert_id() == 0 )
		{
			$this->_sessieLog( 'error', "insert konstenoverzicht mislukt", $factuur['factuur_id']);
			return false;
		}
		
		
		//log action
		$this->_sessieLog( 'action', "einde kostenoverzicht pdf", $factuur['factuur_id']);
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf van verkoop factuur maken
	 *
	 */
	private function _pdfVerkoop( $factuur, $regels ): bool
	{
		//log action
		$this->_sessieLog( 'action', "start verkoopfactuur pdf", $factuur['factuur_id']);
		
		$pdf = new PdfFactuurVerkoopUren();
		$pdf->setType( 'verkoop' );
		
		$pdf->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ) );
		
		$pdf->setRelatieGegevens( $this->_inlener_bedrijfsgegevens, $this->_inlener_factuurgegevens );
		$pdf->setFactuurdatum( $factuur['factuur_datum']);
		$pdf->setVervaldatum( $factuur['verval_datum'] );
	
		//cessietekst verpanding
		$pdf->setIbanFactoring( $factuur['iban_factoring'] );
		$pdf->setCessieTekst( $factuur['cessie_tekst'] );
		
		$pdf->setGRekening( $factuur['bedrag_grekening'], $factuur['percentage_grekening'] );
		
		//factuur nur ophalen
		$factuur_nr = $this->_getFactuurNr( $factuur['factuur_id'] );
		
		$pdf->setFactuurNr( $factuur_nr );
		$pdf->setFooter();
		$pdf->setHeader();
		
		$pdf->setBody( $factuur, $regels );
		
		$update['file_dir'] = 'facturen/' . $factuur['jaar'];
		$update['file_name'] = $factuur['factuur_id'] . '_verkoop_uren_' . $factuur_nr . '_' . generateRandomString( 4 ) . '.pdf';
	
		$pdf->setFileDir( $update['file_dir'] );
		$pdf->setFileName( $update['file_name'] );
		
		//$pdf->preview();
		
		//pdf maken
		$this->_sessieLog( 'action', "generate verkoop pdf", $factuur['factuur_id']);
		if( !$pdf->generate() )
			return false;
		
		//update met juiste gegevens
		$this->db_user->where( 'factuur_id', $factuur['factuur_id'] );
		$this->db_user->update( 'facturen', $update );
		
		//log action
		$this->_sessieLog( 'action', "einde verkoopfactuur pdf", $factuur['factuur_id']);
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * concept facturen ophalen
	 *
	 */
	private function _getSessieConcepten(): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM facturen WHERE sessie_id = $this->_sessie_id AND concept = 1 AND deleted = 0" );
		
		//stoppen als er geen concepten zijn
		if( $query->num_rows() == 0 )
		{
			$this->_sessieLog( 'error', 'geen concept facturen gevonden', NULL, 1 );
			return NULL;
		}
		
		foreach( $query->result_array() as $row )
			$concepten[$row['factuur_id']] = $row;
		
		return $concepten;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * invoer controleren
	 *
	 */
	private function _checkInvoer(): bool
	{
		//controle doorbelasten
		if( !$this->_checkDoorbelasten() )
		{
			$this->_sessieFinish( 'invoer onjuist doorbelast' );
			return false;
		}
		
		//controle project
		if( !$this->_checkProjecten() )
		{
			$this->_sessieFinish( 'invoer zonder project' );
			return false;
		}
		
		//controle verkooptarief en bruto loon
		if( !$this->_checkBedragen() )
		{
			$this->_sessieFinish( 'onjuiste bedragen' );
			return false;
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * invoer groeperen en optellen voor factuur regels
	 * uren per type en project en locatie tekst
	 * kilometers bij elkaar
	 * vergoedingen per stuk
	 * hoogste niveau is project ID. Bij geen splitsing is de key NULL
	 */
	private function _groupInvoer(): bool
	{
		//zijn er uren om op te tellen
		if( count( $this->_invoer_array['uren'] ) > 0 )
		{
			//groeperen en optellen
			$this->_groupUrenInvoer();
		}
		
		//zijn er km om op te tellen
		if( count( $this->_invoer_array['km'] ) > 0 )
		{
			//groeperen en optellen
			$this->_groupKmInvoer();
		}
		
		//zijn er vergoedingen om op te tellen
		if( count( $this->_invoer_array['vergoedingen'] ) > 0 )
		{
			//groeperen en optellen
			$this->_groupVergoedingenInvoer();
		}
		
		return true;
	}




	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * concept marge aanmaken
	 *
	 */
	private function _setConceptMargeFactuur( $factuur ) :? int
	{
		//start van de facturen
		$this->_sessieLog( 'action', "concept marge aanmaken" );
		
		//marge factuur in databases aanmaken
		if( NULL === $marge_id = $this->_insertConceptMargeFactuur( $factuur ))
			return NULL;
		
		//regels toevoegen
		$this->_insertConceptMargeFactuurRegels( $marge_id, $factuur );
		
		return $marge_id;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * concepten aanmaken in de database
	 *
	 */
	private function _setConceptVerkoopFacturen(): bool
	{
		//start van de facturen
		$this->_sessieLog( 'action', "start concept facturen" );
		
		//we kunnen gewoon door het hoogste niveau loopen, per project is geregeld
		foreach( $this->_group_array as $project_id => $project_array )
		{
			//factuur aanmaken
			if( NULL === $factuur_id = $this->_insertConceptVerkoopFactuur( $project_id ) )
				return false;
			
			//factuur regels toevoegen
			foreach( $project_array as $werknemer_id => $werknemer_array )
				$this->_insertConceptVerkoopFactuurRegels( $factuur_id, $werknemer_id, $werknemer_array );
			
			//totaal telling factuur en BTW uitrekenen
			if( !$this->_calcVerkoopFactuurTotalen( $factuur_id ) )
				return false;
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur totaal tellingen en BTW instellen
	 *
	 */
	private function getFactuurDetails( $factuur_id ): ?array
	{
		$query = $this->db_user->query( "SELECT facturen.*, fct.cessie_tekst, fct.iban_factoring FROM facturen LEFT JOIN facturen_cessie_tekst fct ON facturen.factuur_id = fct.factuur_id WHERE facturen.factuur_id = $factuur_id LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur totaal tellingen en BTW instellen
	 *
	 */
	private function getFactuurRegels( $factuur_id ): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM facturen_regels WHERE factuur_id = $factuur_id" );
		return DBhelper::toArray( $query, 'regel_id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur totaal tellingen en BTW instellen
	 *
	 */
	private function _calcVerkoopFactuurTotalen( $factuur_id ): bool
	{
		//start van de facturen
		$this->_sessieLog( 'action', "totaalbedragen uitrekenen", $factuur_id );
		
		//regels ophalen
		$query = $this->db_user->query( "SELECT regel_id, row_start, row_end, subtotaal_verkoop, subtotaal_kosten FROM facturen_regels WHERE factuur_id = $factuur_id AND row_start IS NULL" );
		
		//wanneer geen regels gevonden zijn error flaggen, moet nagekeken worden
		if( $query->num_rows() == 0 )
		{
			$this->_sessieLog( 'error', "geen regels gevonden voor factuur", $factuur_id, 1 );
			return false;
		}
		
		//dubbel tellen, moet aan elkaar gelijk zijn
		$regel_totaal = 0;
		$sub_totaal = 0;
		
		$kosten_totaal = 0;
		$sub_kosten = 0;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['row_end'] !== NULL )
			{
				$sub_totaal += $row['subtotaal_verkoop'];
				$sub_kosten += $row['subtotaal_kosten'];
			}
			else
			{
				$regel_totaal += $row['subtotaal_verkoop'];
				$kosten_totaal += $row['subtotaal_kosten'];
			}
		}
		
		//controleren of bedragen overeenkomen
		if( abs(($regel_totaal-$sub_totaal)/$sub_totaal) > PHP_FLOAT_EPSILON )
		{
			$this->_sessieLog( 'error', "som regeltotaal en som subtotaal zijn niet aan elkaar gelijk", $factuur_id, 1 );
			return false;
		}
		
		if( abs(($sub_kosten-$kosten_totaal)/$kosten_totaal) > PHP_FLOAT_EPSILON )
		{
			$this->_sessieLog( 'error', "som regelkosten en som kostentotaal zijn niet aan elkaar gelijk", $factuur_id, 1 );
			return false;
		}
		
		//default incl gelijk aan excl
		$update['bedrag_excl'] = $sub_totaal;
		$update['bedrag_incl'] = $sub_totaal;
		
		$update['kosten_excl'] = $sub_kosten;
		$update['kosten_incl'] = $sub_kosten;
		
		//BTW verlegd of niet
		if( $this->_setting_btw_verleggen == 0 )
		{
			$update['bedrag_btw'] = round( ( $update['bedrag_excl'] * 0.21 ), 2 );
			$update['bedrag_incl'] = $update['bedrag_excl'] + $update['bedrag_btw'];
			
			$update['kosten_btw'] = round( ( $update['kosten_excl'] * 0.21 ), 2 );
			$update['kosten_incl'] = $update['kosten_excl'] + $update['kosten_btw'];
		}
		
		//moet er een deel naar de grekening
		if( $this->_setting_g_rekening == 1 )
		{
			$update['percentage_grekening'] = $this->_setting_g_rekening_percentage;
			$update['bedrag_grekening'] = round( ( $update['bedrag_incl'] * ( $this->_setting_g_rekening_percentage / 100 ) ), 2 );
		}
		
		$update['bedrag_openstaand'] = $update['bedrag_incl'];
		
		$this->db_user->where( 'factuur_id', $factuur_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() < 1 )
		{
			$this->_sessieLog( 'error', "factuur kon niet worden geupdate met juiste bedragen", $factuur_id, 1 );
			return false;
		}
		
		return true;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * marge regel aanmaken
	 *
	 */
	private function _insertConceptMargeFactuurRegels( $marge_id, $factuur ): bool
	{
		//log regels
		$this->_sessieLog( 'action', "regels voor margefactuur", $factuur['factuur_id'] );
		
		$insert['factuur_id'] = $marge_id;
		$insert['omschrijving'] = 'Marge uit te betalen door ' . $this->werkgever->bedrijfsnaam() . ': €' . $factuur['bedrag_excl'] . ' - €' . $factuur['kosten_excl'];
		$insert['subtotaal_verkoop'] = $factuur['bedrag_excl'] - $factuur['kosten_excl'];
		
		$this->db_user->insert( 'facturen_regels', $insert );
		
		if( $this->db_user->insert_id() > 0 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur regels aanmaken
	 * TODO: insert naar apparte functie verplaatsen
	 */
	private function _insertConceptVerkoopFactuurRegels( $factuur_id, $werknemer_id, $werknemer_array ): bool
	{
		//log regels
		$this->_sessieLog( 'action', "regels voor factuur ($werknemer_id)", $factuur_id );
		
		//reset telling
		$werknemer_bedrag_verkoop = 0;
		$werknemer_bedrag_kosten = 0;
		
		//algemene inserts
		$insert['factuur_id'] = $factuur_id;
		$insert['werknemer_id'] = $werknemer_id;
		
		//eerste regel met alleen de naam
		$insert['row_start'] = 1;
		$insert['omschrijving'] = $this->_inlener_werknemers[$werknemer_id]['naam'];
		
		//insert 1e regel
		$this->db_user->insert( 'facturen_regels', $insert );
		
		//beginnen met de uren
		if( isset( $werknemer_array['urengroep'] ) && is_array( $werknemer_array['urengroep'] ) && count( $werknemer_array['urengroep'] ) > 0 )
		{
			foreach( $werknemer_array['urengroep'] as $doorbelasten => $doorbelastengroep )
			{
				foreach( $doorbelastengroep['urentypes'] as $label => $urengroep )
				{
					$insert['row_start'] = NULL;
					$insert['omschrijving'] = $label;
					$insert['uren_aantal'] = d2h( $urengroep['totaal_uren'] );
					$insert['uren_decimaal'] = $urengroep['totaal_uren'];
					$insert['verkooptarief'] = $urengroep['verkooptarief'];
					$insert['factor'] = $urengroep['factor'];
					$insert['bruto_uurloon'] = $urengroep['bruto_loon'];
					$insert['percentage'] = $urengroep['percentage'];
					$insert['uitkeren_werknemer'] = 1;
					$insert['invoer_ids'] = json_encode( $urengroep['invoer_ids'] );
					
					if( $urengroep['doorbelasten_uitzender'] == 1 )
						$insert['doorbelasten_aan'] = 'uitzender';
					else
						$insert['doorbelasten_aan'] = 'inlener';
					
					//regeltotaal uitrekenen
					$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['bruto_uurloon'] * $insert['factor'] * ( $insert['percentage'] / 100 ) ), 2 );
					$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
					
					//wanneer doorbelasten naar uitzender, dan NIET op de verkoop factuur
					if( $urengroep['doorbelasten_uitzender'] != 1 )
					{
						$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * 1 * 1 ), 2 );
						$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
					} else
						$insert['subtotaal_verkoop'] = 0;
					
					$this->db_user->insert( 'facturen_regels', $insert );
					
					//invoer juiste factuur ID meegeven
					$this->_updateInvoerMetFactuurIDs( 'invoer_uren', $factuur_id, $urengroep['invoer_ids'] );
				}
			}
		}
		
		//dan de kilometers
		if( isset( $werknemer_array['kmgroep'] ) && is_array( $werknemer_array['kmgroep'] ) && count( $werknemer_array['kmgroep'] ) > 0 )
		{
			foreach( $werknemer_array['kmgroep'] as $doorbelasten => $kmgroep )
			{
				$insert['row_start'] = NULL;
				$insert['omschrijving'] = 'kilometergeld';
				$insert['uren_aantal'] = NULL;
				$insert['uren_decimaal'] = $kmgroep['totaal_km'];
				$insert['verkooptarief'] = 0.19;
				$insert['factor'] = 1;
				$insert['bruto_uurloon'] = 0.19;
				$insert['percentage'] = 100;
				$insert['uitkeren_werknemer'] = 1;
				$insert['invoer_ids'] = json_encode( $kmgroep['invoer_ids'] );
				$insert['doorbelasten_aan'] = $kmgroep['doorbelasten'];
				
				//regeltotaal uitrekenen
				$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['bruto_uurloon'] * $insert['factor'] * ( $insert['percentage'] / 100 ) ), 2 );
				$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
				
				//wanneer doorbelasten naar uitzender, dan NIET op de verkoop factuur
				if( $insert['doorbelasten_aan'] == 'inlener' )
				{
					$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * 1 * 1 ), 2 );
					$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
				} else
					$insert['subtotaal_verkoop'] = 0;
				
				$this->db_user->insert( 'facturen_regels', $insert );
				
				//invoer juiste factuur ID meegeven
				$this->_updateInvoerMetFactuurIDs( 'invoer_kilometers', $factuur_id, $kmgroep['invoer_ids'] );
			}
		}
		
		//dan de vergoedingen
		if( isset( $werknemer_array['vergoedingengroep'] ) && is_array( $werknemer_array['vergoedingengroep'] ) && count( $werknemer_array['vergoedingengroep'] ) > 0 )
		{
			foreach( $werknemer_array['vergoedingengroep'] as $doorbelasten => $vergoedingengroep )
			{
				$insert['row_start'] = NULL;
				$insert['omschrijving'] = $vergoedingengroep['naam'];
				$insert['uren_aantal'] = NULL;
				$insert['uren_decimaal'] = 1;
				$insert['verkooptarief'] = $vergoedingengroep['bedrag'];
				$insert['factor'] = $vergoedingengroep['factor'];
				$insert['bruto_uurloon'] = $vergoedingengroep['bedrag'];
				$insert['percentage'] = 100;
				$insert['uitkeren_werknemer'] = $vergoedingengroep['uitkeren_werknemer'];
				$insert['invoer_ids'] = $vergoedingengroep['invoer_id'];
				$insert['doorbelasten_aan'] = $vergoedingengroep['doorbelasten'];
				
				//niet uitkeren aan werknemer is geen kosten gemaalt, kan alleen als het doorbelasten aan inlener is
				if( $insert['uitkeren_werknemer'] == 1 && $insert['doorbelasten_aan'] != 'uitzender' )
				{
					$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['bruto_uurloon'] * $insert['factor'] * ( $insert['percentage'] / 100 ) ), 2 );
					$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
				} else
					$insert['subtotaal_kosten'] = 0;
				
				//wanneer doorbelasten naar uitzender, dan NIET op de verkoop factuur
				if( $insert['doorbelasten_aan'] == 'inlener' )
				{
					$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * $insert['factor'] * 1 ), 2 );
					$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
				} else
					$insert['subtotaal_verkoop'] = 0;
				
				$this->db_user->insert( 'facturen_regels', $insert );
				
				//invoer juiste factuur ID meegeven
				$this->_updateInvoerMetFactuurIDs( 'invoer_vergoedingen', $factuur_id, array($vergoedingengroep['invoer_id']=>1));
			}
		}
		
		//laatste regel
		unset( $insert );
		
		//algemene inserts
		$insert['factuur_id'] = $factuur_id;
		$insert['werknemer_id'] = $werknemer_id;
		
		$insert['row_end'] = 1;
		$insert['subtotaal_verkoop'] = $werknemer_bedrag_verkoop;
		$insert['subtotaal_kosten'] = $werknemer_bedrag_kosten;
		
		$this->db_user->insert( 'facturen_regels', $insert );

		return true;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * invoer updaten met fatcuur ID
	 *
	 */
	private function _updateInvoerMetFactuurIDs( $table, $factuur_id, $invoer_ids ): void
	{
		$this->db_user->query( "UPDATE $table SET factuur_id = $factuur_id WHERE invoer_id IN (". array_keys_to_string($invoer_ids) . ") " );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur aanmaken
	 * geef ID terug, anders NULL
	 *
	 */
	private function _insertConceptMargeFactuur( $factuur ): ?int
	{
		$insert['sessie_id'] = $this->_sessie_id;
		$insert['parent_id'] = $factuur['factuur_id'];
		$insert['concept'] = 1;
		$insert['credit'] = 0;
		$insert['marge'] = 1;
		$insert['tijdvak'] = $this->_tijdvak;
		$insert['jaar'] = $this->_jaar;
		$insert['periode'] = $this->_periode;
		$insert['uitzender_id'] = $this->_uitzender_id;
		$insert['inlener_id'] = $this->_inlener_id;
		$insert['factuur_datum'] = date( 'Y-m-d' );
		
		//Marge altijd met BTW, marge is negatief
		$insert['bedrag_excl'] = -($factuur['bedrag_excl'] - $factuur['kosten_excl']);
		$insert['bedrag_btw'] = round( ($insert['bedrag_excl'] * 0.21) ,2);
		$insert['bedrag_incl'] = $insert['bedrag_excl'] + $insert['bedrag_btw'];
		$insert['bedrag_openstaand'] = abs($insert['bedrag_incl']);
	
		$this->db_user->insert( 'facturen', $insert );
		
		if( $this->db_user->insert_id() > 0 )
			return $this->db_user->insert_id();
		
		return NULL;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur aanmaken
	 * geef ID terug, anders NULL
	 *
	 */
	private function _insertConceptVerkoopFactuur( $project_id = NULL ): ?int
	{
		//project key komt door als 0
		if( $project_id == 0 )
			$project_id = NULL;
		
		$insert['sessie_id'] = $this->_sessie_id;
		$insert['concept'] = 1;
		$insert['credit'] = 0;
		$insert['marge'] = 0;
		$insert['tijdvak'] = $this->_tijdvak;
		$insert['jaar'] = $this->_jaar;
		$insert['periode'] = $this->_periode;
		$insert['project_id'] = $project_id;
		$insert['uitzender_id'] = $this->_uitzender_id;
		$insert['inlener_id'] = $this->_inlener_id;
		$insert['factuur_datum'] = date( 'Y-m-d' );
		$insert['verval_datum'] = date( 'Y-m-d', strtotime( ' +' . $this->_setting_termijn . ' days' ) );
		
		$this->db_user->insert( 'facturen', $insert );
		
		if( $this->db_user->insert_id() > 0 )
		{
			$factuur_id = $this->db_user->insert_id();
			
			//cessietekst erbij
			$factoring = $this->werkgever->factoring();
			
			$insert_cessie['iban_factoring'] = $factoring['iban'];
			$insert_cessie['cessie_tekst'] = $factoring['cessie_tekst'];
			$insert_cessie['factuur_id'] = $factuur_id;
			$this->db_user->insert( 'facturen_cessie_tekst', $insert_cessie );
			
			return $factuur_id;
		}
		
		return NULL;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Controleren uren op bruto uurloon en verkooptarieven en factor
	 *
	 */
	private function _checkBedragen(): bool
	{
		//geen uren is geen controle
		if( count( $this->_invoer_array ) == 0 )
			return true;
		
		$bedragen_error = false;
		
		//log check
		$this->_sessieLog( 'action', "bedragen controleren" );
		
		foreach( $this->_group_array as $project_array )
		{
			foreach( $project_array as $werknemer_id => $werknemer_array )
			{
				//geen uren, dan volgende werknemer
				if( !isset( $werknemer_array['urengroep'] ) )
					continue;
				
				foreach( $werknemer_array['urengroep'] as $doorbelasten => $doorbelastengroep )
				{
					foreach( $doorbelastengroep['urentypes'] as $categorie => $urengroep )
					{
						if( $urengroep['bruto_loon'] === NULL || $urengroep['bruto_loon'] == '' || $urengroep['bruto_loon'] == 0 )
						{
							$this->_error[] = 'Ongeldig bruto uurloon voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
							$this->_sessieLog( 'error', "ongeldig uurloon bij $werknemer_id" );
							$bedragen_error = true;
						}
						
						if( $urengroep['verkooptarief'] === NULL || $urengroep['verkooptarief'] == '' || $urengroep['verkooptarief'] == 0 )
						{
							$this->_error[] = 'Ongeldig verkooptarief voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
							$this->_sessieLog( 'error', "ongeldig verkooptarief bij $werknemer_id" );
							$bedragen_error = true;
						}
						
						if( $urengroep['factor'] === NULL || $urengroep['factor'] == '' || $urengroep['factor'] < 1.4 )
						{
							$this->_error[] = 'Ongeldige factor voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
							$this->_sessieLog( 'error', "ongeldige factor bij $werknemer_id" );
							$bedragen_error = true;
						}
					}
				}
			}
		}
		
		if( !$bedragen_error )
			return true;
		else
			return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Controleren alle invoer aan een project is gekoppeld
	 *
	 */
	private function _checkProjecten(): bool
	{
		//niet splitsen is niet controleren
		if( $this->_setting_split_project == 0 )
			return true;
		
		//log check
		$this->_sessieLog( 'action', "projecten controleren" );
		
		if( !isset( $this->_group_array[NULL] ) )
			return true;
		
		foreach( $this->_group_array[NULL] as $werknemer_id => $werknemer_array )
		{
			//uren
			if( isset( $werknemer_array['urengroep'] ) )
				$this->_error[] = 'Er zijn uren gevonden die niet onder een project vallen bij ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ')';
			
			//kilometers
			if( isset( $werknemer_array['kmgroep'] ) )
				$this->_error[] = 'Er zijn kilometers gevonden die niet onder een project vallen bij ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ')';
			
			//vergoedingen
			if( isset( $werknemer_array['vergoedingengroep'] ) )
				$this->_error[] = 'Er zijn vergoedingen gevonden die niet onder een project vallen bij ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ')';
			
		}
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Controleren of kilometers en voergoedingen juist zijn doorbelast
	 *
	 */
	private function _checkDoorbelasten(): bool
	{
		$doorbelast_error = false;
		
		//log check
		$this->_sessieLog( 'action', "doorbelasten controleren" );
		
		foreach( $this->_group_array as $project_array )
		{
			foreach( $project_array as $werknemer_id => $werknemer_array )
			{
				//kilometers
				if( isset( $werknemer_array['kmgroep'] ) )
				{
					if( isset( $werknemer_array['kmgroep'][NULL] ) )
					{
						$doorbelast_error = true;
						$this->_error[] = 'Er zijn niet-doorbelaste kilometers gevonden bij ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . array_keys_to_string( $werknemer_array['kmgroep'][NULL]['invoer_ids'] ) . ']';
					}
				}
				
				//vergoedingen
				if( isset( $werknemer_array['vergoedingengroep'] ) )
				{
					if( isset( $werknemer_array['vergoedingengroep'][NULL] ) )
					{
						$doorbelast_error = true;
						$this->_error[] = 'Er zijn niet-doorbelaste vergoedingen gevonden bij ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ')';
					}
				}
			}
		}
		
		if( !$doorbelast_error )
			return true;
		else
			return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * vergoedingen groeperen en optellen voor factuur regels
	 * vergoedingen per project
	 * TODO: bij projecten verdelen over de projecten op basis van uren
	 */
	private function _groupVergoedingenInvoer(): bool
	{
		//log kilometers groeperen
		$this->_sessieLog( 'action', "vergoedingen optellen" );
		
		//door de uren lopen
		foreach( $this->_invoer_array['vergoedingen'] as $werknemer_id => $rows )
		{
			foreach( $rows as $invoer_id => $row )
			{
				//juiste projec ID, bij splitsen
				$project_id = ( $this->_setting_split_project == 0 ) ? NULL : $row['project_id'];
				
				//rij toevoegen, 1 op 1 overnemen
				$this->_group_array[$project_id][$werknemer_id]['vergoedingengroep'][$row['doorbelasten']] = $row;
				
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * km groeperen en optellen voor factuur regels
	 * km per project
	 *
	 */
	private function _groupKmInvoer(): bool
	{
		//log kilometers groeperen
		$this->_sessieLog( 'action', "kilometers optellen" );
		
		//door de uren lopen
		foreach( $this->_invoer_array['km'] as $werknemer_id => $rows )
		{
			foreach( $rows as $invoer_id => $row )
			{
				//juiste projec ID, bij splitsen
				$project_id = ( $this->_setting_split_project == 0 ) ? NULL : $row['project_id'];
				
				//init wanneer nodig
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']] ) )
					$this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']] = array(
						'totaal_km' => 0,
						'factor' => 1,
						'doorbelasten' => $row['doorbelasten'],
						'invoer_ids' => array()
					);
				
				//rij optellen bij totaal en ID naar array
				$this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']]['totaal_km'] += $row['aantal'];
				$this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']]['invoer_ids'][$row['invoer_id']] = 1;
				
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren groeperen en optellen voor factuur regels
	 * uren per type en project en locatie tekst
	 *
	 */
	private function _groupUrenInvoer(): bool
	{
		//log uren groeperen
		$this->_sessieLog( 'action', "uren optellen" );
		
		//door de uren lopen
		foreach( $this->_invoer_array['uren'] as $werknemer_id => $rows )
		{
			foreach( $rows as $invoer_id => $row )
			{
				//juiste projec ID, bij splitsen
				$project_id = ( $this->_setting_split_project == 0 ) ? NULL : $row['project_id'];
				
				//set key
				$key = $this->_buildUrenKey( $row );
				
				//doorbelasten instellen
				if( $row['doorbelasten_uitzender'] == 1 )
					$doorbelasten = 'uitzender';
				else
					$doorbelasten = 'inlener';
				
				//totaal telling standaard uren voor pensioen
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['uren_totaal'] ) )
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['uren_totaal'] = 0;
				
				//init wanneer nodig
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key] ) )
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key] = array(
						'totaal_uren' => 0,
						'bruto_loon' => $row['bruto_loon'],
						'verkooptarief' => $row['verkooptarief'],
						'percentage' => $row['percentage'],
						'factor' => $row['factor'],
						'doorbelasten_uitzender' => $row['doorbelasten_uitzender'],
						'dag_telling' => array(),
						'week_telling' => array(),
						'invoer_ids' => array()
					);
				
				//rij optellen bij totaal en ID naar array
				$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['totaal_uren'] += $row['aantal'];
				$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['invoer_ids'][$row['invoer_id']] = 1;
				
				// dag en week telling bijwerken
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['dag_telling'][$row['dag_nr']] ) )
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['dag_telling'][$row['dag_nr']] = 0;
				$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['dag_telling'][$row['dag_nr']] += $row['aantal'];
				
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['week_telling'][$row['dag_nr']] ) )
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['week_telling'][$row['week_nr']] = 0;
				$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key]['week_telling'][$row['week_nr']] += $row['aantal'];
				
				//standaard uren optellen voor uitrekenen pensioen
				if( $row['categorie'] == 'uren' )
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['uren_totaal'] += $row['aantal'];
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren key maken
	 * naam - project - locatie
	 */
	private function _buildUrenKey( $row ): string
	{
		//basis
		$key = $row['label'];
		
		//project tekst erbij, alleen wanneer niet gesplitst word
		if( $this->_setting_split_project == 0 )
		{
			if( $row['project_tekst'] !== NULL )
				$key .= ' - ' . $row['project_tekst'];
		}
		
		//locatie tekst erbij
		if( $row['locatie_tekst'] !== NULL )
			$key .= ' - ' . $row['locatie_tekst'];
		
		return $key;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * invoer laden en naar array
	 * array is opgebouwd als volgt
	 * $_invoer[type(uren,km,vergoedingen,pensioen)][werknemer_id][row]
	 *
	 */
	public function _invoerToArray(): bool
	{
		//init
		$this->_invoer_array['uren'] = array();
		$this->_invoer_array['km'] = array();
		$this->_invoer_array['vergoedingen'] = array();
		$this->_invoer_array['pensioen'] = array();
		
		//invoer classes laden
		$invoerUren = new InvoerUren( $this->invoer );
		$invoerKm = new InvoerKm( $this->invoer );
		$invoervergoedingen = new InvoerVergoedingen( $this->invoer );
		
		//door werknemers lopen en invoer laden
		foreach( $this->_inlener_werknemers as $werknemer_id => $array )
		{
			//per werknemer loggen
			$this->_sessieLog( 'load', "input loop werknemer: $werknemer_id" );
			
			//werknemer ID instellen
			$invoerUren->setWerknemer( $werknemer_id );
			$invoerKm->setWerknemer( $werknemer_id );
			$invoervergoedingen->setWerknemer( $werknemer_id );
			
			//ureninvoer
			if( NULL !== $uren = $invoerUren->getWerknemerUrenRijen() )
				$this->_invoer_array['uren'][$werknemer_id] = $uren;
			
			//km
			if( NULL !== $km = $invoerKm->getWerknemerKilometerRijen() )
				$this->_invoer_array['km'][$werknemer_id] = $km;
			
			//vergoedingen
			if( $vergoedingen = $invoervergoedingen->getWerknemerVergoedingenRijen() )
			{
				if( count( $vergoedingen ) !== 0 )
					$this->_invoer_array['vergoedingen'][$werknemer_id] = $vergoedingen;
			}
			
		}
		
		//check of er invoer is, anders foutmelding naar gebruiker
		if( count( $this->_invoer_array['uren'] ) == 0 && count( $this->_invoer_array['km'] ) == 0 && count( $this->_invoer_array['vergoedingen'] ) == 0 )
		{
			$this->_error[] = 'Er is geen ureninvoer gevonden voor deze periode';
			return false;
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle werknemers die bij inlener zijn geplaatst ophalen
	 * TODO: aleen voor deze periode
	 * $werknemer['werknemer_id']
	 * $werknemer['naam']
	 */
	public function _werknemersForInlener(): bool
	{
		$werknemers = $this->invoer->listWerknemers();
		
		//stoppen bij lege lijst
		if( count( $werknemers ) === 0 )
			return false;
		
		//extra sorteren omdat key niet werknemer_id is
		foreach( $werknemers as $array )
			$this->_inlener_werknemers[$array['werknemer_id']]['naam'] = $array['naam'];
		
		//log actie
		$this->_sessieLog( 'load', json_encode( $this->_inlener_werknemers ) );
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Tijdvak info instellen
	 * TODO: controle op periodes
	 *
	 */
	public function setTijdvak( $data )
	{
		if( isset( $data['tijdvak'] ) )
			$this->_tijdvak = $data['tijdvak'];
		if( isset( $data['periode'] ) )
			$this->_periode = intval( $data['periode'] );
		if( isset( $data['jaar'] ) )
			$this->_jaar = intval( $data['jaar'] );
		
		//tijdvak invoer zelfde stellen
		$this->invoer->setTijdvak( $data );
		
		//log tijdvak
		$this->_sessieLog( 'setting', json_encode( $data ) );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uitzender ID
	 *
	 */
	public function setUitzender( $uitzender_id )
	{
		//voor deze class
		$this->_uitzender_id = intval( $uitzender_id );
		
		//invoer ook vullen
		$this->invoer->setUitzender( $this->_uitzender_id );
		
		//inlener facturatiegegevens
		$uitzender = new Uitzender( $this->_uitzender_id );
		$this->_uitzender_bedrijfsgegevens = $uitzender->bedrijfsgegevens();
		
		//alles voor de uitzender is geladen
		$this->_sessieLog( 'setting', "uitzender_id: $uitzender_id" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * inlener ID
	 *
	 */
	public function setInlener( $inlener_id )
	{
		//voor deze class
		$this->_inlener_id = intval( $inlener_id );
		
		//invoer meenemen
		$this->invoer->setInlener( $this->_inlener_id );
		
		//inlener facturatiegegevens
		$inlener = new Inlener( $this->_inlener_id );
		$this->_inlener_bedrijfsgegevens = $inlener->bedrijfsgegevens();
		$this->_inlener_factuurgegevens = $inlener->factuurgegevens();
		
		//alles voor de inlener is geladen
		$this->_sessieLog( 'setting', "inlener_id: $inlener_id" );
		
		//settings voor splitsen
		if( $this->_inlener_factuurgegevens['factuur_per_medewerker'] == 1 )
		{
			$this->_setting_split_werknemer = 1;
			$this->_sessieLog( 'setting', "split_werknemer: 1" );
		}
		
		if( $this->_inlener_factuurgegevens['factuur_per_project'] == 1 )
		{
			$this->_setting_split_project = 1;
			$this->_sessieLog( 'setting', "split_project: 1" );
		}
		
		if( $this->_inlener_factuurgegevens['btw_verleggen'] == 1 )
		{
			$this->_setting_btw_verleggen = 1;
			$this->_sessieLog( 'setting', "btw_verleggen: 1" );
		}
		
		if( $this->_inlener_factuurgegevens['g_rekening'] == 1 )
		{
			$this->_setting_g_rekening = 1;
			$this->_setting_g_rekening_percentage = $this->_inlener_factuurgegevens['g_rekening_percentage'];
			$this->_sessieLog( 'setting', "g_rekening: 1, g_rekening_percentage: " . $this->_inlener_factuurgegevens['g_rekening_percentage'] );
		}
		
		$this->_setting_termijn = $this->_inlener_factuurgegevens['termijn'];
		$this->_sessieLog( 'setting', "termijn: " . $this->_inlener_factuurgegevens['termijn'] );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * database opschonen
	 *
	 */
	static function clear()
	{
		if( ENVIRONMENT != 'development' )
			die( 'GEEN TOEGANG VOOR DEZE FUNCTiE' );
		
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$db_user->query( "SET FOREIGN_KEY_CHECKS = 0" );
		$db_user->query( "TRUNCATE facturen" );
		$db_user->query( "TRUNCATE facturen_bijlages" );
		$db_user->query( "TRUNCATE facturen_cessie_tekst" );
		$db_user->query( "TRUNCATE facturen_log" );
		$db_user->query( "TRUNCATE facturen_regels" );
		$db_user->query( "TRUNCATE facturen_sessies" );
		$db_user->query( "TRUNCATE facturen_sessies_log" );
		$db_user->query( "SET FOREIGN_KEY_CHECKS = 1" );
		
		$db_user->query( "UPDATE invoer_uren SET factuur_id = NULL" );
		$db_user->query( "UPDATE invoer_kilometers SET factuur_id = NULL" );
		$db_user->query( "UPDATE invoer_vergoedingen SET factuur_id = NULL" );
		
		//delete facturen
		$files = scandir('userf1les_o7dm6\werkgever_dir_1\facturen\2020');
		foreach( $files as $file )
		{
			$path = 'userf1les_o7dm6/werkgever_dir_1/facturen/2020/' . $file;
			if( file_exists( $path ) && !is_dir( $path ) )
				unlink( $path );
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Start factuur sessie
	 *
	 */
	private function _sessieStart()
	{
		$insert['user_id'] = $this->user->user_id;
		$insert['ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['sessie_start'] = microtime( true );
		
		$this->db_user->insert( 'facturen_sessies', $insert );
		
		$this->_sessie_id = $this->db_user->insert_id();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen weer weggooien
	 * TODO: functie maken
	 */
	private function _errorDeleteAll()
	{
	
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Start factuur sessie
	 *
	 */
	private function _sessieFinish( $abort = NULL )
	{
		if( $abort !== NULL )
			$this->_sessieLog( 'abort', $abort );
		
		$update['sessie_end'] = microtime( true );
		$this->db_user->where( 'sessie_id', $this->_sessie_id );
		$this->db_user->update( 'facturen_sessies', $update );
		
		//calc duration
		$this->db_user->query( "UPDATE facturen_sessies SET sessie_duration = sessie_end - sessie_start WHERE  sessie_id = $this->_sessie_id LIMIT 1" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log sessie gegevens
	 *
	 */
	private function _sessieLog( $type, $message, $factuur_id = NULL, $flag = 0 )
	{
		$backtrace = debug_backtrace( 0, 2 );
		
		if( isset( $backtrace[1]['function'] ) )
			$insert['method'] = $backtrace[1]['function'];
		
		$insert['sessie_id'] = $this->_sessie_id;
		$insert['type'] = $type;
		$insert['message'] = $message;
		$insert['factuur_id'] = $factuur_id;
		$insert['flag'] = $flag;
		$insert['microtime'] = microtime( true );;
		
		@$this->db_user->insert( 'facturen_sessies_log', $insert );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 */
	public function errors()
	{
		//output for debug
		if( isset( $_GET['debug'] ) )
		{
			if( $this->_error === NULL )
				show( 'Geen errors' );
			else
				show( $this->_error );
		}
		
		if( $this->_error === NULL )
			return false;
		
		return $this->_error;
	}
}

?>