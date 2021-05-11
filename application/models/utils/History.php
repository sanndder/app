<?php

namespace models\utils;

use models\Connector;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * History class
 *
 *
 *
 */

class History extends Connector
{

	private $_table = NULL; // @var string
	private $_index = NULL; // @var array
	

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
	 * Set index
	 * indexfield => value
	 */
	public function index( $index )
	{
		$this->_index = $index;
		
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set table
	 * Only allowed tables
	 */
	public function table( $table )
	{
		if( $table == 'inlener_bedrijfsgegevens' ) $this->_table = 'inleners_bedrijfsgegevens';
		
		//abort for security reasons
		if( $this->_table === NULL )
			die('ERROR');
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 *
	 */
	public function data()
	{
		$sql = "SELECT $this->_table.user_id, $this->_table.timestamp, $this->_table.* FROM $this->_table WHERE ";
		
		//indexes
		foreach( $this->_index as $field => $value )
			$sql .= " $field = ".intval($value)." AND";

		//delete last "and"
		$sql = substr($sql,0,-4);
		
		//order
		$sql .= " ORDER BY timestamp DESC";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			unset($row['id']);
			unset($row['deleted']);
			unset($row['deleted_on']);
			unset($row['deleted_by']);
			unset($row['inlener_id']);
			
			$data[] = $row;
		}
		
		return $data;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array | bool
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