<?php

namespace models\werknemers;

use models\Connector;
use models\inleners\Inlener;
use models\utils\DBhelper;
use models\verloning\Urentypes;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
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
	private $_error;
	
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
	 * factor plaatsing aanpassen
	 *
	 */
	public function setFactor( $factor_id ) :bool
	{
		$update['factor_id'] = intval($factor_id);
		
		$this->db_user->where( 'plaatsing_id', $this->_plaatsing_id );
		$this->db_user->update( 'werknemers_inleners', $update );
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get details
	 */
	public function details() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM werknemers_inleners WHERE plaatsing_id = $this->_plaatsing_id" );
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
		$this->db_user->query( "UPDATE werknemers_inleners SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND plaatsing_id = $this->_plaatsing_id" );
		
		//urentypes weghalen bij werknemer
		if( $this->db_user->affected_rows() > 0 )
		{
			$urentypes = new Urentypes();
			$urentypes->deleteUrentypesWerknemerForInlener( $plaatsing['werknemer_id'], $plaatsing['inlener_id'] );
		}
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * add plaatsing
	 * TODO: validate input
	 */
	public function add( $data )
	{
		//gen dubbele plaatsing
		$query = $this->db_user->query( "SELECT plaatsing_id FROM werknemers_inleners WHERE werknemer_id = ? AND inlener_id = ? AND deleted = 0", array( intval($data['werknemer_id']), intval($data['inlener_id']) ) );
		if( $query->num_rows() > 0 )
		{
			$this->_error[] = 'Werknemer is al geplaatst bij inlener';
			return false;
		}
		
		//factor ophalen
		$inlener = new Inlener($data['inlener_id']);
		$standaardfactor =  $inlener->standaardfactor();
		
		$input['factor_id'] = $standaardfactor['factor_id'];
		
		$input['werknemer_id'] = $data['werknemer_id'];
		$input['inlener_id'] = $data['inlener_id'];
		$input['cao_id_intern'] = $data['cao_id'];
		$input['loontabel_id_intern'] = $data['loontabel_id'];
		$input['job_id_intern'] = $data['job_id'];
		$input['schaal'] = $data['schaal_id'] ?? null;
		$input['periodiek'] = $data['periodiek_id'] ?? null;
		$input['bruto_loon'] = prepareAmountForDatabase($data['brutoloon']);
		$input['start_plaatsing'] = reverseDate($data['start_plaatsing']);
		
		
		
		$this->db_user->insert( 'werknemers_inleners', $input );
		
		//bruto uurloon naar de standaard tabel
		
		
		//als het gelukt is dan uretypes koppelen
		if( $this->db_user->insert_id() > 0 )
		{
			$urentypes = new Urentypes();
			$urentypes->addUrentypesWerknemerForInlener($this->db_user->insert_id(), $input['werknemer_id'], $input['inlener_id'] );
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