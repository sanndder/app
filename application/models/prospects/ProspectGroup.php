<?php

namespace models\prospects;

use models\Connector;
use models\users\UserGroup;
use models\utils\Codering;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Werknemer Lists
 *
 * Alle lijsten van werknemers moeten via deze class
 *
 */
class ProspectGroup extends Connector {
	
	/*
	 * @var array
	 */
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
	/*
	 * Alle prospects ophalen aan de hand van de zoekcriteria
	 *
	 */
	public function all( $param = NULL )
	{
		$sql = "SELECT prospects.*, prospects_status.status
				FROM prospects
				LEFT JOIN prospects_status ON prospects.status_id = prospects_status.status_id
				WHERE prospects.deleted = 0
				ORDER BY status_id, bedrijfsnaam";
		
		$query = $this->db_user->query( $sql );
		foreach( $query->result_array() as $row )
		{
			$data[] = $row;
		}
		
		$data = UserGroup::findUserNames( $data );
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