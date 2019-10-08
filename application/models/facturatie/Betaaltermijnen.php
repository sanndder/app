<?php

namespace models\Facturatie;

use models\Connector;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Betaaltermijnen class
 *
 *
 *
 */

class Betaaltermijnen extends Connector
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
	 * haal betaaltermijnen op
	 *
	 * @return array or boolean
	 */
	static function list()
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT termijn FROM settings_betaaltermijnen WHERE deleted = 0 ORDER BY termijn";
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['termijn']] = $row['termijn'] . ' dagen';
		
		return $data;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 *
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