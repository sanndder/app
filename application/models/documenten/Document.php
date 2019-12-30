<?php

namespace models\documenten;
use models\Connector;
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
		$sql = "SELECT documenten.*
				FROM documenten
				WHERE document_id = $this->_document_id
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
	 * get Handetekeningen
	 * @return object
	 */
	public function details()
	{
		return $this->_data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get Handetekeningen
	 * @return object
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