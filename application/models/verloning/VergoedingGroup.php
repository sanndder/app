<?php

namespace models\Verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class VergoedingGroup extends Connector
{

	/*
	 * @var array
	 */
	private $_error = NULL;
	
	/**
	 * @var int
	 */
	private $_inlener_id;
	/**
	 * @var int
	 */
	private $_werknemer_ids;
	
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
	 * set inlener id
	 *
	 */
	public function inlener( $inlener_id )
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function werknemers( $werknemer_ids )
	{
		$this->_werknemer_ids = $werknemer_ids;
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function all()
	{
		$sql = "SELECT * FROM vergoedingen WHERE deleted = 0 ORDER BY naam";
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'vergoeding_id' );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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