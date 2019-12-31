<?php

namespace models\documenten;
use models\Connector;
use models\file\Pdf;
use models\pdf\PdfBuilder;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Documenten maken
 *
 */
class Document extends Connector {

	protected $_error = NULL;
	protected $_werknemer_id = NULL;
	protected $_werknemer_info = array();
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
	 * Gegevens ophalen
	 * @return void
	 */
	public function _load()
	{
		$sql = "SELECT documenten.*, documenten_templates_settings.owner
				FROM documenten
				LEFT JOIN documenten_templates_settings ON documenten_templates_settings.template_id = documenten.template_id
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
		$sql = "SELECT * FROM documenten_signed WHERE document_id = $this->_document_id";
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
	 * document ondertekenen
	 * @return bool
	 */
	public function sign()
	{
		//show($_POST);
		$pdf = $this->pdf();
		
		$file_info = $pdf->addSignature();
		
		if( file_exists($file_info['signed_file_path']))
		{
			unset($file_info['signed_file_path']);
			
			//update file
			$this->db_user->where( 'document_id', $this->_document_id );
			$this->db_user->update( 'inleners', $file_info );
			
			//handtekening naar database
			$insert['document_id'] = $this->_document_id;
			$insert['user_id'] = $this->user->user_id;
			$insert['naam'] = $this->user->user_name;
			
			if( $this->user->user_type == 'uitzender' )	$insert['uitzender_id'] = $this->uitzender->id;
			if( $this->user->user_type == 'inlener' )	$insert['inlener_id'] = $this->inlener->id;
			if( $this->user->user_type == 'werknemer' )	$insert['werknemer_id'] = $this->werknemer->id;
			
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
			//check of er een bestad is
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
	public function setWerknemerID( $werknemer_id )
	{
		$this->_werknemer_id = intval( $werknemer_id );
		$this->_setWerknemerInfo();
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer info
	 * @return void
	 */
	public function _setWerknemerInfo()
	{
		$sql = "SELECT werknemers_gegevens.*
				FROM werknemers_gegevens
				WHERE werknemers_gegevens.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		$this->_werknemer_info = DBhelper::toRow($query);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set inlener
	 * @return object
	 */
	public function setInlenerID( $inlener_id )
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
				WHERE inleners_bedrijfsgegevens.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		$this->_inlener_info = DBhelper::toRow($query);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set uitzender
	 * @return object
	 */
	public function setUitzenderID( $uitzender_id )
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
		//bedrijfsgegevens
		$sql = "SELECT uitzenders_bedrijfsgegevens.*
				FROM uitzenders_bedrijfsgegevens
				WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND uitzender_id = $this->_uitzender_id";
		
		$query = $this->db_user->query( $sql );
		
		$this->_uitzender_info = DBhelper::toRow($query);
		
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
	public function dummy()
	{
		$this->_uitzender_info['bedrijfsnaam'] = 'FeelGoodPeople B.V.';
		$this->_uitzender_info['straat'] = 'Uitzendsingel';
		$this->_uitzender_info['huisnummer'] = '136';
		$this->_uitzender_info['postcode'] = '4566GH';
		$this->_uitzender_info['plaats'] = 'Hoofddorp';
		$this->_uitzender_info['kvknr'] = '12345678';
		$this->_uitzender_info['btwnr'] = 'NL123456789B01';
		
		$this->_uitzender_info['contactpersoon']['aanhef'] = 'de heer';
		$this->_uitzender_info['contactpersoon']['naam'] = 'U.K.L. van Jongbloed';
		

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
		
		$this->db_user->insert( 'documenten_signed', $insert );
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