<?php

namespace models\facturatie;

use models\Connector;
use models\email\Email;
use models\file\Pdf;
use models\forms\Validator;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurDefault;
use models\pdf\PdfFactuurUren;
use models\uitzenders\Uitzender;
use models\users\UserGroup;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\PlaatsingGroup;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Factuur extends Connector
{
	protected $_factuur_id = NULL;
	protected $_factuur_nr = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_uitzender_id = NULL;
	protected $_inlener_id = NULL;
	protected $_werknemer_id = NULL;
	protected $_zzp_id = NULL;
	
	protected $_periode_start = NULL;
	protected $_periode_einde = NULL;
	protected $_periode_dagen = NULL;
	
	protected $_betaald_vrij = 0;
	protected $_betaald_g = 0;
	
	protected $_file_name = NULL;
	protected $_file_dir = NULL;
	
	protected $_kosten = false;
	
	protected $_error = NULL;
	private $_betaling_id = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct( $factuur_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $factuur_id !== NULL )
			$this->setFactuurID( $factuur_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur ID
	 *
	 */
	public function setFactuurID( $factuur_id )
	{
		$this->_factuur_id = intval( $factuur_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur ID
	 *
	 */
	public function factuurID()
	{
		return $this->_factuur_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * ID van de bijbehorende marge factuur
	 *
	 */
	public function getMargeFactuurID()
	{
		$query = $this->db_user->query( "SELECT factuur_id FROM facturen WHERE parent_id = $this->_factuur_id AND deleted = 0 LIMIT 1" );
		$factuur = DBhelper::toRow( $query, 'NULL' );
		
		if( $factuur === NULL )
			return NULL;
		
		return $factuur['factuur_id'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bijlages uit de invoer
	 *
	 */
	public function getInvoerBijlages()
	{
		$sql = "SELECT * FROM invoer_bijlages WHERE factuur_id = $this->_factuur_id AND deleted = 0 ORDER BY file_name_display";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['icon'] = get_file_icon( $row['file_ext'] );
			$row['file_size'] = size( $row['file_size'] );
			
			$data[] = $row;
		}
		
		$data = UserGroup::findUserNames( $data );
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bijlages later toegevoegd
	 *
	 */
	public function getExtraBijlages()
	{
		$sql = "SELECT * FROM facturen_bijlages WHERE factuur_id = $this->_factuur_id AND deleted = 0 ORDER BY file_name_display";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['icon'] = get_file_icon( $row['file_ext'] );
			$row['file_size'] = size( $row['file_size'] );
			
			$data[] = $row;
		}
		
		$data = UserGroup::findUserNames( $data );
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Haal alle bijlages op
	 * @return ?array
	 */
	public function getBijlageByID( ?int $file_id ): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM facturen_bijlages WHERE file_id = ? AND deleted = 0 LIMIT 1", array( $file_id ) );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * geuploade bijlage naar de database
	 *
	 */
	public function addBijlage( array $file_info ): bool
	{
		$insert = $file_info;
		
		$insert['factuur_id'] = $this->_factuur_id;
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'facturen_bijlages', $insert );
		
		$bijlage_id = $this->db_user->insert_id();
		if( $bijlage_id > 0 )
		{
			
			//factuur als pdf
			$pdf = new Pdf( array( 'file_dir' => $this->filedir(), 'file_name' => $this->filename() ) );
			
			if( !$pdf->addFileToPdf( $file_info['file_dir'], $file_info['file_name'] ) )
			{
				$this->_error[] = 'PDF koppelen mislukt';
				return false;
			}
			
			$this->_log( 'Bijlage toegevoegd', '{bijlage_id:' . $bijlage_id . '}' );
			
			return true;
		}
		
		$this->_error[] = 'Wegschrijven naar database is mislukt';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * filed name
	 *
	 */
	public function filename()
	{
		if( $this->_file_name === NULL )
			$this->details();
		
		return $this->_file_name;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * file dir
	 *
	 */
	public function filedir()
	{
		if( $this->_file_dir === NULL )
			$this->details();
		
		return $this->_file_dir;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur nr ophalen
	 *
	 */
	public function nr()
	{
		$query = $this->db_user->query( "SELECT factuur_nr FROM facturen WHERE factuur_id = $this->_factuur_id LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL', 'factuur_nr' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur nr
	 *
	 */
	public function setFactuurNr( $factuur_nr )
	{
		$this->_factuur_nr = intval( $factuur_nr );
		
		$query = $this->db_user->query( "SELECT factuur_id FROM facturen WHERE factuur_nr = $this->_factuur_nr LIMIT 1" );
		$factuur = DBhelper::toRow( $query, 'NULL' );
		
		if( $factuur === NULL )
			return NULL;
		
		$this->setFactuurID( $factuur['factuur_id'] );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * details
	 *
	 */
	public function details()
	{
		$sql = "SELECT facturen.*,DATEDIFF(voldaan_op,factuur_datum) AS opengestaan, DATEDIFF(verval_datum,factuur_datum) AS betaaltermijn, DATEDIFF(NOW(),facturen.verval_datum) AS verval_dagen, inleners_projecten.omschrijving AS project_label, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = facturen.inlener_id
				LEFT JOIN inleners_projecten ON inleners_projecten.id = facturen.project_id
				WHERE facturen.factuur_id = $this->_factuur_id AND facturen.deleted = 0 AND facturen.concept = 0 AND inleners_bedrijfsgegevens.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		$details = DBhelper::toRow( $query, 'NULL' );
		
		if( $details !== NULL )
			$details['bedrag_vrij'] = $details['bedrag_incl'] - $details['bedrag_grekening'];
		
		$details = UserGroup::findUserNames( $details );
		
		$this->_file_name = $details['file_name'];
		$this->_file_dir = $details['file_dir'];
		
		return $details;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * status van wachtrij aanpassen
	 *
	 */
	public function setWachtrijstatus( $status = NULL ): bool
	{
		if( $status == 'ready' )
			$val = 1;
		elseif( $status == 'wait' )
			$val = 0;
		else
			die( 'Ongeldige waarde status wachtrij' );
		
		$this->db_user->query( "UPDATE facturen SET wachtrij_akkoord = ? WHERE factuur_id = ? LIMIT 1", array( $val, $this->_factuur_id ) );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			if( $status == 'ready' )
				$this->_log( 'Wachtrij: factuur klaar voor verzenden' );
			else
				$this->_log( 'Wachtrij: klaar voor verzenden geannuleerd' );
			
			return true;
		}
		
		$this->_log( 'Wachtrij: aanpassen status mislukt' );
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * project in de wachtrij updaten
	 *
	 */
	public function setWachtrijProject( $project = NULL ): ?array
	{
		//clean
		$update['wachtrij_project'] = substr( trim( $project ), 0, 255 );
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() == 1 )
		{
			$this->_log( 'Projectnummer aangepast', '{project:' . $update['wachtrij_project'] . '}' );
			$this->_addWachtrijProjectToPdf( $update['wachtrij_project'] );
		}
		
		$result['status'] = 'success';
		
		return $result;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * PDF aanpassen
	 *
	 */
	private function _addWachtrijProjectToPdf( $wachtrij_project = NULL ): bool
	{
		if( $wachtrij_project === NULL )
		{
			$query = $this->db_user->query( "SELECT wachtrij_project FROM facturen WHERE factuur_id = $this->_factuur_id LIMIT 1" );
			$data = $query->row_array();
			$wachtrij_project = $data['wachtrij_project'];
		}
		
		if( $wachtrij_project === NULL )
			return false;
		
		$pdf = new Pdf( $this->_file_dir . '/' . $this->_file_name );
		$pdf->addWachtrijProjectTofactuur( $wachtrij_project );
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * log factuur actie
	 *
	 */
	private function _log( $action = NULL, $info = NULL ): void
	{
		if( $action === NULL )
			return;
		
		$insert['factuur_id'] = $this->_factuur_id;
		$insert['action'] = $action;
		$insert['info'] = $info;
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'facturen_log', $insert );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * 
	 * check of factuur aankoop en eindafrekening heeft. Het verschil minus G is kosten
	 *
	 */
	public function checkFactoringComplete()
	{
		$query = $this->db_user->query( "SELECT facturen_betalingen.bedrag, facturen_betalingen.betaald_op, facturen_betalingen_categorien.factoring_aankoop, facturen_betalingen_categorien.factoring_eind
											FROM facturen_betalingen
											LEFT JOIN facturen_betalingen_categorien ON facturen_betalingen_categorien.categorie_id = facturen_betalingen.categorie_id
											WHERE facturen_betalingen.factuur_id = $this->_factuur_id AND facturen_betalingen.deleted = 0" );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$bedrag_factoring = 0;
		$aankoop = false;
		$eind = false;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['factoring_aankoop'] == 1 ) $aankoop = true;
			if( $row['factoring_eind'] == 1 )
			{
				$eind = true;
				$datum_eind = $row['betaald_op'];
			}
			
			$bedrag_factoring += $row['bedrag'];
		}
		
		//aankoop en eind zijn binnen
		if( $aankoop && $eind )
		{
			//betalingen laden
			$factuur = $this->details();
			
			$kosten_factoring = $factuur['bedrag_incl'] - $factuur['bedrag_grekening'] - $bedrag_factoring;
			
			$betaling = new FactuurBetaling();
			$betaling->bedrag( $kosten_factoring )->categorie( 4 )->datum( reverseDate($datum_eind) );
			
			$this->addBetaling( $betaling );
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betaling toevoegen
	 *
	 */
	public function addBetaling( FactuurBetaling $betaling )
	{
		//extra controle
		if( !$betaling->valid() )
		{
			$this->_error[] = 'Betaling is ongeldig';
			return false;
		}
		
		$insert = $betaling->getInsertArray();
		$insert['factuur_id'] = $this->_factuur_id;
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'facturen_betalingen', $insert );
		
		$this->_updateBedragenNaBetaling();
		
		$this->_log( 'Betaling toegevoegd', json_encode( $insert ) );
		
		$this->_betaling_id = $this->db_user->insert_id();
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betaling ID ophalen
	 *
	 */
	public function getBetalingID()
	{
		return $this->_betaling_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betaling verwijderen
	 *
	 */
	public function delBetaling( int $betaling_id )
	{
		$this->db_user->query( "UPDATE facturen_betalingen SET deleted = 1, deleted_on = NOW(),  deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND id = " . intval( $betaling_id ) . " LIMIT 1" );
		
		$this->_updateBedragenNaBetaling();
		
		$this->_log( 'Betaling verwijderd', '{betaling_id:' . $betaling_id . '}' );
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betalingen
	 *
	 */
	public function _updateBedragenNaBetaling()
	{
		$query = $this->db_user->query( "SELECT SUM(bedrag) AS betaald FROM facturen_betalingen WHERE factuur_id = $this->_factuur_id AND deleted = 0 ORDER BY betaald_op DESC" );
		$data = DBhelper::toRow( $query, 'NULL' );
		
		if( $data['betaald'] === NULL ) $data['betaald'] = 0;
		
		if( $data !== NULL )
			$this->db_user->query( "UPDATE facturen SET bedrag_openstaand = (ABS(bedrag_incl) - " . $data['betaald'] . ") WHERE factuur_id =  $this->_factuur_id LIMIT 1" );
		
		//factuur op voldaan indien van toepassing
		$query = $this->db_user->query( "SELECT betaald_op FROM facturen_betalingen WHERE factuur_id = $this->_factuur_id AND deleted = 0 ORDER BY betaald_op DESC LIMIT 1" );
		$data = DBhelper::toRow( $query, 'NULL' );
		
		$this->db_user->query( "UPDATE facturen SET voldaan = 1, voldaan_op = '" . $data['betaald_op'] . "' WHERE bedrag_openstaand = 0 AND factuur_id = $this->_factuur_id LIMIT 1" );
		$this->db_user->query( "UPDATE facturen SET voldaan = 0, voldaan_op = NULL WHERE bedrag_openstaand != 0 AND factuur_id = $this->_factuur_id LIMIT 1" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betalingen
	 *
	 */
	public function betalingen( $show_deleted = false )
	{
		$sql = "SELECT facturen_betalingen.*, facturen_betalingen_categorien.g_rekening
				FROM facturen_betalingen
				LEFT JOIN facturen_betalingen_categorien ON facturen_betalingen_categorien.categorie_id = facturen_betalingen.categorie_id
				WHERE facturen_betalingen.factuur_id = $this->_factuur_id";
		
		if( !$show_deleted )
			$sql .= " AND facturen_betalingen.deleted = 0 ";
		$sql .= " ORDER BY betaald_op DESC";
		
		$query = $this->db_user->query( $sql );
		
		$betalingen = DBhelper::toArray( $query, 'id', 'NULL' );
		$betalingen = UserGroup::findUserNames( $betalingen );
		
		//vrij en g optellen
		if( $betalingen === NULL )
			return $betalingen;
		
		foreach( $betalingen as $betaling )
		{
			if( $betaling['deleted'] == 0 )
			{
				if( $betaling['g_rekening'] == 1 )
					$this->_betaald_g += $betaling['bedrag'];
				else
					$this->_betaald_vrij += $betaling['bedrag'];
			}
		}
		
		return $betalingen;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betalingen totaal Vrij
	 *
	 */
	public function betaaldVrij()
	{
		return $this->_betaald_vrij;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * betalingen totaal G
	 *
	 */
	public function betaaldG()
	{
		return $this->_betaald_g;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * delete
	 * kosten en marge ook verwijderen
	 * invoer restten
	 * TODO beperkingen inbouwen (tijd user)
	 * TODO transaction van maken
	 */
	public function delete()
	{
		$this->db_user->query( "UPDATE facturen SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE (factuur_id = $this->_factuur_id OR parent_id = $this->_factuur_id) AND  deleted = 0", array( $this->user->user_id ) );
		$this->db_user->query( "UPDATE facturen_kostenoverzicht SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE factuur_id = $this->_factuur_id AND  deleted = 0", array( $this->user->user_id ) );
		$this->db_user->query( "UPDATE zzp_facturen SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE parent_id = $this->_factuur_id AND  deleted = 0", array( $this->user->user_id ) );
		$this->db_user->query( "UPDATE invoer_uren SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id" );
		$this->db_user->query( "UPDATE invoer_kilometers SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id" );
		$this->db_user->query( "UPDATE invoer_vergoedingen SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id" );
		$this->db_user->query( "UPDATE facturen_correcties SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * factuur naar inlener sturen
	 *
	 */
	public function email()
	{
		$factuur = $this->details();
		
		$email = new Email();
		
		$inlener = new Inlener( $factuur['inlener_id'] );
		$emailadressen = $inlener->emailadressen();
		$factuurgegevens = $inlener->factuurgegevens();
		
		$emailadressen['facturatie'] = ( $emailadressen['facturatie'] === NULL ) ? $emailadressen['standaard'] : $emailadressen['facturatie'];
		
		$to['email'] = $emailadressen['facturatie'];
		$to['name'] = $inlener->bedrijfsnaam;
		
		if( $to['email'] == '' )
			return false;
		
		$email->to( $to );
		
		//cc naar factris
		if( ENVIRONMENT != 'development' )
		{
			if( $factuurgegevens['factoring'] == 1 )
				$email->to( [ 'email' => 'facturen@factris.com', 'name' => 'Factris' ] );
		}
		
		$email->setSubject( 'Nieuwe factuur' );
		$email->setTitel( 'Nieuwe factuur voor ' . $inlener->bedrijfsnaam );
		$email->setAttechment( 'facturen', 'factuur_id', $factuur, 'factuur_' . $factuur['factuur_nr'] . '.pdf' );
		$email->setBody( 'Er staat een nieuwe factuur voor u klaar. U vind de factuur als bijlage bij de email en in uw portal.
						<br /><br />
						<table>
						<tr><th style="padding-right: 20px; text-align: right">Factuur nr</th><th style="text-align: right">Bedrag Incl BTW</th></tr>
						<tr><td style="padding-right: 20px; text-align: right">' . $factuur['factuur_nr'] . '</td><td style="text-align: right">€ ' . number_format( $factuur['bedrag_incl'], 2, ',', '.' ) . '</td></tr>
						</table>
						<br /><br />
						Met vriendelijke groet,<br />' . $this->werkgever->bedrijfsnaam() );
		$email->useHtmlTemplate( 'default' );
		$email->delay( 0 );
		
		$email->send();
		
		$this->setSend();
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set send
	 *
	 * @return bool
	 */
	public function setSend()
	{
		$update['send_on'] = date( 'Y-m-d H:i:s' );
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set uplaoded to factoring
	 *
	 * @return bool
	 */
	public function setUploaded()
	{
		$update['to_factoring_on'] = date( 'Y-m-d H:i:s' );
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uitzender ID
	 *
	 * @return void
	 */
	public function setUitzender( $uitzender_id )
	{
		$this->_uitzender_id = intval( $uitzender_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get uitzender ID
	 *
	 * @return int
	 */
	public function uitzender()
	{
		return $this->_uitzender_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * inlener ID
	 *
	 * @return void
	 */
	public function setInlener( $inlener_id )
	{
		$this->_inlener_id = intval( $inlener_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get inlener ID
	 *
	 * @return int
	 */
	public function inlener()
	{
		return $this->_inlener_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * werknemer ID
	 *
	 * @return void
	 */
	public function setWerknemer( $werknemer_id )
	{
		$this->_werknemer_id = intval( $werknemer_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * zzp ID
	 *
	 * @return void
	 */
	public function setZZP( $zzp_id )
	{
		$this->_zzp_id = intval( $zzp_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * details kosten ophalen
	 *
	 */
	public function kostendetails()
	{
		$query = $this->db_user->query( "SELECT * FROM facturen_kostenoverzicht WHERE factuur_id = $this->_factuur_id AND deleted = 0" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 *  set kosten klaar om te downloaden
	 *
	 */
	public function kosten(): Factuur
	{
		$this->_kosten = true;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf bekijken
	 *
	 */
	public function view()
	{
		if( $this->_kosten )
			$details = $this->kostendetails();
		else
			$details = $this->details();
		
		$pdf = new Pdf( $details );
		
		$filename = NULL;
		
		if( isset( $details['inlener_id'] ) && $details['inlener_id'] !== NULL )
			$filename = $details['jaar'] . '_' . $details['periode'] . '_' . Inlener::bedrijfsnaam( $details['inlener_id'] ) . '.pdf';
		
		$pdf->inline( $filename );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf downloaden
	 *
	 */
	public function download()
	{
		if( $this->_kosten )
			$details = $this->kostendetails();
		else
			$details = $this->details();
		
		$pdf = new Pdf( $details );
		$pdf->download();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 * @return array|boolean
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