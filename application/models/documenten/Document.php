<?php

namespace models\documenten;
use models\Connector;
use models\email\Email;
use models\file\Pdf;
use models\uitzenders\Uitzender;
use models\utils\DBhelper;
use models\werknemers\Werknemer;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Documenten maken
 *
 */
class Document extends Connector {

	protected $_error = NULL;
	protected $_werknemer_id = NULL;
	protected $_werknemer_info = array();
	protected $_zzp_id = NULL;
	protected $_zzp_info = array();
	protected $_inlener_id = NULL;
	protected $_inlener_info = array();
	protected $_uitzender_id = NULL;
	protected $_uitzender_info = array();
	protected $_entiteit_id = NULL;
	protected $_werkgever_info = array();
	
	protected $_html = '';
	
	protected $_document_id = '';
	
	protected $_data = NULL;
	protected $_handtekeningen = NULL;
	
	protected $pdf = NULL;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @return void
	 */
	public function __construct( $document_id = NULL )
	{
		parent::__construct();
		
		//default entiteit
		$this->setEntiteitID();
		
		//haal bestaand document op
		if( $document_id !== NULL )
			$this->setDocumentId( $document_id );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get by hash
	 *
	 */
	public function getByHash( $hash = '' )
	{
		$query = $this->db_user->query( "SELECT document_id FROM documenten WHERE document_hash = ? AND deleted = 0 LIMIT 1", [$hash] );
		
		if( $query->num_rows() == 0 )
			return false;
		
		$data = $query->row_array();
		
		$this->setDocumentId( $data['document_id'] );
		return true;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * in en uit archief
	 *
	 */
	public function delete()
	{
		$this->db_user->query( "UPDATE documenten SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE deleted = 0 AND werknemer_id = ? AND document_id = ?",
			array( $this->user->user_id, $this->_werknemer_id, $this->_document_id ) );
		
		if( $this->db_user->affected_rows() > 0 )
		{
			//oevrige velden meenemen
			$this->db_user->query( "UPDATE werknemers_inleners SET document_id = NULL WHERE document_id = $this->_document_id" );
			
			return true;
		}
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get by hash
	 *
	 */
	public function emailSignLink()
	{
		if( $this->_data['document_hash'] === NULL )
			$this->_setHash();
		
		//reload
		$this->_load();
		
		$werknemer = new Werknemer($this->_data['werknemer_id']);
		$gegevens = $werknemer->gegevens();
		
		if( ENVIRONMENT == 'development')
			$link = 'http://192.168.1.2/app/sign/document?wid='.$this->werkgever->wid().'&wg_hash='.$this->werkgever->hash().'&werknemer='.$this->_data['werknemer_id'].'&document=' . $this->_data['document_hash'];
		else
			$link = 'https://www.devisonline.nl/sign/document?wid='.$this->werkgever->wid().'&wg_hash='.$this->werkgever->hash().'&werknemer='.$this->_data['werknemer_id'].'&document=' . $this->_data['document_hash'];
		
		$email = new Email();
		
		$to['email'] = $gegevens['email'];
		$to['name'] = $gegevens['voornaam'] . ' ' . $gegevens['tussenvoegsel'] . ' ' . $gegevens['achternaam'];
		
		if( $to['email'] == '' )
			return false;
		
		$email->to( $to );
		$email->setSubject('U heeft een document ontvangen voor ondertekening');
		$email->setTitel('Document voor ondertekening');
		$email->setBody('Er staat voor u een document klaar om te ondertekenen. Klik om onderstaande link om het document te bekijken en te tekenen.
						<br /><br />
						<a href="'.$link.'">
						'.$link.'</a>
						<br /><br />
						Mocht u problemen ondervinden, neem dan contact met ons op.
						Met vriendelijke groet,<br />Abering HR Services');
		$email->useHtmlTemplate( 'devis' );
		$email->delay( 0 );
		
		$email->send();
		
		$update['send'] = 1;
		$update['send_on'] = date( 'Y-m-d H:i:s' );
		$update['send_by'] = $this->user->user_id;
		
		$this->db_user->where( 'document_id', $this->_document_id );
		$this->db_user->update( 'documenten', $update );
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * sey hash
	 *
	 */
	private function _setHash()
	{
		$update['document_hash']  = md5( time() . $this->_data['document_id'] . $this->_data['file_name'] );
		$this->db_user->where( 'document_id', $this->_document_id );
		$this->db_user->update( 'documenten', $update );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Delete document
	 * TODO: moet naar deleted = 1
	 * @return object
	 */
	public function del()
	{
		$this->db_user->query( "DELETE FROM documenten WHERE document_id = $this->_document_id" );
		$this->db_user->query( "DELETE FROM documenten_handtekeningen WHERE document_id = $this->_document_id" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set document ID
	 * @return object
	 */
	public function setDocumentId( $document_id )
	{
		$this->_document_id = intval( $document_id );
		$this->_load();
		$this->_loadHandtekeningen();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get document ID
	 * @return int
	 */
	public function documentID()
	{
		return $this->_document_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Gegevens ophalen
	 * @return void
	 */
	public function _load()
	{
		$sql = "SELECT documenten.*, documenten_templates_settings.owner, documenten_templates_settings.template_name, documenten_templates_settings.categorie_id, documenten_categorieen.*
				FROM documenten
				LEFT JOIN documenten_templates_settings ON documenten_templates_settings.template_id = documenten.template_id
				LEFT JOIN documenten_categorieen ON documenten_categorieen.categorie_id = documenten_templates_settings.categorie_id
				WHERE documenten.document_id = $this->_document_id AND documenten_templates_settings.deleted = 0
				LIMIT 1";
		
		$query = $this->db_user->query( $sql );
		$this->_data = DBhelper::toRow( $query );

		$this->_html = $this->_data['html'];//html los in een var
		unset($this->_data['html']);//hier weghalen
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Handtekeningen ophalen
	 * @return void
	 */
	public function _loadHandtekeningen()
	{
		$sql = "SELECT * FROM documenten_handtekeningen WHERE document_id = $this->_document_id";
		$query = $this->db_user->query( $sql );
		
		$this->_handtekeningen = DBhelper::toArray( $query, 'id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get details
	 * @return array
	 */
	public function details()
	{
		return $this->_data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get owner
	 * @return string
	 */
	public function owner()
	{
		return $this->_data['owner'];
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * document uploaden als ondertekening
	 * @return bool
	 */
	public function uploadSignedFile( $file_info )
	{
		
		//rename
		$old_file = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $file_info['file_dir'] . '/' . $file_info['file_name'];
		
		$new_name = str_replace('.pdf', '_signed.pdf', $this->_data['file_name']);
		$new_file = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $this->_data['file_dir'] . '/' . $new_name;
		
		if( rename( $old_file, $new_file) )
		{
			$update['signed'] = 1;
			$update['signed_on'] = date('Y-m-d H:i:s');
			$update['signed_file_dir'] = $this->_data['file_dir'];
			$update['signed_file_name'] = $new_name;
			$update['signed_file_name_display'] = $new_name;
			
			//update file
			$this->db_user->where( 'document_id', $this->_document_id );
			$this->db_user->update( 'documenten', $update );
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * document ondertekenen
	 * @return bool
	 */
	public function sign()
	{
		//show($_POST);
		$pdf = $this->pdf();
		
		//arbeidscontract moet anders getekend worden
		
		$arbeidsovereenkomst = false;
		if( $this->_data['categorie'] == 'arbeidsovereenkomst' )
			$arbeidsovereenkomst = true;

		//handtekening toevoegen, aantal aanwezigen meegeven
		$file_info = $pdf->addSignature( $this->countSignatures(), $arbeidsovereenkomst );
		
		if( file_exists($file_info['signed_file_path']))
		{
			unset($file_info['signed_file_path']);
			
			//update file
			$this->db_user->where( 'document_id', $this->_document_id );
			$this->db_user->update( 'documenten', $file_info );
			
			//handtekening naar database
			$update['user_id'] = $this->user->user_id;
			$update['naam'] = $this->user->user_name;
			$update['ip'] = $_SERVER['REMOTE_ADDR'];
			$update['signed_on'] = date( 'Y-m-d H:i:s' );
			
			if( $this->user->user_type == 'uitzender' )
				$this->db_user->where( 'uitzender_id', $this->uitzender->id );
			
			if( $this->user->user_type == 'inlener' )
				$this->db_user->where( 'inlener_id', $this->inlener->id );
			
			if( $this->user->user_type == 'werknemer' )
				$this->db_user->where( 'werknemer_id',  $this->werknemer->id );
			
			if( $this->user->user_type == 'zzp' )
				$this->db_user->where( 'zzp_id',  $this->zzp->id );
			
			$this->db_user->where( 'document_id', $this->_document_id );
			$this->db_user->update( 'documenten_handtekeningen', $update );

			//kijken of alles getekend is
			$this->checkSignatures();
			
			return true;
		}
		
		return false;
		/*
		$encoded_image = explode(",", $_POST['imageData'])[1];
		$decoded_image = base64_decode($encoded_image);
		
		$dir = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id . '/';
		//file_put_contents( $dir . "signature.png", $decoded_image);
		
		file_put_contents( $dir . "signature.jpg", $decoded_image);*/
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * getekende handtekeningen entries
	 * @return int
	 */
	public function countSignatures()
	{
		$sql = "SELECT * FROM documenten_handtekeningen WHERE document_id = $this->_document_id AND user_id IS NOT NULL";
		$query = $this->db_user->query( $sql );
		
		return $query->num_rows();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * is alles getekend, dan document signed naar 1
	 * @return void
	 */
	public function checkSignatures()
	{
		$sql = "SELECT * FROM documenten_handtekeningen WHERE document_id = $this->_document_id AND user_id IS NULL";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
		{
			$this->db_user->where( 'document_id', $this->_document_id );
			$this->db_user->update( 'documenten', array('signed'=>1, 'signed_on'=>date('Y-m-d H:i:s')) );
		}
	}
	


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check if current user has access to view document
	 * @return bool
	 */
	public function userHasAccess()
	{
		//TODO :uitbreiden
		if( $this->owner() == 'uitzender' )
		{
			//uitzender documenten mag alleen door werkgever of uitzender zelf bekeken worden
			if( $this->user->user_type == 'werkgever' )
				return true;
				
			if( $this->user->user_type == 'inlener' || $this->user->user_type == 'werknemer' )
				return  false;
			
			//bij uitzender ID checken
			if( $this->user->user_type == 'uitzender' )
			{
				if( $this->uitzender->uitzender_id != $this->_data['uitzender_id'])
					return false;
				else
					return true;
			}
		}
		
		if( $this->owner() == 'inlener' )
		{
			//uitzender documenten mag alleen door werkgever of uitzender zelf bekeken worden
			if( $this->user->user_type == 'werkgever' )
				return true;
			
			if( $this->user->user_type == 'werknemer' )
				return  false;
			
			//uitnreiden
			if( $this->user->user_type == 'uitzender' )
			{
				return  true;
			}
			
			
			//bij uitzender ID checken
			if( $this->user->user_type == 'inlener' )
			{
				if( $this->inlener->inlener_id != $this->_data['inlener_id'])
					return false;
				else
					return true;
			}
		}
		
		if( $this->owner() == 'werknemer' )
		{
			//external mag
			if( $this->user->user_type == 'external' )
				return true;
			
			//uitzender documenten mag alleen door werkgever of uitzender zelf bekeken worden
			if( $this->user->user_type == 'werkgever' )
				return true;
			
		
			if( $this->user->user_type == 'uitzender' )
			{
				$werknemer = new Werknemer( $this->_data['werknemer_id'] );
				if(  $this->uitzender->id !=  $werknemer->uitzenderID())
					return false;
				return true;
			}
			
			
			if( $this->user->user_type == 'inlener' )
				return false;
		}
		
		//failsafe
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get Pdf uit database en return object
	 * @return object|void
	 */
	public function pdf( $origineel = false )
	{
		//is er een ondertekende versie, dan die gebruiken
		if( !$origineel && isset($this->_data['signed_file_name']) && $this->_data['signed_file_name'] !== NULL )
		{
			$file['file_name'] = $this->_data['signed_file_name'];
			$file['file_dir'] = $this->_data['signed_file_dir'];
		}
		else
		{
			//check of er een bestand is
			if( !isset($this->_data['file_name']) || $this->_data['file_name'] === NULL )
				return NULL;
			else
			{
				$file['file_name'] = $this->_data['file_name'];
				$file['file_dir'] = $this->_data['file_dir'];
			}
		}
		
		$pdf = new Pdf( $file );
		return $pdf;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get Handetekeningen
	 * @return array
	 */
	public function handtekeningen()
	{
		return $this->_handtekeningen;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set entiteit ID
	 * @return object
	 */
	public function setEntiteitID( $entiteit_id = NULL )
	{
		if( $entiteit_id !== NULL )
			$this->_entiteit_id = $entiteit_id;
		else
			$this->_entiteit_id = $_SESSION['entiteit_id'];
		
		$this->_setWerkgeverInfo();
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer info
	 * @return void
	 */
	public function _setWerkgeverInfo()
	{
		$sql = "SELECT werkgever_bedrijfsgegevens.*
				FROM werkgever_bedrijfsgegevens
				WHERE werkgever_bedrijfsgegevens.deleted = 0 AND entiteit_id = $this->_entiteit_id";
		
		$query = $this->db_user->query( $sql );
		$this->_werkgever_info = DBhelper::toRow($query);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer
	 * @return object
	 */
	public function setWerknemerID( $werknemer_id ) :Document
	{
		$this->_werknemer_id = intval( $werknemer_id );
		$this->_setWerknemerInfo();
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer
	 * @return object
	 */
	public function setZzpID( $zzp_id )  :Document
	{
		$this->_zzp_id = intval( $zzp_id );
		$this->_setZzpInfo();
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer info
	 * @return void
	 */
	public function _setWerknemerInfo()
	{
		$sql = "SELECT werknemers_gegevens.*, werknemers_dienstverband_duur.fase, werknemers_verloning_instellingen.et_regeling
				FROM werknemers_gegevens
				LEFT JOIN werknemers_dienstverband_duur ON werknemers_gegevens.werknemer_id = werknemers_dienstverband_duur.werknemer_id
				LEFT JOIN werknemers_verloning_instellingen ON werknemers_gegevens.werknemer_id = werknemers_verloning_instellingen.werknemer_id
				WHERE werknemers_gegevens.deleted = 0 AND werknemers_dienstverband_duur.deleted = 0 AND werknemers_gegevens.werknemer_id = $this->_werknemer_id";
		
		$query = $this->db_user->query( $sql );

		$this->_werknemer_info = DBhelper::toRow($query);
		$this->_werknemer_info['gb_datum'] = reverseDate($this->_werknemer_info['gb_datum']);
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer info
	 * @return void
	 */
	public function _setZzpInfo()
	{
		$sql = "SELECT zzp_bedrijfsgegevens.*
				FROM zzp_bedrijfsgegevens
				WHERE zzp_bedrijfsgegevens.deleted = 0 AND zzp_id = $this->_zzp_id";
		
		$query = $this->db_user->query( $sql );
		
		$this->_zzp_info = DBhelper::toRow($query);
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set inlener
	 * @return object
	 */
	public function setInlenerID( $inlener_id ) :Document
	{
		$this->_inlener_id = intval( $inlener_id );
		$this->_setInlenerInfo();
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set inlener info
	 * @return void
	 */
	public function _setInlenerInfo()
	{
		$sql = "SELECT inleners_bedrijfsgegevens.*
				FROM inleners_bedrijfsgegevens
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inlener_id = $this->_inlener_id";
		
		$query = $this->db_user->query( $sql );
		
		$this->_inlener_info = DBhelper::toRow($query);
		
		//contactpersonen
		$sql = "SELECT * FROM inleners_contactpersonen WHERE deleted = 0 AND inlener_id = $this->_inlener_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		$this->_inlener_info['contactpersoon'] = DBhelper::toRow($query);
		
		//factuurgegevens
		$sql = "SELECT * FROM inleners_factuurgegevens WHERE deleted = 0 AND inlener_id = $this->_inlener_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		$this->_inlener_info['factuurgegevens'] = DBhelper::toRow($query);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set uitzender
	 * @return object
	 */
	public function setUitzenderID( $uitzender_id ) :Document
	{
		$this->_uitzender_id = intval( $uitzender_id );
		$this->_setUitzenderInfo();
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set uitzender info
	 * @return void
	 */
	public function _setUitzenderInfo()
	{
		$uitzender = new Uitzender( $this->_uitzender_id );
		
		$this->_uitzender_info = $uitzender->bedrijfsgegevens();
		$this->_uitzender_info['logo'] = $uitzender->logo( 'path' );
		$this->_uitzender_info['systeeminstellingen'] =  $uitzender->systeeminstellingen();
	
		//contactpersonen
		$sql = "SELECT * FROM uitzenders_contactpersonen WHERE deleted = 0 AND uitzender_id = $this->_uitzender_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		$this->_uitzender_info['contactpersoon'] = DBhelper::toRow($query);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * dummy data gebruiken
	 *
	 * @return object
	 */
	public function dummy() :Document
	{
		$this->_uitzender_info['bedrijfsnaam'] = 'FeelGoodPeople B.V.';
		$this->_uitzender_info['straat'] = 'Uitzendsingel';
		$this->_uitzender_info['huisnummer'] = '136';
		$this->_uitzender_info['postcode'] = '4566GH';
		$this->_uitzender_info['plaats'] = 'Hoofddorp';
		$this->_uitzender_info['kvknr'] = '12345678';
		$this->_uitzender_info['btwnr'] = 'NL123456789B01';
		/*
		$this->_uitzender_info['bedrijfsnaam'] = 'Royal DS Personeel-service B.V.';
		$this->_uitzender_info['straat'] = 'Ambachtsweg';
		$this->_uitzender_info['huisnummer'] = '57';
		$this->_uitzender_info['postcode'] = '6541DA';
		$this->_uitzender_info['plaats'] = 'Nijmegen';
		$this->_uitzender_info['kvknr'] = '62724827';
		$this->_uitzender_info['btwnr'] = 'NL854932525B01';*/
		
		$this->_uitzender_info['contactpersoon']['aanhef'] = 'de heer';
		$this->_uitzender_info['contactpersoon']['naam'] = 'U.K.L. van Jongbloed';
		/*
		$this->_uitzender_info['contactpersoon']['aanhef'] = 'de heer';
		$this->_uitzender_info['contactpersoon']['naam'] = 'R. Weeren';*/
		
		
		$this->_inlener_info['bedrijfsnaam'] = 'Clean Supplies Schoonmaak B.V.';
		$this->_inlener_info['straat'] = 'Inleenstraat';
		$this->_inlener_info['huisnummer'] = '27';
		$this->_inlener_info['postcode'] = '7545KL';
		$this->_inlener_info['plaats'] = 'Enschede';
		$this->_inlener_info['kvknr'] = '87654321';
		$this->_inlener_info['btwnr'] = 'NL123456789B01';
		
		$this->_inlener_info['factuurgegevens']['termijn'] = '30';
		$this->_inlener_info['factuurgegevens']['g_rekening_percentage'] = '30';
		
		$this->_inlener_info['contactpersoon']['aanhef'] = 'mevrouw';
		$this->_inlener_info['contactpersoon']['naam'] = 'J.L. de Grootte';
		
		$this->_werknemer_info['gb_datum'] = '17-11-1985';
		$this->_werknemer_info['voorletters'] = 'J.';
		$this->_werknemer_info['tussenvoegsel'] = 'de';
		$this->_werknemer_info['achternaam'] = 'Jong';
		$this->_werknemer_info['voornaam'] = 'Jennie';
		$this->_werknemer_info['straat'] = 'Werknemerstraat';
		$this->_werknemer_info['geslacht'] = 'm';
		$this->_werknemer_info['huisnummer'] = '3';
		$this->_werknemer_info['huisnummer_toevoeging'] = '';
		$this->_werknemer_info['postcode'] = '5589OP';
		$this->_werknemer_info['plaats'] = 'Apeldoorn';
		$this->_werknemer_info['iban'] = 'NL87RABO13245678';
		
		/*
		$this->_werknemer_info['gb_datum'] = '04-09-1974';
		$this->_werknemer_info['voorletters'] = 'D.';
		$this->_werknemer_info['tussenvoegsel'] = '';
		$this->_werknemer_info['achternaam'] = 'Racoreanu';
		$this->_werknemer_info['voornaam'] = 'Dumitrel';
		$this->_werknemer_info['straat'] = 'Strada Asachi Gheorghe';
		$this->_werknemer_info['geslacht'] = 'm';
		$this->_werknemer_info['huisnummer'] = '24';
		$this->_werknemer_info['huisnummer_toevoeging'] = '';
		$this->_werknemer_info['postcode'] = '810080';
		$this->_werknemer_info['plaats'] = 'Braila';
		$this->_werknemer_info['iban'] = '';*/
		
		
		$this->_plaatsing['start_plaatsing'] = '2020-05-04';
		$this->_plaatsing['bruto_loon'] = '12,54';
		$this->_plaatsing['cao']['werksoort'][1]['name'] = 'Standaard uur';
		$this->_plaatsing['cao']['werksoort'][1]['amount'] = '100.00';
		$this->_plaatsing['cao']['werksoort'][1]['hour_type'] = 'normaal';
		$this->_plaatsing['cao']['cao_name'] = 'Afbouw';
		
		$this->_inlenersbeloning['cao_ja_nee'] = 'ja';
		$this->_inlenersbeloning['cao'] = 'Afbouw';
		$this->_inlenersbeloning['branche'] = 'Bouwtimmeren';
		$this->_inlenersbeloning['werkweek'] = '37.5';
		$this->_inlenersbeloning['loonschalen_ja_nee'] = 'ja';
		$this->_inlenersbeloning['adv_ja_nee'] = 'ja';
		$this->_inlenersbeloning['adv_uren'] = '10';
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * te verwachten handetekening toevoegen in datadase
	 *
	 * @return void
	 */
	public function addEmptySignature( $user_type, $user_id )
	{
		if( $this->_document_id === NULL )
			die('Kan geen lege handtekening toevoegen zonder document ID');

		$insert['document_id'] = $this->_document_id;
		$insert[ $user_type . '_id' ] = $user_id;

		$this->db_user->insert( 'documenten_handtekeningen', $insert );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Variabelen in de tekst vervangen
	 *
	 * @return void
	 */
	public function replaceVars()
	{
		
		//beginnen met werkgever vars
		foreach( $this->_werkgever_info as $field => $value )
			$this->_html = str_replace( '{{werkgever.'.$field.'}}', $value, $this->_html );
		
		//werkgever handtekening
		$CI = &get_instance();// Grab the super object
		$this->_html = str_replace( '{{werkgever.handtekening}}', '<img style="margin-top:20px;max-height:75px; max-width:150px" src="data:image/jpeg;base64,'.base64_encode($CI->werkgever->handtekening()).'" />', $this->_html );
		
		//uitzender vars
		foreach( $this->_uitzender_info as $field => $value )
		{
			if( !is_array($value))
				$this->_html = str_replace( '{{uitzender.' . $field . '}}', $value, $this->_html );
			else
			{
				foreach( $value as $field2 => $value2 )
					$this->_html = str_replace( '{{uitzender.' . $field . '.' . $field2 . '}}', $value2, $this->_html );
			}
		}
		
		//inlener vars
		foreach( $this->_inlener_info as $field => $value )
		{
			if( !is_array($value))
				$this->_html = str_replace( '{{inlener.' . $field . '}}', $value, $this->_html );
			else
			{
				foreach( $value as $field2 => $value2 )
					$this->_html = str_replace( '{{inlener.' . $field . '.' . $field2 . '}}', $value2, $this->_html );
			}
		}
		
		//werknemer vars
		foreach( $this->_werknemer_info as $field => $value )
		{
			if( !is_array($value))
				$this->_html = str_replace( '{{werknemer.' . $field . '}}', $value, $this->_html );
			else
			{
				foreach( $value as $field2 => $value2 )
					$this->_html = str_replace( '{{werknemer.' . $field . '.' . $field2 . '}}', $value2, $this->_html );
			}
		}
		
		
		//datum/tijd vars
		$this->_html = str_replace( '{{datum.datum}}', date('d-m-Y'), $this->_html );
		
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}
}


?>