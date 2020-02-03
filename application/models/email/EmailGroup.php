<?php

namespace models\email;

use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Uitzender Lists
 *
 * Alle lijsten van uitzenders moeten via deze class
 *
 */
class EmailGroup extends Connector {


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
	 * Haal emails op
	 * @return array|null
	 */
	public function get( string $folder ) :?array
	{
		$data = array();
		
		$sql = "SELECT * FROM emails";
		
		//nog te verzenden
		if( $folder == '' )
			$sql .= " WHERE deleted = 0 AND send = 0";
		
		//verzonden
		if( $folder == 'send' )
			$sql .= " WHERE deleted = 0 AND send = 1";
		
		//verwijderd
		if( $folder == 'trash' )
			$sql .= " WHERE deleted = 1";
		
		$sql .= " ORDER BY send_on DESC LIMIT 100";
		
		$query = $this->db_user->query( $sql );
		
		//email array
		foreach( $query->result_array() as $row )
		{
			$row['body'] = strip_tags($row['body']);
			$data[$row['email_id']] = $row;
		}
		
		if( count($data) > 0 )
		{
			//geadresseerden er bij halen
			$sql = "SELECT * FROM emails_recipients WHERE email_id IN (" . array_keys_to_string( $data ) . ")";
			$query = $this->db_user->query( $sql );
			
			foreach( $query->result_array() as $row )
			{
				$data[$row['email_id']]['recipients'][] = $row;
			}
			
			//bijlages er bij halen
			$sql = "SELECT * FROM emails_attachments WHERE email_id IN (" . array_keys_to_string( $data ) . ")";
			$query = $this->db_user->query( $sql );
			
			foreach( $query->result_array() as $row )
			{
				$data[$row['email_id']]['attachments'][] = $row;
			}
		}
		
		//show($data);
		return $data;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array| boolean
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