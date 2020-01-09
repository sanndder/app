<?php

namespace models\werknemers;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class Et extends Connector
{
	/**
	 * @var int
	 */
	private $_werknemer_id;
	private $_error;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $werknemer_id )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		$this->setID( $werknemer_id );
		
		$this->init();
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * databse vullen wanneer ET wordt aangezet
	 *
	 */
	public function init()
	{
		//instellingen
		$query = $this->db_user->query( "SELECT id FROM werknemer_et_settings WHERE werknemer_id = $this->_werknemer_id LIMIT 1" );
		if( $query->num_rows() == 0 )
		{
			$insert['werknemer_id'] = $this->_werknemer_id;
			$this->db_user->insert( 'werknemer_et_settings', $insert );
		}
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($werknemer_id)
	{
		$this->_werknemer_id = intval($werknemer_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * upload bsn ophalen
	 *
	 * @return string|void
	 */
	public function fileBsn()
	{
		$sql = "SELECT * FROM werknemer_et_bsn WHERE deleted = 0 AND werknemer_id = $this->_werknemer_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		if ($query->num_rows() == 0)
			return NULL;
		
		$row = $query->row_array();
		
		//full path
		$file_path =  UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $row['file_dir'] . '/' . $row['file_name'];
		
		//check
		if( !file_exists($file_path))
			return NULL;
		
		return $file_path;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  del bsn file
	 */
	public function delbsn()
	{
		$this->db_user->query("UPDATE werknemer_et_bsn SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND werknemer_id = $this->_werknemer_id");
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Instellingen
	 *
	 * @return array
	 */
	public function settings()
	{
		$query = $this->db_user->query( "SELECT * FROM werknemer_et_settings WHERE werknemer_id = $this->_werknemer_id AND deleted = 0" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_error === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}

		if ($this->_error === NULL)
			return false;

		return $this->_error;
	}
}


?>