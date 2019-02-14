<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * Parent model om database te laden indien mogelijk
 */
class MY_Model extends CI_Model
{
	public $db_user = NULL;

	public function __construct()
	{
		//call parent constructor
		parent::__construct();

		//try to connect to user database
		$this->_connect();
	}

	/*
	 * Connector om extra database connectie te maken, maar ook om meerdere connecties uit de weg te gaan
	 * Op deze manier wordt het aantal threads beperkt
	 */
	public function _connect()
	{
		$config['hostname'] = 'localhost';
		$config['username'] = 'root';
		$config['password'] = '';
		$config['database'] = 'flxuur_0001';
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';


		// Grab the super object
		$CI =& get_instance();

		//check if connection exists
		if( isset($CI->db_user) && is_object($CI->db_user) && ! empty($CI->db_user->conn_id) )
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

}


?>