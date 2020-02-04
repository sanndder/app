<?php

namespace models\users;

use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * User Lists
 *
 * Alle lijsten van user moeten via deze class
 *
 */
class UserGroup extends Connector {

	/**
	 * admin database
	 * @var object
	 */
	private $db_admin = NULL;
	
	private $_uitzender_id = NULL;
	private $_inlener_id = NULL;
	
	/**
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
		
		//user class needs acces to admin database
		$CI =& get_instance();
		$this->db_admin = $CI->auth->db_admin;
		
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * lijst met users
	 *
	 */
	static function list( $ids = array() )
	{
		$list[NULL] = 'onbekend';
		$list[0] = 'systeem';
		
		if( !is_array($ids) || count($ids) == 0 )
			return $list;
		
		if( current($ids) === NULL )
			return $list;
		
		$CI =& get_instance();
		$db_admin = $CI->auth->db_admin;
		
		$sql = "SELECT user_id, naam FROM users WHERE user_id IN (".implode(',',$ids).")";
		$query = $db_admin->query( $sql );
		
		if( $query->num_rows() == 0 )
			return $list;
		
		foreach( $query->result_array() as $row )
			$list[$row['user_id']] = $row['naam'];
		
		return $list;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * users in array voegen
	 *
	 */
	static function findUserNames( $array = array() )
	{
		if( count($array) == 0 )
			return $array;
		
		$user_ids = array();
		
		foreach( $array as $a )
		{
			if( isset($a['user_id']) )
				$user_ids[] = $a['user_id'];
			if( isset($a['created_by']) )
				$user_ids[] = $a['created_by'];
			if( isset($a['deleted_by']) )
				$user_ids[] = $a['deleted_by'];
		}
		
		if( count($user_ids) == 0)
			return $array();
		
		$users = UserGroup::list( $user_ids );
		
		foreach( $array as &$a )
		{
			if( isset($a['user_id']) )
				$a['user'] = $users[$a['user_id']];
			if( isset($a['created_by']) )
				$a['user'] = $users[$a['created_by']];
			if( isset($a['deleted_by']) )
				$a['user'] = $users[$a['deleted_by']];
		}

		return $array;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set uitzender
	 */
	public function uitzender( $uitzender_id ) :UserGroup
	{
		$this->_uitzender_id = intval($uitzender_id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set inlener
	 */
	public function inlener( $inlener_id ) :UserGroup
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle users ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();
		
		//start query
		$sql = "SELECT users.user_id, username, users_accounts.user_type, users_accounts.admin, naam, email, email_confirmed, users.timestamp, password, new_key_expires
				FROM users
				LEFT JOIN users_accounts ON users.user_id = users_accounts.user_id
				WHERE users.deleted = 0	AND users_accounts.werkgever_id = ".$this->user->werkgever_id." ";

		//alleen voor uitzender
		if( $this->_uitzender_id !== NULL )
			$sql .= " AND uitzender_id = $this->_uitzender_id ";
		
		//alleen voor inlener
		if( $this->_inlener_id !== NULL )
			$sql .= " AND inlener_id = $this->_inlener_id ";
		
		
		//order
		$sql .= " ORDER BY users.username";
		
		$query = $this->db_admin->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
		{
			//nooit wachtwoord meenemen
			if( $row['password'] !== NULL ) $row['password'] = 1;
			$data[$row['user_id']] = $row;
		}

		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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