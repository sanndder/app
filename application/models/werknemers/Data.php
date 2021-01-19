<?php

namespace models\werknemers;

use models\Connector;


if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class Data extends Connector
{
	private $_werknemers = NULL;
	private $_werknemer_ids = NULL;
	
	private $_error;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $plaatsing_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 *
	 */
	public function stamgegevens() :void
	{
		$sql = "SELECT werknemers_status.archief, werknemers_gegevens.*, werknemers_dienstverband_duur.*, werknemers_verloning_instellingen.*
				FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_status.werknemer_id = werknemers_gegevens.werknemer_id
				LEFT JOIN werknemers_dienstverband_duur ON werknemers_status.werknemer_id = werknemers_dienstverband_duur.werknemer_id
				LEFT JOIN werknemers_verloning_instellingen ON werknemers_status.werknemer_id = werknemers_verloning_instellingen.werknemer_id
				WHERE werknemers_gegevens.deleted = 0
				AND werknemers_status.archief = 0
				AND werknemers_dienstverband_duur.deleted = 0
				AND werknemers_verloning_instellingen.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return;
		
		foreach( $query->result_array() as $row )
		{
			$row['naam'] = make_name($row);
			$this->_werknemers[$row['werknemer_id']] = $row;
		}
		
		$this->_werknemer_ids = array_keys_to_string($this->_werknemers);
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get laatste werkdag
	 *
	 */
	public function laatstewerkweek() :void
	{
		$sql = "SELECT werknemer_id, MAX(datum) AS laatst_gewerkt, WEEK(MAX(datum),3) AS laatste_werkweek FROM invoer_uren WHERE factuur_id IS NOT NULL GROUP BY werknemer_id";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return;
		
		foreach( $query->result_array() as $row )
		{
			if(isset($this->_werknemers[$row['werknemer_id']]))
			{
				$this->_werknemers[$row['werknemer_id']]['laatst_gewerkt'] = $row['laatst_gewerkt'];
				$this->_werknemers[$row['werknemer_id']]['laatste_werkweek'] = $row['laatste_werkweek'];
			}
		}
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get array
	 *
	 */
	public function werknemers() :?array
	{
		//lijst met standaard gegevens
		$this->stamgegevens();
		$this->laatstewerkweek();
		
		if(isset($_GET['s']))
			show($this->_werknemers);
		
		return $this->_werknemers;
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