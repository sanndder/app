<?php

namespace models\zzp;

use models\Connector;
use models\utils\DBhelper;
use models\verloning\Urentypes;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Plaatsing ZZP'er class
 *
 *
 *
 */

class Plaatsing extends Connector
{
	/**
	 * @var int
	 */
	private $_plaatsing_id;
	private $_zzp_id;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $plaatsing_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		if( $plaatsing_id !== NULL )
			$this->setID( $plaatsing_id );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($plaatsing_id)
	{
		$this->_plaatsing_id = intval($plaatsing_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get details
	 */
	public function details() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM zzp_inleners WHERE plaatsing_id = $this->_plaatsing_id" );
		return DBhelper::toRow( $query, 'NULL' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete
	 */
	public function delete()
	{
		$plaatsing = $this->details();
		
		//plaatsing weghalen
		$this->db_user->query( "UPDATE zzp_inleners SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND plaatsing_id = $this->_plaatsing_id" );
		
		//urentypes weghalen bij zzp'er
		if( $this->db_user->affected_rows() > 0 )
		{
			$urentypes = new Urentypes();
			$urentypes->deleteUrentypesZzpForInlener( $plaatsing['zzp_id'], $plaatsing['inlener_id'] );
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set ZZP ID
	 *
	 */
	public function zzpId( $id )
	{
		$this->_zzp_id = intval($id);
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * add plaatsing
	 * TODO: validate input
	 */
	public function add( $data )
	{
		//gen dubbele plaatsing
		$query = $this->db_user->query( "SELECT plaatsing_id FROM zzp_inleners WHERE zzp_id = ? AND inlener_id = ? AND deleted = 0", array( $this->_zzp_id, intval($data['inlener_id']) ) );
		if( $query->num_rows() > 0 )
		{
			$this->_error[] = "ZZP'er is al geplaatst bij inlener";
			return false;
		}
		
		$input['zzp_id'] = $this->_zzp_id;
		$input['inlener_id'] = intval($data['inlener_id']);
		$input['start_plaatsing'] = reverseDate($data['start_plaatsing']);
		
		$this->db_user->insert( 'zzp_inleners', $input );

		//als het gelukt is dan uretypes koppelen
		if( $this->db_user->insert_id() > 0 )
		{
			$urentypes = new Urentypes();
			$urentypes->addUrentypesZzpForInlener($this->db_user->insert_id(), $this->_zzp_id, $data['inlener_id'] );
		}
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