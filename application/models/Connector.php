<?php

namespace models;

use CI_DB_driver;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Connector class
 * Can be used as a parent to connect to database via codeignitor
 * @property CI_DB_result $db_user CI_DB_result module
 */

class Connector
{
	protected $db_user = NULL;
	protected $user = NULL;
	protected $werkgever = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * When werkgever_id is know, connect tot database
	 *
	 */
	public function __construct()
	{
		if( isset($_SESSION['logindata']['werkgever_id']) )
		{
			$this->connect( $_SESSION['logindata']['werkgever_id'] );
		}

	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Connect calss to database
	 *
	 */
	public function connect( $werkgever_id = '' )
	{
		// Grab the super object
		$CI =& get_instance();

		//copy user
		$this->user = $CI->user;
		$this->werkgever = $CI->werkgever;

		$config['hostname'] = 'localhost';
		$config['username'] = $CI->config->item('db_user');
		$config['password'] = $CI->config->item('db_password');
		$config['database'] = $CI->config->item('db_name');
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';

		//check if connection exists
		if( isset($CI->db_user) && is_object($CI->db_user) && !empty($CI->db_user->conn_id) )
		{
			//copy instance
			$this->db_user = $CI->db_user;
		}
		//else load database
		else
		{
			$this->load->database($config, false, '', 'db_user');//load db_user
			$this->db_user = $CI->db_user;// first time also copy
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * SELECT * FROM table LIMIT 1 and return row array
	 *
	 */
	public function select_row( string $table, array $where = array() ) :?array
	{
		$sql = "SELECT * FROM $table WHERE deleted = 0 ";
		
		//add where
		if( count($where) > 0 )
			$sql .= " AND ".key($where). " = '".current($where)."' LIMIT 1";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		return $query->row_array();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * SELECT * FROM table and return array with or without custom index
	 *
	 */
	public function select_all( string $table, string $index = '', $where = array() ) :?array
	{
		$sql = "SELECT * FROM $table WHERE deleted = 0";
		
		//add where
		if( count($where) > 0 )
			$sql .= " AND ".key($where). " = '".current($where)."' ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $index != '' )
				$key = $row[$index];
			
			if( isset($key) )
				$data[$key] = $row;
			else
				$data[] = $row;
		}
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * SELECT * FROM table and return array with or without custom index
	 *
	 */
	public function max( string $table, string $field = '' ) :int
	{
		$sql = "SELECT MAX($field) AS max_val FROM $table";
		$query = $this->db_user->query( $sql );
		
		$data = $query->row_array();
		if( $data['max_val'] === NULL )
			return 0;
		
		return  $data['max_val'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * DELETE row
	 *
	 */
	public function delete_row( string $table, array $where = array() ) :bool
	{
		$sql = "UPDATE $table
				SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . "
				WHERE deleted = 0 AND ".key($where). " = ? ";
		
		$this->db_user->query($sql, array(intval(current($where))));
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}
}


?>