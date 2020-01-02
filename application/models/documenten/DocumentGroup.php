<?php

namespace models\documenten;
use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Documenten maken
 *
 */
class DocumentGroup extends Connector {

	private $_error = NULL;
	private $_werknemer_id = NULL;
	private $_inlener_id = NULL;
	private $_uitzender_id = NULL;
	
	private $_deleted = 0;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * IDs
	 * @return object
	 */
	public function uitzender( $id )
	{
		$this->_uitzender_id = intval($id);
		return $this;
	}
	
	public function inlener( $id )
	{
		$this->_inlener_id = intval($id);
		return $this;
	}
	
	public function werknemer( $id )
	{
		$this->_werknemer_id = intval($id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * met verwijderde items
	 * @return object
	 */
	public function deleted()
	{
		$this->_deleted = 1;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * met verwijderde items
	 * @return object
	 */
	public function get()
	{
		$sql = "SELECT documenten.document_id, documenten.werknemer_id, documenten.inlener_id, documenten.uitzender_id, documenten.send, documenten.file_name, documenten.signed, documenten.timestamp AS aangemaakt, documenten.deleted, documenten.deleted_by, documenten.deleted_on,
       					documenten_templates_settings.*, dc.categorie
				FROM documenten
				LEFT JOIN documenten_templates_settings ON documenten.template_id = documenten_templates_settings.template_id
				LEFT JOIN documenten_categorieen dc on documenten_templates_settings.categorie_id = dc.categorie_id
				WHERE documenten.template_id IS NOT NULL
				";
		
		if( $this->_deleted == 0 )$sql .= " AND documenten.deleted = 0";
		
		if( $this->_werknemer_id !== NULL )	$sql .= " AND documenten.werknemer_id = $this->_werknemer_id";
		if( $this->_inlener_id !== NULL )	$sql .= " AND documenten.inlener_id = $this->_inlener_id";
		if( $this->_uitzender_id !== NULL )	$sql .= " AND documenten.uitzender_id = $this->_uitzender_id";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'document_id');
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