<?php

namespace models\werknemers;

use models\cao\CAOGroup;
use models\Connector;
use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\inleners\Inlener;
use models\utils\DBhelper;
use models\verloning\Urentypes;
use models\verloning\UrentypesGroup;
use models\verloning\Vergoeding;
use models\verloning\VergoedingGroup;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class LoonbeslagGroup extends Connector
{
	/**
	 * @var int
	 */
	private $_werknemer_id;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factor plaatsing aanpassen
	 *
	 */
	public function setWerknemerID( $werknemer_id )
	{
		$this->_werknemer_id = intval($werknemer_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get all
	 *
	 */
	public function all() :?array
	{
		$sql = "SELECT werknemers_loonbeslag.*, statussen.status
				FROM werknemers_loonbeslag
				LEFT JOIN werknemers_loonbeslag_statussen AS statussen ON werknemers_loonbeslag.status_id = statussen.status_id
				WHERE werknemers_loonbeslag.deleted = 0 ";
		
		if( $this->_werknemer_id !== NULL )
			$sql .= " AND werknemer_id = $this->_werknemer_id ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$data[$row['status_id']][$row['loonbeslag_id']] = $row;
		}
		
		return $data;
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