<?php

namespace models;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Connector class
 * Can be used as a parent to connect to database via codeignitor
 *
 */

class Connector
{
	protected $db_user = NULL;
	protected $user = NULL;


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get bedrijfsgegevens
	 *
	 */
	public function connect( $werkgever_id = '' )
	{
		// Grab the super object
		$CI =& get_instance();

		//copy user
		$this->user = $CI->user;

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


}


?>