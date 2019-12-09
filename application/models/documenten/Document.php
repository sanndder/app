<?php

namespace models\Documenten;
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
	protected $_werknemer_info = NULL;
	protected $_inlener_id = NULL;
	protected $_inlener_info = NULL;
	protected $_uitzender_id = NULL;
	protected $_uitzender_info = NULL;
	protected $_entiteit_id = NULL;
	protected $_werkgever_info = NULL;
	
	protected $_html = '';
	
	
	
	protected $pdf = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		//default entiteit
		$this->setEntiteitID();
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * view pdf
	 * @return object
	 */
	public function preview()
	{
		$this->pdf->view();
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
		$sql = "SELECT uitzenders_bedrijfsgegevens.*
				FROM uitzenders_bedrijfsgegevens
				WHERE uitzenders_bedrijfsgegevens.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		$this->_uitzender_info = DBhelper::toRow($query);
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