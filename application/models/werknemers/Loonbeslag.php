<?php

namespace models\werknemers;

use models\cao\CAOGroup;
use models\Connector;
use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\inleners\Inlener;
use models\utils\DBhelper;
use models\verloning\Urentypes;
use models\verloning\UrentypesGroup;
use models\verloning\Vergoeding;
use models\verloning\VergoedingGroup;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class Loonbeslag extends Connector
{
	/**
	 * @var int
	 */
	private $_werknemer_id;
	private $_loonbeslag_id;
	private $_error;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $loonbeslag_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		if( $loonbeslag_id !== NULL )
			$this->setID( $loonbeslag_id );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($loonbeslag_id)
	{
		$this->_loonbeslag_id = intval($loonbeslag_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get ID
	 */
	public function id()
	{
		return $this->_loonbeslag_id;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factor plaatsing aanpassen
	 *
	 */
	public function setWerknemerID( $werknemer_id )
	{
		$this->_werknemer_id = intval($werknemer_id);
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get details
	 */
	public function details() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM werknemers_loonbeslag WHERE loonbeslag_id = $this->_loonbeslag_id AND deleted = 0" );
		return DBhelper::toRow( $query, 'NULL' );
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete
	 */
	public function delete() :bool
	{
		//loonbeslag verwijderen
		$this->db_user->query( "UPDATE werknemers_loonbeslag SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND loonbeslag_id = $this->_loonbeslag_id" );
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * add loonbeslag
	 *
	 */
	public function add( $data )
	{
		//er moet een werknemer ingesteld zijn
		if( $this->_werknemer_id === NULL )
		{
			$this->_error[] = 'Geen werknemer ingesteld';
			return false;
		}
		
		//check
		if( strlen(trim($data['beslaglegger'])) < 2 )
		{
			$this->_error[] = 'Geen beslaglegger';
			return false;
		}
		
		if( strlen(trim($data['dossiernummer'])) < 3 )
		{
			$this->_error[] = 'Geen dossiernummer';
			return false;
		}
		
		$insert['loonbeslag_id'] = $this->lastID( 'werknemers_loonbeslag', 'loonbeslag_id' ) + 1;
		$insert['beslaglegger'] = trim($data['beslaglegger']);
		$insert['dossiernummer'] = trim($data['dossiernummer']);
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'werknemers_loonbeslag', $insert );
		
		if( $this->db_user->insert_id() > 0 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
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