<?php

namespace models\utils;

use models\Connector;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Visited class
 * Houdt laatst bezoche pagina's bij
 *
 *
 */

class VisitsLogger extends Connector
{
	
	/*
	 * @var array
	 * array met tabelnamen
	 */
	private $_table = array();
	
	/*
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
		
		//fill array
		$this->_table['uitzender'] = 'uitzenders_last_visited';
		$this->_table['inlener'] = 'inleners_last_visited';
		$this->_table['werknemer'] = 'werknemers_last_visited';
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * CRM log visit
	 *
	 */
	public function logCRMVisit( $type, $id )
	{
		
		if( $type == 'uitzender' )
			$insert['uitzender_id'] = intval( $id );
		if( $type == 'inlener' )
			$insert['inlener_id'] = intval( $id );
		if( $type == 'werknemer' )
			$insert['werknemer_id'] = intval( $id );
		
		$insert['user_id'] = $this->user->user_id;
		
		//delete previous entries
		$sql = "DELETE FROM ".$this->_table[$type]." WHERE ".key($insert)." = ".current($insert)." AND user_id = ".$this->user->user_id;
		$this->db_user->query( $sql );
		
		//now insert
		$this->db_user->insert( $this->_table[$type], $insert );
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cleanup table
	 *
	 */
	public function getLastCRMVisits( $type )
	{
		if($type == 'uitzender' )
			$sql = "SELECT uitzenders_last_visited.*, uitzenders_bedrijfsgegevens.bedrijfsnaam FROM uitzenders_last_visited LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_last_visited.uitzender_id
					WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND uitzenders_last_visited.user_id = ".$this->user->user_id." ORDER BY timestamp DESC LIMIT 50";
		if($type == 'inlener' )
			$sql = "SELECT inleners_last_visited.*, inleners_bedrijfsgegevens.bedrijfsnaam FROM inleners_last_visited LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_last_visited.inlener_id
					WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_last_visited.user_id = ".$this->user->user_id." ORDER BY timestamp DESC LIMIT 50";
		if($type == 'werknemer' )
			$sql = "SELECT werknemers_last_visited.*, wg.achternaam, wg.voorletters, wg.voornaam, wg.tussenvoegsel FROM werknemers_last_visited LEFT JOIN werknemers_gegevens wg ON werknemers_last_visited.werknemer_id = wg.werknemer_id
					WHERE wg.deleted = 0 AND werknemers_last_visited.user_id = ".$this->user->user_id." ORDER BY timestamp DESC LIMIT 50";
		
		$query = $this->db_user->query( $sql );
		
		$array = DBhelper::toArray($query, NULL, 'array' );
		
		//cleanup every 50 lookups
		if( count($array) > 50 )
			$this->_cleanupVisitsTable( $type );
		
		return array_slice($array, 0, 8);
		
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cleanup table
	 *
	 */
	private function _cleanupVisitsTable( $type )
	{
		$sql = "DELETE FROM ".$this->_table[$type]." WHERE user_id = ".$this->user->user_id." ORDER BY timestamp ASC LIMIT 41 ";
		$this->db_user->query( $sql );
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 *
	 */
	public function data()
	{
		$sql = "SELECT $this->_table.user_id, $this->_table.timestamp, $this->_table.* FROM $this->_table WHERE ";
		
		//indexes
		foreach( $this->_index as $field => $value )
			$sql .= " $field = ".intval($value)." AND";

		//delete last "and"
		$sql = substr($sql,0,-4);
		
		//order
		$sql .= " ORDER BY timestamp DESC";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			unset($row['id']);
			unset($row['deleted']);
			unset($row['deleted_on']);
			unset($row['deleted_by']);
			unset($row['inlener_id']);
			
			$data[] = $row;
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