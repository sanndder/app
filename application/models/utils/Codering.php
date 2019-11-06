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

	/*
	 * @var array
	 */
	private $_error = NULL;


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * haal landenlijst op
	 *
	 * @return array or boolean
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
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * haal nationalititen op
	 *
	 * @return array or boolean
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
	
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
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