<?php

namespace models\verloning;

use models\Connector;
use models\utils\DBhelper;
use models\utils\Tijdvak;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Loonstoken zip class
 *
 */

class Reserveringen extends Connector
{
	
	private $_werknemer_id = NULL;

	private $_error = NULL;
	
	
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
	 *
	 * set path
	 *
	 */
	public function werknemer( $werknemer_id ) :Reserveringen
	{
		$this->_werknemer_id = intval($werknemer_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * stand
	 *
	 */
	public function stand() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM werknemers_reserveringen WHERE deleted = 0 AND werknemer_id = $this->_werknemer_id LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
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