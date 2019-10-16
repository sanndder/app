<?php

namespace models\users;

use models\Connector;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * User Lists
 *
 * Alle lijsten van user moeten via deze class
 *
 */
class UserGroup extends Connector {

	/*
	 * set columns for SELECT query
	 * @var array
	 */
	private $_cols = 'user_id, username, user_type, admin, naam, email, email_confirmed, timestamp';
	
	/**
	 * admin database
	 * @var object
	 */
	private $db_admin = NULL;
	
	/**
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
		
		//user class needs acces to admin database
		$CI =& get_instance();
		//show($CI);
		$this->db_admin = $CI->auth->db_admin;
		
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * aanpassen welke columns er opgehaald moeten worden, standaard alles
	 * param is door komma gescheiden lijst
	 * @return void
	 */
	public function setColumns( $cols = NULL )
	{
		if( $cols != NULL )
			$this->_cols = $cols;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle users ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();
		
		//start query
		$sql = "SELECT $this->_cols 
				FROM users
				WHERE users.deleted = 0
					AND users.werkgever_id = ".$this->user->werkgever_id." ";

		//order
		$sql .= " ORDER BY users.username";
		
		$query = $this->db_admin->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
		{
			//nooit wachtwoord meenemen
			unset($row['password']);
			
			$data[$row['user_id']] = $row;
		}

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