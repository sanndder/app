<?php

namespace models\facturatie;

use models\Connector;
use models\file\Pdf;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurMarge;
use models\pdf\PdfFactuurVerkoopUren;
use models\pdf\PdfFactuurZzp;
use models\uitzenders\Uitzender;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\verloning\Invoer;
use models\verloning\InvoerAangenomenwerk;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;
use models\werknemers\PlaatsingGroup;
use models\werknemers\WerknemerGroup;
use models\zzp\Zzp;

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
	private $_uitzender_korting = NULL;
	private $_uitzender_systeeminstellingen = NULL;
	
	private $_inlener_bedrijfsgegevens = NULL;
	private $_inlener_factuurgegevens = NULL;
	private $_inlener_projecten = NULL;
	private $_inlener_werknemers = NULL;
	private $_werknemers_pensioen = NULL;
	
	private $_setting_split_project = 0;
	private $_setting_split_werknemer = 0;
	
	private $_setting_termijn = 30;
	
	private $_setting_eu_levering = 0;
	private $_setting_btw_verleggen = 0;
	private $_setting_btw_tarief = 21;
	
	private $_setting_g_rekening = 0;
	private $_setting_g_rekening_percentage = 0;
	
	private $_setting_uren_werkweek = 40;
	
	//array met alle ruwe data
	private $_invoer_array = NULL;
	
	//array met alles opgeteld en gegroepeerd
	private $_group_array = NULL;
	private $_aangenomenwerk_array = NULL;
	
	private $_preview = false;
	
	private $_rebuild = false;
	private $_rebuild_kosten_file_name = NULL;
	private $_rebuild_marge_file_name = NULL;
	
	//invoer object laden, wordt meerdere malen gebruikt
	private $invoer = NULL;
	
	protected $_inlener_id = NULL;
	protected $_uitzender_id = NULL;
	
	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	//TODO: instelbaar via app maken
	protected $_stipp_basis = 3;
	
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
	 * voorbeeld bekijken
	 *
	 */
	public function preview(): void
	{
		$this->_preview = true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * voorbeeld bekijken
	 *
	 */
	public function rebuildPdf( $factuur_id ): void
	{
		$this->_rebuild = true;
		
		$this->_sessieStart();
		$this->_sessieLog( 'setting', 'rebuild: true' );
		
		//details en regels hier ophalen voor hergebruik
		$factuur = $this->getFactuurDetails( $factuur_id );
		$factuur_regels = $this->getFactuurRegels( $factuur_id );
		
		//marge ophalen
		$sql = "SELECT factuur_id FROM facturen WHERE parent_id = $factuur_id AND marge = 1";
		$query = $this->db_user->query( $sql );
		
		$marge = $query->row_array();
		
		$this->setTijdvak( $factuur );
		
		$this->_pdfKosten( $factuur, $factuur_regels );
		
		$this->setUitzender( $factuur['uitzender_id'] );
		$this->_pdfMarge( $marge['factuur_id'] );
		
		$this->_sessieFinish();
		
		show( $this->_rebuild_kosten_file_name, 'kosten' );
		show( $marge );
		show( $this->_rebuild_marge_file_name, 'marge' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Functie die alles aanstuurt
	 *
	 */
	public function run(): bool
	{
		
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
	 * Factuur vanuit de database maken
	 *
	 */
	public function runCustom( $factuur_id ): bool
	{
		$this->_sessieStart();
		
		//details en regels hier ophalen voor hergebruik
		$factuur = $this->getFactuurDetails( $factuur_id );
		$factuur_regels = $this->getFactuurRegels( $factuur_id );
		
		$this->setTijdvak( $factuur );
		$this->setInlener( $factuur['inlener_id'] );
		$this->setUitzender( $factuur['uitzender_id'] );
		
		//verkoopfactuur ook sessie meegeven
		$this->_setVerkoopSessie( $factuur['factuur_id'] );
		
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
		if( !$this->_pdfMarge( $marge_id ) )
		{
			$this->_sessieLog( 'error', "marge pdf kon niet worden aangemaakt", $factuur_id, 1 );
			$this->_errorDeleteAll();
			return false;
		}
		
		//alle pdf's zijn gemaakt, van concept af
		if( !$this->_conceptToFinal() )
		{
			$this->_sessieFinish( "fout definitief maken facturen" );
			return false;
		}
		
		$this->_sessieFinish();
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle pdf's van de sessie van concept af
	 *
	 */
	private function _setVerkoopSessie( $factuur_id ): void
	{
		$this->db_user->query( "UPDATE facturen SET sessie_id = $this->_sessie_id WHERE factuur_id = $factuur_id" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle pdf's van de sessie van concept af
	 *
	 */
	private function _conceptToFinal(): bool
	{
		//moet de fatcuur naar de wachtrij?
		if( isset( $this->_uitzender_systeeminstellingen['facturen_wachtrij'] ) && $this->_uitzender_systeeminstellingen['facturen_wachtrij'] == 1 && $this->_inlener_factuurgegevens['factuur_wachtrij'] == 1 )
			$this->db_user->query( "UPDATE facturen SET wachtrij = 1, wachtrij_akkoord = 0 WHERE sessie_id = $this->_sessie_id AND marge = 0" );
		
		$update['concept'] = 0;
		
		$this->db_user->where( 'sessie_id', $this->_sessie_id );
		$this->db_user->update( 'facturen', $update );
		
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
			if( !$this->_pdfMarge( $marge_id ) )
			{
				$this->_sessieLog( 'error', "marge pdf kon niet worden aangemaakt", $factuur_id, 1 );
				$this->_errorDeleteAll();
				return false;
			}
			
			//bij bemiddeling ook zzp'facturen aanmaken
			if( $this->user->werkgever_type == 'bemiddeling' )
			{
				if( !$this->_pdfZzp( $factuur_id ) )
				{
					$this->_sessieLog( 'error', "ZZP pdf kon niet worden aangemaakt", $factuur_id, 1 );
					return false;
				}
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
	 * pdf kostenoverzicht maken
	 *
	 */
	private function _pdfMarge( $marge_id ): bool
	{
		//details en regels hier ophalen voor hergebruik
		$factuur = $this->getFactuurDetails( $marge_id );
		$regels = $this->getFactuurRegels( $marge_id );
		
		//log action
		$this->_sessieLog( 'action', "start margefactuur pdf", $factuur['parent_id'] );
		
		$pdf = new PdfFactuurMarge();
		
		$pdf->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ) );
		$pdf->setType( 'marge' );
		
		$pdf->setRelatieGegevens( $this->_uitzender_bedrijfsgegevens, NULL );
		$pdf->setFactuurdatum( $factuur['factuur_datum'] );
		
		//factuur nur ophalen
		if( !$this->_rebuild )
			$factuur_nr = $this->_getFactuurNr( $marge_id );
		else
			$factuur_nr = $factuur['factuur_nr'];
		
		$pdf->setFactuurNr( $factuur_nr );
		
		$pdf->setFooter();
		$pdf->setHeader( $factuur );
		
		$pdf->setBody( $factuur, $regels );
		
		$update['file_dir'] = 'facturen/' . $factuur['jaar'];
		$update['file_name'] = $factuur['parent_id'] . '_marge_' . $factuur_nr . '_' . generateRandomString( 4 ) . '.pdf';
		
		//naam opslaan zodat we die terug kunnen geven
		$this->_rebuild_marge_file_name = $update['file_name'];
		
		$pdf->setFileDir( $update['file_dir'] );
		$pdf->setFileName( $update['file_name'] );
		
		$this->_sessieLog( 'action', "generate marge pdf", $factuur['parent_id'] );
		//$pdf->preview();
		
		if( !$pdf->generate() )
			return false;
		
		//update met juiste gegevens, alleen wanneer we niet rebuilden
		if( !$this->_rebuild )
		{
			$this->db_user->where( 'factuur_id', $marge_id );
			$this->db_user->update( 'facturen', $update );
			
			if( $this->db_user->affected_rows() < 1 )
			{
				$this->_sessieLog( 'error', "update margefactuur mislukt", $factuur['parent_id'] );
				return false;
			}
		}
		
		//log action
		$this->_sessieLog( 'action', "einde margefactuur pdf", $factuur['parent_id'] );
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf zzp'er maken
	 *
	 */
	public function _pdfZzp( $factuur_id ): bool
	{
		//factuur per zzp'er
		$factuur = $this->getFactuurDetails( $factuur_id );
		$factuur_regels = $this->getFactuurRegels( $factuur_id );
		
		//regels per zzp er
		foreach( $factuur_regels as $id => $r )
		{
			$regels[$r['zzp_id']][$r['regel_id']] = $r;
			
			//nieuwe totaal telling
			if( !isset( $totaal_excl[$r['zzp_id']] ) )
				$totaal_excl[$r['zzp_id']] = 0;
			
			//optellen
			if( $r['uitkeren_werknemer'] == 1 )
				$totaal_excl[$r['zzp_id']] += $r['subtotaal_kosten'];
			
			if( $r['row_end'] == 1 )
				$regels[$r['zzp_id']][$r['regel_id']]['subtotaal_kosten'] = $totaal_excl[$r['zzp_id']];
		}
		
		//per zzp'er een factuur
		foreach( $regels as $zzp_id => $zzp_regels )
		{
			unset( $zzp_factuur );
			
			//log action
			$this->_sessieLog( 'action', "start zzp pdf", $factuur_id );
			
			//zzp class
			$zzp = new Zzp( $zzp_id );
			
			//new pdf
			$pdf = new PdfFactuurZzp();
			$pdf->setType( 'zzp' );
			
			//fotoer anders instelllen
			$bedrijfsgegevens = $zzp->bedrijfsgegevens();
			$persoonsgegevens = $zzp->persoonsgegevens();
			$factuurgegevens = $zzp->factuurgegevens();
			
			$highestCount = $zzp->getCurrentFactuurCount();
			$zzp_factuur['factuur_nr_count'] = $highestCount + 1;
			$zzp_factuur['factuur_nr'] = $zzp_id . '-' . $zzp_factuur['factuur_nr_count'];
			
			$pdf->setFactuurNr( $zzp_factuur['factuur_nr'] );
			$pdf->setBedrijfsgegevens( $bedrijfsgegevens + [ 'email' => $persoonsgegevens['email'] ] + $factuurgegevens );
			
			$pdf->setTijdvak( array( 'tijdvak' => $factuur['tijdvak'], 'jaar' => $factuur['jaar'], 'periode' => $factuur['periode'] ) );
			$pdf->setType( 'zzp' );
			
			$pdf->setFactuurdatum( $factuur['factuur_datum'] );
			
			$zzp_factuur['tijdvak'] = $factuur['tijdvak'];
			$zzp_factuur['jaar'] = $factuur['jaar'];
			$zzp_factuur['periode'] = $factuur['periode'];
			$zzp_factuur['verval_datum'] = date( 'Y-m-d', strtotime( $factuur['factuur_datum'] . ' + 8 days' ) );
			$pdf->setVervaldatum( $zzp_factuur['verval_datum'] );
			
			$pdf->setFooter();
			$pdf->setHeader( $factuur );
			
			$zzp_factuur['bedrag_excl'] = $totaal_excl[$zzp_id];
			$zzp_factuur['bedrag_btw'] = NULL;
			$zzp_factuur['bedrag_incl'] = $zzp_factuur['bedrag_excl'];
			
			if( $factuur['bedrag_btw'] !== NULL )
			{
				$zzp_factuur['bedrag_btw'] = round( ( $zzp_factuur['bedrag_excl'] * 0.21 ), 2 );
				$zzp_factuur['bedrag_incl'] = $zzp_factuur['bedrag_excl'] + $zzp_factuur['bedrag_btw'];
			}
			
			$zzp_factuur['bedrag_openstaand'] = $zzp_factuur['bedrag_incl'];
			
			$pdf->setBody( $zzp_factuur, $zzp_regels );
			
			$zzp_factuur['file_dir'] = 'zzp/facturen/' . $factuur['jaar'];
			$zzp_factuur['file_name'] = $factuur['factuur_id'] . '_factuur_' . generateRandomString( 4 ) . '.pdf';
			
			$pdf->setFileDir( $zzp_factuur['file_dir'] );
			$pdf->setFileName( $zzp_factuur['file_name'] );
			
			if( !$pdf->generate() )
				return false;
			
			//insert met juiste gegevens
			$zzp_factuur['inlener_id'] = $factuur['inlener_id'];
			$zzp_factuur['uitzender_id'] = $factuur['uitzender_id'];
			$zzp_factuur['zzp_id'] = $zzp_id;
			$zzp_factuur['parent_id'] = $factuur['factuur_id'];
			$zzp_factuur['sessie_id'] = $this->_sessie_id;
			$zzp_factuur['user_id'] = $this->user->user_id;
			
			$this->db_user->insert( 'zzp_facturen', $zzp_factuur );
			if( $this->db_user->insert_id() == 0 )
			{
				$this->_sessieLog( 'error', "insert zzp factuur mislukt", $factuur['factuur_id'] );
				return false;
			} else
				$this->_sessieLog( 'action', "zzp factuur gegenereerd", $this->db_user->insert_id() );
			
		}
		
		//log action
		$this->_sessieLog( 'action', "einde zzp facturen pdf", $factuur['factuur_id'] );
		
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
		$this->_sessieLog( 'action', "start kostenoverzicht pdf", $factuur['factuur_id'] );
		
		$pdf = new PdfFactuurVerkoopUren();
		
		$pdf->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ) );
		$pdf->setType( 'kosten' );
		
		if( isset( $this->_uitzender_korting[$this->_inlener_id] ) )
			$pdf->setKortingUitzender( $this->_uitzender_korting[$this->_inlener_id] );
		else
			$pdf->setKortingUitzender( NULL );
		
		//kosten nooit afgesprokenwerk
		$pdf->setAangenomenwerk( 0 );
		
		$pdf->setFooter();
		$pdf->setHeader( $factuur );
		
		$pdf->setBody( $factuur, $regels );
		
		$insert['file_dir'] = 'facturen/' . $factuur['jaar'];
		$insert['file_name'] = $factuur['factuur_id'] . '_kostenoverzicht_' . generateRandomString( 4 ) . '.pdf';
		
		//naam opslaan zodat we die terug kunnen geven
		$this->_rebuild_kosten_file_name = $insert['file_name'];
		
		$pdf->setFileDir( $insert['file_dir'] );
		$pdf->setFileName( $insert['file_name'] );
		
		$this->_sessieLog( 'action', "generate kosten pdf", $factuur['factuur_id'] );
		if( !$pdf->generate() )
			return false;
		
		//insert met juiste gegevens, alleen wanneer het geen rebuild is
		if( !$this->_rebuild )
		{
			$insert['sessie_id'] = $this->_sessie_id;
			$insert['factuur_id'] = $factuur['factuur_id'];
			$insert['user_id'] = $this->user->user_id;
			
			$this->db_user->insert( 'facturen_kostenoverzicht', $insert );
			if( $this->db_user->insert_id() == 0 )
			{
				$this->_sessieLog( 'error', "insert kostenoverzicht mislukt", $factuur['factuur_id'] );
				return false;
			}
			
			//log action
			$this->_sessieLog( 'action', "einde kostenoverzicht pdf", $factuur['factuur_id'] );
		}
		
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
		$this->_sessieLog( 'action', "start verkoopfactuur pdf", $factuur['factuur_id'] );
		
		$pdf = new PdfFactuurVerkoopUren();
		$pdf->setType( 'verkoop' );
		
		$pdf->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ) );
		
		$pdf->setRelatieGegevens( $this->_inlener_bedrijfsgegevens, $this->_inlener_factuurgegevens );
		$pdf->setFactuurdatum( $factuur['factuur_datum'] );
		$pdf->setVervaldatum( $factuur['verval_datum'] );
		
		//resetten voor splitsen
		$pdf->setKortingUitzender( NULL );
		
		//wanneer verkoop gelijk aan kosten
		if( isset( $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] ) && $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] == 1 )
		{
			if( isset( $this->_uitzender_korting[$this->_inlener_id] ) )
				$pdf->setKortingUitzender( $this->_uitzender_korting[$this->_inlener_id] );
			else
				$pdf->setKortingUitzender( NULL );
		}
		
		//aangenomenwerk
		if( $this->_inlener_factuurgegevens['afgesproken_werk'] == 1 )
			$pdf->setAangenomenwerk( 1 );
		else
			$pdf->setAangenomenwerk( 0 );
		
		//wel of niet naar factoring
		if( $this->_inlener_factuurgegevens['factoring'] == 1 )
		{
			//cessietekst verpanding
			$pdf->setIbanFactoring( $factuur['iban_factoring'] );
			$pdf->setCessieTekst( $factuur['cessie_tekst'] );
		} else
			$pdf->setFactoring( $this->_inlener_factuurgegevens['factoring'] );
		
		if( $factuur['bedrag_grekening'] > 0 )
			$pdf->setGRekening( $factuur['bedrag_grekening'], $factuur['percentage_grekening'] );
		
		//factuur nur ophalen
		if( !$this->_preview )
			$factuur_nr = $this->_getFactuurNr( $factuur['factuur_id'] );
		else
			$factuur_nr = NULL;
		
		$pdf->setFactuurNr( $factuur_nr );
		$pdf->setFooter();
		$pdf->setHeader( $factuur );
		
		$pdf->setBody( $factuur, $regels );
		
		$update['file_dir'] = 'facturen/' . $factuur['jaar'];
		$update['file_name'] = $factuur['factuur_id'] . '_verkoop_uren_' . $factuur_nr . '_' . generateRandomString( 4 ) . '.pdf';
		
		$pdf->setFileDir( $update['file_dir'] );
		$pdf->setFileName( $update['file_name'] );
		
		//preview
		if( $this->_preview )
		{
			$this->_sessieLog( 'action', "voorbeeld bekijken", $factuur['factuur_id'] );
			$this->_sessieFinish();
			$pdf->preview();
		}
		
		//pdf maken
		$this->_sessieLog( 'action', "generate verkoop pdf", $factuur['factuur_id'] );
		$pdf_file = $pdf->generate();
		if( !$pdf_file )
			return false;
		
		//aantal pagina's
		$update['file_org_pages'] = $pdf_file->pageCount();
		$update['file_pages'] = $pdf_file->pageCount();
		
		//update met juiste gegevens
		$this->db_user->where( 'factuur_id', $factuur['factuur_id'] );
		$this->db_user->update( 'facturen', $update );
		
		//log action
		$this->_sessieLog( 'action', "einde verkoopfactuur pdf", $factuur['factuur_id'] );
		
		//bijlages invoegen
		if( $factuur['correctie'] != 1 )
		{
			if( !$this->_pdfVerkoopBijlages( $factuur['factuur_id'], $update['file_dir'], $update['file_name'], $factuur['project_id'] ) )
				$this->_sessieLog( 'error', 'bijlages invoegen mislukt', $factuur['factuur_id'] );
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bijlages aan factuur toevoegen
	 *
	 */
	private function _pdfVerkoopBijlages( $factuur_id, $file_dir, $file_name, $project_id = NULL ): bool
	{
		//log action
		$this->_sessieLog( 'action', 'bijlages invoegen', $factuur_id );
		
		//bijlages ophalen
		$bijlages = $this->invoer->getBijlages();
		
		//factuur als pdf
		$pdf = new Pdf( array( 'file_dir' => $file_dir, 'file_name' => $file_name ) );
		
		foreach( $bijlages as $bijlage )
		{
			if( $project_id === NULL || $project_id == $bijlage['project_id'] )
			{
				if( $pdf->addFileToPdf( $bijlage['file_dir'], $bijlage['file_name'] ) )
				{
					$this->db_user->query( "UPDATE invoer_bijlages SET factuur_id = $factuur_id, file_pages = " . $pdf->bijlagePageCount() . " WHERE file_id = " . $bijlage['file_id'] . " LIMIT 1" );
					$this->db_user->query( "UPDATE facturen SET file_pages = file_pages + " . $pdf->bijlagePageCount() . " WHERE factuur_id = $factuur_id LIMIT 1" );
				}
			}
		}
		
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
		//check uren werkweek
		if( !$this->_urenWerkweek() )
		{
			$this->_sessieFinish( 'te veel uren' );
			return false;
		}
		
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
		//juiste aangenomenwerkregels erbij
		if( $this->_inlener_factuurgegevens['afgesproken_werk'] == 1 )
		{
			//groeperen en optellen
			$this->_groupAangenomenwerkInvoer();
		}
		
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
		
		//check
		if( !is_array( $this->_group_array ) || count( $this->_group_array ) == 0 )
		{
			$this->_sessieFinish( 'Group array is leeg' );
			return false;
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * concept marge aanmaken
	 *
	 */
	private function _setConceptMargeFactuur( $factuur ): ?int
	{
		//start van de facturen
		$this->_sessieLog( 'action', "concept marge aanmaken" );
		
		//marge factuur in databases aanmaken
		if( NULL === $marge_id = $this->_insertConceptMargeFactuur( $factuur ) )
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
			
			//zijn er aangenomenwerk regels?
			$aangenomenwerk_array = NULL;
			if( $this->_inlener_factuurgegevens['afgesproken_werk'] == 1 )
			{
				if( $this->_aangenomenwerk_array !== NULL )
					$aangenomenwerk_array = $this->_aangenomenwerk_array[$project_id];
				
				$this->_insertConceptVerkoopFactuurRegelsAangenomenWerk( $factuur_id, $aangenomenwerk_array );
			}
			
			//factuur regels toevoegen
			foreach( $project_array as $werknemer_id => $werknemer_array )
				$this->_insertConceptVerkoopFactuurRegels( $factuur_id, $werknemer_id, $werknemer_array, $aangenomenwerk_array );
			//corrcties
			$this->_insertCorrecties( $factuur_id );
			
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
		$sql = "SELECT facturen.*, fct.cessie_tekst, fct.iban_factoring, inleners_projecten.omschrijving AS project
				FROM facturen
				LEFT JOIN inleners_projecten ON inleners_projecten.id = facturen.project_id
				LEFT JOIN facturen_cessie_tekst fct ON facturen.factuur_id = fct.factuur_id
				WHERE facturen.factuur_id = $factuur_id LIMIT 1";
		
		$query = $this->db_user->query( $sql );
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
		$query = $this->db_user->query( "SELECT regel_id, row_afgesprokenwerk, row_start, row_end, subtotaal_verkoop, subtotaal_kosten FROM facturen_regels WHERE factuur_id = $factuur_id AND row_start IS NULL" );
		
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
		$aangenomenwerk = 0;
		
		foreach( $query->result_array() as $row )
		{
			//losse regels optellen
			if( $row['row_afgesprokenwerk'] == 0 )
			{
				if( $row['row_end'] !== NULL )
				{
					$sub_totaal += $row['subtotaal_verkoop'];
					$sub_kosten += $row['subtotaal_kosten'];
				} else
				{
					$regel_totaal += $row['subtotaal_verkoop'];
					$kosten_totaal += $row['subtotaal_kosten'];
				}
			} //aangenomenwerk optellen
			else
			{
				if( $row['row_end'] === NULL )
					$aangenomenwerk += $row['subtotaal_verkoop'];
			}
		}
		
		//check for zero
		if( $sub_totaal == 0 || $kosten_totaal == 0 )
		{
			$this->_sessieLog( 'error', "Delen door 0", $factuur_id, 1 );
			return false;
		}
		
		//controleren of bedragen overeenkomen
		if( abs( ( $regel_totaal - $sub_totaal ) / $sub_totaal ) > 0.00001 )
		{
			$this->_sessieLog( 'error', "som regeltotaal en som subtotaal zijn niet aan elkaar gelijk", $factuur_id, 1 );
			return false;
		}
		
		if( abs( ( $sub_kosten - $kosten_totaal ) / $kosten_totaal ) > 0.00001 )
		{
			$this->_sessieLog( 'error', "som regelkosten en som kostentotaal zijn niet aan elkaar gelijk", $factuur_id, 1 );
			return false;
		}
		
		//aangenomenwerk ipv factuurbedrag
		if( $aangenomenwerk > 0 )
			$sub_totaal = $aangenomenwerk;
		
		//default incl gelijk aan excl
		$update['bedrag_excl'] = $sub_totaal;
		$update['bedrag_incl'] = $sub_totaal;
		
		//kosten, eventueel korting uitzender
		if( isset( $this->_uitzender_korting[$this->_inlener_id]['korting_percentage'] ) && $this->_uitzender_korting[$this->_inlener_id]['korting_percentage'] > 0 )
		{
			$update['kosten_korting'] = round( $sub_totaal * ( $this->_uitzender_korting[$this->_inlener_id]['korting_percentage'] / 100 ), 2 );
			$update['kosten_excl'] = $sub_kosten - $update['kosten_korting'];
			
			//bij kosten gelijk aan evrkoop
			if( isset( $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] ) && $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] == 1 )
			{
				$update['bedrag_korting'] = $update['kosten_korting'];
				$update['bedrag_excl'] = $update['kosten_excl'];
			}
		} else
		{
			$update['kosten_korting'] = 0; //default 0
			$update['kosten_excl'] = $sub_kosten;
		}
		
		//totaal kosten
		$update['kosten_incl'] = $update['kosten_excl'];
		
		//BTW verlegd of niet
		if( $this->_setting_btw_verleggen == 0 )
		{
			$update['bedrag_btw'] = round( ( $update['bedrag_excl'] * ($this->_setting_btw_tarief / 100) ), 2 );
			$update['bedrag_incl'] = $update['bedrag_excl'] + $update['bedrag_btw'];
			
			$update['kosten_btw'] = round( ( $update['kosten_excl'] * ($this->_setting_btw_tarief / 100) ), 2 ); //incl is hier nog gelijk aan excl - korting
			$update['kosten_incl'] = $update['kosten_excl'] + $update['kosten_btw']; //nieuwe incl waarde
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
	private function _insertConceptVerkoopFactuurRegelsAangenomenWerk( $factuur_id, $aangenomenwerk_array ): bool
	{
		$insert['factuur_id'] = $factuur_id;
		
		//aangenomenwerk voor project
		if( $aangenomenwerk_array != NULL )
		{
			//init
			$bedrag_aangenomenwerk = 0;
			foreach( $aangenomenwerk_array['regels'] as $invoer_id => $regel )
			{
				//updaten van de tabel
				$invoer_ids[0] = $invoer_id;
				
				$insert['row_afgesprokenwerk'] = 1;
				$insert['row_afgesprokenwerk'] = 1;
				$insert['omschrijving'] = $regel['omschrijving'];
				$insert['invoer_ids'] = json_encode( $invoer_id );
				
				//regeltotaal uitrekenen
				$bedrag_aangenomenwerk += $regel['bedrag'];
				$insert['subtotaal_verkoop'] = $regel['bedrag'];
				
				$this->db_user->insert( 'facturen_regels', $insert );
				
				//invoer juiste factuur ID meegeven, NIET VOOR VOORBEELD
				if( !$this->_preview )
					$this->_updateInvoerMetFactuurIDs( 'invoer_aangenomenwerk_regels', $factuur_id, $invoer_ids );
				
				/*
				if(isset($_GET['debug']))
				{
					show($aangenomenwerk_array);
					die();
				}*/
			}
			
			//laatste regel
			unset( $insert );
			
			//algemene inserts
			$insert['factuur_id'] = $factuur_id;
			$insert['row_afgesprokenwerk'] = 1;
			
			$insert['row_end'] = 1;
			$insert['subtotaal_verkoop'] = $bedrag_aangenomenwerk;
			$insert['subtotaal_kosten'] = 0;
			
			$this->db_user->insert( 'facturen_regels', $insert );
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur regels aanmaken
	 * TODO: insert naar apparte functie verplaatsen
	 */
	private function _insertConceptVerkoopFactuurRegels( $factuur_id, $werknemer_id, $werknemer_array, $aangenomenwerk_array ): bool
	{
		//log regels
		$this->_sessieLog( 'action', "regels voor factuur ($werknemer_id)", $factuur_id );
		
		//reset telling
		$werknemer_bedrag_verkoop = 0;
		$werknemer_bedrag_kosten = 0;
		$bedrag_aangenomenwerk = NULL;
		
		//algemene inserts
		$insert['factuur_id'] = $factuur_id;
		
		if( $this->user->werkgever_type == 'uitzenden' )
			$insert['werknemer_id'] = $werknemer_id;
		if( $this->user->werkgever_type == 'bemiddeling' )
			$insert['zzp_id'] = $werknemer_id;
		
		//eerste regel met alleen de naam
		$insert['row_start'] = 1;
		$insert['omschrijving'] = $werknemer_id . ' - ' . $this->_inlener_werknemers[$werknemer_id]['naam'];
		
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
					$insert['uitkeren_werknemer'] = 1;
					$insert['invoer_ids'] = json_encode( $urengroep['invoer_ids'] );
					
					if( $this->user->werkgever_type == 'uitzenden' )
						$insert['percentage'] = $urengroep['percentage'];
					if( $this->user->werkgever_type == 'bemiddeling' )
						$insert['percentage'] = 100;
					
					if( $this->user->werkgever_type == 'uitzenden' )
						$insert['bruto_uurloon'] = $urengroep['bruto_loon'];
					if( $this->user->werkgever_type == 'bemiddeling' )
						$insert['bruto_uurloon'] = $urengroep['uurtarief'];
					
					if( $urengroep['doorbelasten_uitzender'] == 1 )
						$insert['doorbelasten_aan'] = 'uitzender';
					else
						$insert['doorbelasten_aan'] = 'inlener';
					
					//regeltotaal uitrekenen
					$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['bruto_uurloon'] * $insert['factor'] * ( $insert['percentage'] / 100 ) ), 2 );
					$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
					
					//wanneer verkoop gelijk aan kosten
					if( isset( $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] ) && $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] == 1 )
					{
						$insert['subtotaal_verkoop'] = $insert['subtotaal_kosten'];
						$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
					} //anders gewone afhandeling
					else
					{
						//wanneer doorbelasten naar uitzender, dan NIET op de verkoop factuur
						if( $urengroep['doorbelasten_uitzender'] != 1 )
						{
							$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * 1 * 1 ), 2 );
							$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
						} else
							$insert['subtotaal_verkoop'] = 0;
					}
					
					$this->db_user->insert( 'facturen_regels', $insert );
					
					//invoer juiste factuur ID meegeven, NIET VOOR VOORBEELD
					if( !$this->_preview )
						$this->_updateInvoerMetFactuurIDs( 'invoer_uren', $factuur_id, $urengroep['invoer_ids'] );
				}
			}
		}
		
		//bij bemiddeling marge regel aanmaken op kostenoverzicht
		if( $this->user->werkgever_type == 'bemiddeling' )
		{
			if( isset( $werknemer_array['urengroep']['inlener']['marge'] ) && is_array( $werknemer_array['urengroep']['inlener']['marge'] ) )
			{
				foreach( $werknemer_array['urengroep']['inlener']['marge'] as $marge => $aantal )
				{
					$insert['row_start'] = NULL;
					$insert['omschrijving'] = 'bemiddelingskosten';
					$insert['bemiddelingskosten'] = 1;
					$insert['uren_aantal'] = NULL;
					$insert['uren_decimaal'] = $aantal;
					$insert['verkooptarief'] = NULL;
					$insert['bruto_uurloon'] = $marge;
					$insert['factor'] = 1;
					$insert['percentage'] = 100;
					$insert['uitkeren_werknemer'] = 0;
					$insert['invoer_ids'] = NULL;
					$insert['doorbelasten_aan'] = 'uitzender';
					$insert['subtotaal_kosten'] = round( ( $aantal * $marge ), 2 );
					$insert['subtotaal_verkoop'] = 0;
					
					$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
					
					$this->db_user->insert( 'facturen_regels', $insert );
				}
			}
		}
		
		//dan de kilometers
		if( isset( $werknemer_array['kmgroep'] ) && is_array( $werknemer_array['kmgroep'] ) && count( $werknemer_array['kmgroep'] ) > 0 )
		{
			foreach( $werknemer_array['kmgroep'] as $doorbelasten => $uitkerenGroep )
			{
				foreach( $uitkerenGroep as $uitkeren => $kmgroep )
				{
					$insert['row_start'] = NULL;
					$insert['omschrijving'] = 'kilometergeld';
					$insert['uren_aantal'] = NULL;
					$insert['uren_decimaal'] = $kmgroep['totaal_km'];
					$insert['verkooptarief'] = 0.19;
					$insert['factor'] = 1;
					$insert['bruto_uurloon'] = 0.19;
					$insert['percentage'] = 100;
					$insert['uitkeren_werknemer'] = $uitkeren;
					$insert['invoer_ids'] = json_encode( $kmgroep['invoer_ids'] );
					$insert['doorbelasten_aan'] = $kmgroep['doorbelasten'];
					
					//regeltotaal uitrekenen
					if( $uitkeren == 1 )
						$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['bruto_uurloon'] * $insert['factor'] * ( $insert['percentage'] / 100 ) ), 2 );
					else
						$insert['subtotaal_kosten'] = 0;
					
					$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
					
					//wanneer verkoop gelijk aan kosten
					if( isset( $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] ) && $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] == 1 )
					{
						$insert['subtotaal_verkoop'] = $insert['subtotaal_kosten'];
						$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
					} //anders gewone afhandeling
					else
					{
						//wanneer doorbelasten naar uitzender, dan NIET op de verkoop factuur
						if( $insert['doorbelasten_aan'] == 'inlener' )
						{
							$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * 1 * 1 ), 2 );
							$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
						} else
							$insert['subtotaal_verkoop'] = 0;
					}
					
					$this->db_user->insert( 'facturen_regels', $insert );
					
					//invoer juiste factuur ID meegeven
					if( !$this->_preview )
						$this->_updateInvoerMetFactuurIDs( 'invoer_kilometers', $factuur_id, $kmgroep['invoer_ids'] );
				}
			}
		}
		
		//dan de vergoedingen
		if( isset( $werknemer_array['vergoedingengroep'] ) && is_array( $werknemer_array['vergoedingengroep'] ) && count( $werknemer_array['vergoedingengroep'] ) > 0 )
		{
			foreach( $werknemer_array['vergoedingengroep'] as $doorbelasten => $vergoedingenarray )
			{
				foreach( $vergoedingenarray as $invoer_id => $vergoedingengroep )
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
					
					//wanneer verkoop gelijk aan kosten
					if( isset( $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] ) && $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] == 1 )
					{
						$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * $insert['factor'] * 1 ), 2 );
						$insert['subtotaal_verkoop'] = $insert['subtotaal_kosten'];
						$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
						$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
					} //anders gewone afhandeling
					else
					{
						//niet uitkeren aan werknemer = geen kosten gemaakt, kan alleen als het doorbelasten aan inlener is
						if( $insert['uitkeren_werknemer'] == 0 )
						{
							if( $insert['doorbelasten_aan'] == 'inlener' )
							{
								$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * $insert['factor'] * 1 ), 2 );
								$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
								$insert['subtotaal_kosten'] = 0;
							}
						} //wannneer uitkeren aan werknemer, altijd kosten maken
						else
						{
							$insert['subtotaal_kosten'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * $insert['factor'] * 1 ), 2 );
							$insert['subtotaal_verkoop'] = 0; //eerst op 0, wanneer doorbelasten aan inlener wordt deze overschreven
							$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
							
							//wanneer doorbelasten naar inlener, dan ook verkoop aanmaken
							if( $insert['doorbelasten_aan'] == 'inlener' )
							{
								$insert['subtotaal_verkoop'] = round( ( $insert['uren_decimaal'] * $insert['verkooptarief'] * $insert['factor'] * 1 ), 2 );
								$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
							}
						}
					}
					
					$this->db_user->insert( 'facturen_regels', $insert );
					
					$vergoedingen_ids[$vergoedingengroep['invoer_id']] = 1;
				}

				//invoer juiste factuur ID meegeven
				if( !$this->_preview )
					$this->_updateInvoerMetFactuurIDs( 'invoer_vergoedingen', $factuur_id, $vergoedingen_ids );
			}
		}
		
		//pensioen, alleen bij uitzenden
		if( $this->user->werkgever_type == 'uitzenden' )
		{
			if( isset( $this->_werknemers_pensioen[$werknemer_id] ) && ( $this->_werknemers_pensioen[$werknemer_id]['stipp'] == 'basis' || $this->_werknemers_pensioen[$werknemer_id]['stipp'] == 'plus' ) )
			{
				
				if( isset( $werknemer_array['urengroep']['inlener']['bruto_loon'] ) && $werknemer_array['urengroep']['inlener']['bruto_loon'] > 0 )
				{
					//bruto loon
					$salaris = $werknemer_array['urengroep']['inlener']['bruto_loon'];
					$salaris = $salaris * 1.25; // reserveringen erbij
					
					//STIPP Basis
					if( $this->_werknemers_pensioen[$werknemer_id]['stipp'] == 'basis' )
					{
						$pensioenpremie = round( ( $salaris * ( $this->_stipp_basis / 100 ) ), 2 );
						$omschrijving = 'STiPP pensioen Basis';
					}
					
					//STIPP Plus
					if( $this->_werknemers_pensioen[$werknemer_id]['stipp'] == 'plus' )
					{
						$pensioenpremie = round( ( $salaris * ( 9 / 100 ) ), 2 );
						$omschrijving = 'STiPP pensioen Plus';
					}
					
					$insert['row_start'] = NULL;
					$insert['omschrijving'] = $omschrijving;
					$insert['uren_aantal'] = NULL;
					$insert['uren_decimaal'] = NULL;
					$insert['verkooptarief'] = NULL;
					$insert['factor'] = 1;
					$insert['bruto_uurloon'] = $pensioenpremie;
					$insert['percentage'] = 100;
					$insert['uitkeren_werknemer'] = 1;
					$insert['invoer_ids'] = NULL;
					$insert['doorbelasten_aan'] = 'uitzender';
					
					$insert['subtotaal_kosten'] = $pensioenpremie;
					
					//wanneer verkoop gelijk aan kosten
					if( isset( $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] ) && $this->_inlener_factuurgegevens['verkoop_kosten_gelijk'] == 1 )
						$insert['subtotaal_verkoop'] = $pensioenpremie;
					else
						$insert['subtotaal_verkoop'] = 0;
					
					$werknemer_bedrag_kosten += $insert['subtotaal_kosten'];
					$werknemer_bedrag_verkoop += $insert['subtotaal_verkoop'];
					
					$this->db_user->insert( 'facturen_regels', $insert );
				}
			}
		}
		
		//laatste regel
		unset( $insert );
		
		//algemene inserts
		$insert['factuur_id'] = $factuur_id;
		
		if( $this->user->werkgever_type == 'uitzenden' )
			$insert['werknemer_id'] = $werknemer_id;
		if( $this->user->werkgever_type == 'bemiddeling' )
			$insert['zzp_id'] = $werknemer_id;
		
		$insert['row_end'] = 1;
		$insert['subtotaal_verkoop'] = $werknemer_bedrag_verkoop;
		$insert['subtotaal_kosten'] = $werknemer_bedrag_kosten;
		
		$this->db_user->insert( 'facturen_regels', $insert );
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * moeten er nog correcties aan de fatcuur worden toegevoegd?
	 *
	 */
	private function _insertCorrecties( $factuur_id ): void
	{
		$correcties = $this->_getCorrecties();
		
		if( $correcties === NULL )
			return;
		
		//log action
		$this->_sessieLog( 'action', "correcties toevoegen" );
		
		//algemene inserts
		$insert['factuur_id'] = $factuur_id;
		
		//eerste regel met alleen de naam
		$insert['row_start'] = 1;
		$insert['row_correctie'] = 1;
		$insert['omschrijving'] = 'Overige kosten';
		
		//insert 1e regel
		$this->db_user->insert( 'facturen_regels', $insert );
		
		$totaal_correcties = 0;
		
		foreach( $correcties as $correctie )
		{
			$insert['row_start'] = NULL;
			$insert['row_correctie'] = 1;
			$insert['omschrijving'] = $correctie['omschrijving'];
			$insert['uren_aantal'] = NULL;
			$insert['uren_decimaal'] = NULL;
			$insert['verkooptarief'] = $correctie['bedrag'];
			$insert['factor'] = 1;
			$insert['bruto_uurloon'] = 0;
			$insert['percentage'] = 100;
			$insert['uitkeren_werknemer'] = 0;
			$insert['invoer_ids'] = $correctie['id'];
			$insert['doorbelasten_aan'] = 'inlener';
			
			$insert['subtotaal_kosten'] = 0;
			$insert['subtotaal_verkoop'] = $correctie['bedrag'];
			
			$this->db_user->insert( 'facturen_regels', $insert );
			
			$totaal_correcties += $correctie['bedrag'];
			
			//invoer juiste factuur ID meegeven
			if( !$this->_preview )
				$this->_updateCorrectiesMetFactuurIDs( $factuur_id, array( $correctie['id'] => 1 ) );
		}
		
		unset( $insert );
		
		//optelling
		$insert['factuur_id'] = $factuur_id;
		$insert['row_end'] = 1;
		$insert['row_correctie'] = 1;
		$insert['subtotaal_verkoop'] = $totaal_correcties;
		$insert['subtotaal_kosten'] = 0;
		
		$this->db_user->insert( 'facturen_regels', $insert );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * kijker of er correcties zijn
	 *
	 */
	private function _getCorrecties(): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM facturen_correcties WHERE deleted = 0 AND factuur_id IS NULL AND inlener_id = $this->_inlener_id AND uitzender_id = $this->_uitzender_id" );
		return DBhelper::toArray( $query, 'id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * invoer updaten met factuur ID
	 *
	 */
	private function _updateInvoerMetFactuurIDs( $table, $factuur_id, $invoer_ids ): void
	{
		$this->db_user->query( "UPDATE $table SET factuur_id = $factuur_id WHERE invoer_id IN (" . array_keys_to_string( $invoer_ids ) . ") " );
	}
	
	private function _updateCorrectiesMetFactuurIDs( $factuur_id, $invoer_ids ): void
	{
		$this->db_user->query( "UPDATE facturen_correcties SET factuur_id = $factuur_id WHERE id IN (" . array_keys_to_string( $invoer_ids ) . ") " );
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
		$insert['user_id'] = $this->user->user_id;
		
		//Marge altijd met BTW, marge is negatief
		$insert['bedrag_excl'] = -( $factuur['bedrag_excl'] - $factuur['kosten_excl'] );
		$insert['bedrag_btw'] = round( ( $insert['bedrag_excl'] * 0.21 ), 2 );
		$insert['bedrag_incl'] = $insert['bedrag_excl'] + $insert['bedrag_btw'];
		$insert['bedrag_openstaand'] = abs( $insert['bedrag_incl'] );
		
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
		$insert['afgesprokenwerk'] = $this->_inlener_factuurgegevens['afgesproken_werk'];
		$insert['tijdvak'] = $this->_tijdvak;
		$insert['jaar'] = $this->_jaar;
		$insert['periode'] = $this->_periode;
		$insert['project_id'] = $project_id;
		$insert['uitzender_id'] = $this->_uitzender_id;
		$insert['inlener_id'] = $this->_inlener_id;
		$insert['tarief_btw'] = $this->_setting_btw_tarief;
		$insert['eu_levering'] = $this->_setting_eu_levering;
		$insert['factuur_datum'] = date( 'Y-m-d' );
		$insert['verval_datum'] = date( 'Y-m-d', strtotime( ' +' . $this->_setting_termijn . ' days' ) );
		$insert['user_id'] = $this->user->user_id;
		
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
						//bij uitzenden bruto loon en factor checken
						if( $this->user->werkgever_type == 'uitzenden' )
						{
							if( $urengroep['bruto_loon'] === NULL || $urengroep['bruto_loon'] == '' || $urengroep['bruto_loon'] == 0 )
							{
								$this->_error[] = 'Ongeldig bruto uurloon voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
								$this->_sessieLog( 'error', "ongeldig uurloon bij $werknemer_id" );
								$bedragen_error = true;
							}
							
							if( $urengroep['factor'] === NULL || $urengroep['factor'] == '' || $urengroep['factor'] < 1.35 )
							{
								$this->_error[] = 'Ongeldige factor voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
								$this->_sessieLog( 'error', "ongeldige factor bij $werknemer_id" );
								$bedragen_error = true;
							}
						}
						
						//bij bemiddeling uurtarief en marge checken
						if( $this->user->werkgever_type == 'bemiddeling' )
						{
							if( $urengroep['uurtarief'] === NULL || $urengroep['uurtarief'] == '' || $urengroep['uurtarief'] == 0 )
							{
								$this->_error[] = 'Ongeldig uurtarief voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
								$this->_sessieLog( 'error', "ongeldig uurloon bij $werknemer_id" );
								$bedragen_error = true;
							}
							
							if( $urengroep['marge'] === NULL || $urengroep['marge'] == '' || $urengroep['marge'] < 0 )
							{
								$this->_error[] = 'Ongeldige marge voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
								$this->_sessieLog( 'error', "ongeldige factor bij $werknemer_id" );
								$bedragen_error = true;
							}
						}
						
						//altijd verkooptarief
						if( $urengroep['verkooptarief'] === NULL || $urengroep['verkooptarief'] == '' || $urengroep['verkooptarief'] == 0 )
						{
							$this->_error[] = 'Ongeldig verkooptarief voor ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') - [' . $categorie . ']';
							$this->_sessieLog( 'error', "ongeldig verkooptarief bij $werknemer_id" );
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
		
		//bijlages checken
		$bijlages = $this->invoer->getBijlages();
		if( $bijlages !== NULL && is_array( $bijlages ) && count( $bijlages ) > 0 )
		{
			foreach( $bijlages as $bijlage )
			{
				if( $bijlage['project_id'] === NULL )
					$this->_error[] = 'Er zijn bijlages gevonden die niet onder een project vallen';
				
			}
		}
		
		//aangenomenwerk checken
		if( $this->_inlener_factuurgegevens['afgesproken_werk'] == 1 )
		{
			if( $this->_setting_split_project == 1 )
			{
				$invoeraangenomenwerk = new InvoerAangenomenwerk( $this->invoer );
				$projecten = $invoeraangenomenwerk->getDataForProjecten( $this->invoer->getActieveProjecten() );
				
				foreach( $projecten as $project )
				{
					if( count( $project['rijen'] ) == 0 )
						$this->_error[] = 'Er zijn aangenomenwerk projecten zonder omschrijving en bedrag (' . $project['project'] . ')';
					else
					{
						foreach( $project['rijen'] as $rij )
						{
							if( $rij['omschrijving'] === NULL || strlen( $rij['omschrijving'] ) == 0 )
								$this->_error[] = 'Er zijn aangenomenwerk projecten zonder omschrijving (' . $project['project'] . ')';
							
							if( $rij['bedrag'] === NULL || strlen( $rij['bedrag'] ) == 0 || $rij['bedrag'] <= 0 )
								$this->_error[] = 'Er zijn aangenomenwerk projecten met een ongeldig bedrag (' . $project['project'] . ')';
						}
					}
				}
			}
			
		}
		
		//als alles bij een project zit, geen fouten
		if( !isset( $this->_group_array[NULL] ) && $this->_error === NULL )
			return true;
		
		//foutmelding
		if( isset( $this->_group_array[NULL] ) )
		{
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
		}
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Controleren of er niet meer gewone uren ingevoerd zijn dan mag
	 * TODO: checken voor uren bij meerdere inleners
	 * TODO: m en 4w aanpassen
	 */
	private function _urenWerkweek(): bool
	{
		$totaaltelling_error = false;
		
		//korte check
		foreach( $this->_group_array as $project_array )
		{
			foreach( $project_array as $werknemer_id => $werknemer_array )
			{
				//uren werkweek ophalen
				if( !isset( $this->_inlener_werknemers[$werknemer_id]['uren_werkweek'] ) || $this->_inlener_werknemers[$werknemer_id]['uren_werkweek'] == 0 )
					$uren_werkweek = $this->_setting_uren_werkweek;
				else
					$uren_werkweek = $this->_inlener_werknemers[$werknemer_id]['uren_werkweek'];
				
				$uren_werkweek = 48;
				
				if( $this->user->werkgever_type == 'bemiddeling' )
					$uren_werkweek = 80;
				
				if( $this->_tijdvak == '4w' )
					$uren_werkweek = $uren_werkweek * 4;
				
				if(isset($werknemer_array['urengroep']))
				{
					if( $werknemer_array['urengroep']['inlener']['uren_totaal'] > $uren_werkweek )
					{
						$totaaltelling_error = true;
						$this->_error[] = 'Voor werknemer ' . $this->_inlener_werknemers[$werknemer_id]['naam'] . ' (' . $werknemer_id . ') zijn te veel standaard uren ingevoerd (ingevoerd: ' . $werknemer_array['urengroep']['inlener']['uren_totaal'] . ' maximaal: ' . $uren_werkweek . ' uur ). Overuren zijn mogelijk van toepassing.';
					}
				}
			}
		}
		
		if( !$totaaltelling_error )
			return true;
		else
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
		
		//door de vergoedingen lopen
		foreach( $this->_invoer_array['vergoedingen'] as $werknemer_id => $rows )
		{
			foreach( $rows as $invoer_id => $row )
			{
				//juiste projec ID, bij splitsen
				$project_id = ( $this->_setting_split_project == 0 ) ? NULL : $row['project_id'];
				
				//rij toevoegen, 1 op 1 overnemen
				$this->_group_array[$project_id][$werknemer_id]['vergoedingengroep'][$row['doorbelasten']][$row['invoer_id']] = $row;
				
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
				
				//niet uitkeren kan alleen bij doorbelasten aan inlener
				$uitkeren = 1;
				if( $row['doorbelasten'] == 'inlener' && $row['uitkeren'] == 0 )
					$uitkeren = 0;
				
				//init wanneer nodig
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']][$uitkeren] ) )
					$this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']][$uitkeren] = array(
						'totaal_km' => 0,
						'factor' => 1,
						'doorbelasten' => $row['doorbelasten'],
						'invoer_ids' => array()
					);
				
				//rij optellen bij totaal en ID naar array
				$this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']][$uitkeren]['totaal_km'] += $row['aantal'];
				$this->_group_array[$project_id][$werknemer_id]['kmgroep'][$row['doorbelasten']][$uitkeren]['invoer_ids'][$row['invoer_id']] = 1;
				
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * regels op de factuur groeperen en optellen
	 * regels project
	 *
	 */
	private function _groupAangenomenwerkInvoer(): bool
	{
		$invoeraangenomenwerk = new InvoerAangenomenwerk( $this->invoer );
		
		$aangenomenwerk_regels = $invoeraangenomenwerk->getAangenomenwerkRijen();
		
		if( $aangenomenwerk_regels === NULL )
			return false;
		
		foreach( $aangenomenwerk_regels as $regel )
		{
			$project_id = $regel['project_id'];
			$this->_aangenomenwerk_array[$project_id]['regels'][] = $regel;
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
				{
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['uren_totaal'] = 0;
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['bruto_loon'] = 0;
				}
				
				//voor bemiddeling marge telling bijhouden, altijd naar uitzender doorbelasten maar bij inlener array parkeren
				if( $this->user->werkgever_type == 'bemiddeling' )
				{
					if( !isset( $this->_group_array[$project_id][$werknemer_id]['urengroep']['inlener']['marge'][$row['marge']] ) )
						$this->_group_array[$project_id][$werknemer_id]['urengroep']['inlener']['marge'][$row['marge']] = 0;
					
					$this->_group_array[$project_id][$werknemer_id]['urengroep']['inlener']['marge'][$row['marge']] += $row['aantal'];
				}
				
				//init wanneer nodig
				if( !isset( $this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key] ) )
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['urentypes'][$key] = array(
						'totaal_uren' => 0,
						'bruto_loon' => $row['bruto_loon'],
						'uurtarief' => $row['uurtarief'],
						'verkooptarief' => $row['verkooptarief'],
						'marge' => $row['marge'],
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
				{
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['uren_totaal'] += $row['aantal'];
					$this->_group_array[$project_id][$werknemer_id]['urengroep'][$doorbelasten]['bruto_loon'] += $row['aantal'] * $row['bruto_loon'];
				}
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
			
			//ureninvoer
			if( $this->user->werkgever_type == 'uitzenden' )
			{
				//werknemer ID instellen
				$invoerUren->setWerknemer( $werknemer_id );
				$invoerKm->setWerknemer( $werknemer_id );
				$invoervergoedingen->setWerknemer( $werknemer_id );
				
				//uren
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
			if( $this->user->werkgever_type == 'bemiddeling' )
			{
				//werknemer ID instellen
				$invoerUren->setZzp( $werknemer_id );
				$invoerKm->setZzp( $werknemer_id );
				$invoervergoedingen->setZzp( $werknemer_id );
				
				//uren
				if( NULL !== $uren = $invoerUren->getZzpUrenRijen() )
					$this->_invoer_array['uren'][$werknemer_id] = $uren;
				
				//km
				if( NULL !== $km = $invoerKm->getZzpKilometerRijen() )
					$this->_invoer_array['km'][$werknemer_id] = $km;
				
				//vergoedingen
				if( $vergoedingen = $invoervergoedingen->getWerknemerVergoedingenRijen() )
				{
					if( count( $vergoedingen ) !== 0 )
						$this->_invoer_array['vergoedingen'][$werknemer_id] = $vergoedingen;
				}
				
			}
			
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
		
		foreach( $werknemers as $array )
		{
			$this->_inlener_werknemers[$array['id']]['naam'] = $array['naam'];
			$this->_inlener_werknemers[$array['id']]['uren_werkweek'] = $array['uren_werkweek'];
		}
		
		//log actie
		$this->_sessieLog( 'load', json_encode( $this->_inlener_werknemers ) );
		
		//pensioen status ophalen
		$werknemerGroup = new WerknemerGroup();
		$this->_werknemers_pensioen = $werknemerGroup->setWerknemers( $this->_inlener_werknemers )->pensioen();
		
		//log actie
		$this->_sessieLog( 'load', json_encode( $this->_werknemers_pensioen ) );
		
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
		
		//uitzender facturatiegegevens
		$uitzender = new Uitzender( $this->_uitzender_id );
		$this->_uitzender_bedrijfsgegevens = $uitzender->bedrijfsgegevens();
		
		//uitzender korting
		$this->_uitzender_korting = $uitzender->factuurKorting();
		$this->_uitzender_systeeminstellingen = $uitzender->systeeminstellingen();
		
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
			$this->_inlener_projecten = $inlener->projecten();
			$this->_sessieLog( 'setting', "split_project: 1" );
		}
		
		if( $this->_inlener_factuurgegevens['btw_verleggen'] == 1 )
		{
			$this->_setting_btw_verleggen = 1;
			$this->_sessieLog( 'setting', "btw_verleggen: 1" );
		}
		
		if( $this->_inlener_factuurgegevens['btw_verleggen'] == 0 )
		{
			$this->_setting_btw_tarief = $this->_inlener_factuurgegevens['btw_tarief'];
			$this->_sessieLog( 'setting', "btw_tarief: " . $this->_setting_btw_tarief );
		}
		
		if( $this->_inlener_factuurgegevens['eu_levering'] == 1 )
		{
			//eu levering aan
			$this->_setting_eu_levering = 1;
			$this->_sessieLog( 'setting', "eu_levering: 1" );
			
			//altijd 0%
			$this->_setting_btw_tarief = 0;
			$this->_sessieLog( 'setting', "btw_tarief: 0");
			
			//geen btw verleggen
			$this->_setting_btw_verleggen = 0;
			$this->_sessieLog( 'setting', "btw_verleggen: 0" );
		}
		
		
		if( $this->_inlener_factuurgegevens['g_rekening'] == 1 && $this->user->werkgever_type == 'uitzenden' )
		{
			$this->_setting_g_rekening = 1;
			$this->_setting_g_rekening_percentage = $this->_inlener_factuurgegevens['g_rekening_percentage'];
			$this->_sessieLog( 'setting', "g_rekening: 1, g_rekening_percentage: " . $this->_inlener_factuurgegevens['g_rekening_percentage'] );
		}
		
		if( $this->user->werkgever_type == 'bemiddeling' )
		{
			$this->_setting_g_rekening = 0;
			$this->_setting_g_rekening_percentage = 0;
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
			die( 'GEEN TOEGANG VOOR DEZE FUNCTIE' );
		
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
		$db_user->query( "TRUNCATE facturen_kostenoverzicht" );
		$db_user->query( "SET FOREIGN_KEY_CHECKS = 1" );
		
		$db_user->query( "UPDATE invoer_uren SET factuur_id = NULL" );
		$db_user->query( "UPDATE invoer_kilometers SET factuur_id = NULL" );
		$db_user->query( "UPDATE invoer_vergoedingen SET factuur_id = NULL" );
		
		//delete facturen
		$files = scandir( 'userf1les_o7dm6/werkgever_dir_1/facturen/2020' );
		foreach( $files as $file )
		{
			$path = 'userf1les_o7dm6/werkgever_dir_1/facturen/2020/' . $file;
			if( file_exists( $path ) && !is_dir( $path ) )
				unlink( $path );
		}
		
		//delete facturen
		$files = scandir( 'userf1les_o7dm6/werkgever_dir_2/facturen/2020' );
		foreach( $files as $file )
		{
			$path = 'userf1les_o7dm6/werkgever_dir_2/facturen/2020/' . $file;
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