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

class Codering extends Connector
{


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
	 * haal landenlijst op
	 *
	 * @return array | bool
	 */
	static function listLanden()
	{
		$CI =& get_instance();
		$db_admin = $CI->auth->db_admin;
		
		$sql = "SELECT id, landnaam FROM list_landen ORDER BY sort_order, landnaam";
		$query = $db_admin->query( $sql );
		
		foreach( $query->result_array() as $row )
			$data[$row['id']] = $row['landnaam'];
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * haal landenlijst op
	 *
	 * @return array | bool
	 */
	static function listLandCodes()
	{
		$CI =& get_instance();
		$db_admin = $CI->auth->db_admin;
		
		$sql = "SELECT id, code FROM list_landen ORDER BY sort_order, landnaam";
		$query = $db_admin->query( $sql );
		
		foreach( $query->result_array() as $row )
			$data[$row['id']] = $row['code'];
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * naam voor een land
	 *
	 * @return array | bool
	 */
	static function landFromId( $id )
	{
		$CI =& get_instance();
		$db_admin = $CI->auth->db_admin;
		
		$sql = "SELECT landnaam FROM list_landen WHERE id = ". intval($id)." LIMIT 1 ";
		$query = $db_admin->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		
		return $data['landnaam'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * code voor een land
	 *
	 * @return array | bool
	 */
	static function landcodeFromId( $id )
	{
		$CI =& get_instance();
		$db_admin = $CI->auth->db_admin;
		
		$sql = "SELECT code FROM list_landen WHERE id = ". intval($id)." LIMIT 1 ";
		$query = $db_admin->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		
		return $data['code'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * haal nationalititen op
	 *
	 * @return array | bool
	 */
	static function listNationaliteiten()
	{
		$CI =& get_instance();
		$db_admin = $CI->auth->db_admin;
		
		$sql = "SELECT code, omschrijving FROM list_nationaliteiten ORDER BY sort_order, omschrijving";
		$query = $db_admin->query( $sql );
		
		foreach( $query->result_array() as $row )
			$data[$row['code']] = $row['omschrijving'];
		
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