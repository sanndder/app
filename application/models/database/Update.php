<?php

namespace models\database;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Krediet aanvraag
 *
 * Kan uiteindelijk omgezet worden in een inlener
 *
 */
class Update {
	
	private $_databases = NULL;
	private $db_admin = NULL;
	private $db_user = NULL;
	
	


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		$CI =& get_instance();
		$this->db_admin  = $CI->load->database( 'admin', TRUE );
		
		$this->getDatabases();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  Run queries
	 *
	 */
	public function query()
	{
		foreach( $this->_databases as $db )
		{
			$this->_connect($db);
			$this->db_user->query( $_POST['sql'] );
		}

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get all available databases
	 * Remove app_user, deze moet als test met de hand
	 *
	 */
	private function _connect( $db )
	{

		$config['hostname'] = 'localhost';
		$config['username'] = $db['db_user'];
		$config['password'] = $db['db_password'];
		$config['database'] = $db['db_name'];
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';
		
		$CI =& get_instance();
		$this->db_user = $CI->load->database($config, true);//load db_user

	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get all available databases
	 * Remove app_user, deze moet als test met de hand
	 *
	 */
	public function getDatabases()
	{
		$sql = "SELECT *, AES_DECRYPT( werkgevers.db_password, UNHEX(SHA2('".DB_SECRET."' ,512)) ) AS db_password FROM werkgevers";
		$query = $this->db_admin->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$this->_databases[] = $row;
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array | bool
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