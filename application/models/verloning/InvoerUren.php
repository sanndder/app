<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Object voor afhandelen uurinvoer
 *
 *
 */

class InvoerUren extends Invoer
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Rij opslaan
	 * TODO: betere controle, zit datum in periode? Max uren
	 */
	private function _validateInput( $row ): ?array
	{
		if( $row['project_tekst'] == '' )
			$row['project_tekst'] = NULL;
		if( $row['locatie_tekst'] == '' )
			$row['locatie_tekst'] = NULL;
		
		//omzetten naar decimaal
		if( strpos( $row['aantal'], ':' ) !== false )
			$row['aantal'] = h2d( $row['aantal'] );
		
		$row['invoer_id'] = intval( $row['invoer_id'] );
		$row['datum'] = reverseDate( $row['datum'] );
		$row['uren_type_id_werknemer'] = $row['urentype_id'];
		unset( $row['urentype_id'] );
		
		return $row;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Rij ophalen
	 *
	 */
	public function getRow( $invoer_id ): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM invoer_uren WHERE invoer_id = ?", array( $invoer_id ) );
		return DBhelper::toRow( $query, 'NULL' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Rij opslaan
	 * TODO: fout afhandeling
	 */
	public function setRow( $row ): ?array
	{
		$set = $this->_validateInput( $row );
		
		//nieuwe entry
		if( $set['invoer_id'] == 0 )
		{
			$plaatsingGroup = new PlaatsingGroup();
			$plaatsingen = $plaatsingGroup->werknemer( $this->_werknemer_id )->inlener( $this->_inlener_id )->all();
			
			$set['uitzender_id'] = $this->_uitzender_id;
			$set['inlener_id'] = $this->_inlener_id;
			$set['zzp_id'] = $this->_zzp_id;
			$set['werknemer_id'] = $this->_werknemer_id;
			$set['plaatsing_id'] = key( $plaatsingen );
			
			$this->db_user->insert( 'invoer_uren', $set );
			
			$set['invoer_id'] = $this->db_user->insert_id();
			
		} else
		{
			$oude_entry = $this->getRow( $set['invoer_id'] );
			
			$this->db_user->where( 'invoer_id', $set['invoer_id'] );
			$this->db_user->where( 'werknemer_id', $this->_werknemer_id );
			$this->db_user->update( 'invoer_uren', $set );
			
			$this->_logUrenActie( 'update', $oude_entry );
		}
		
		return $set;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Rij verwijderen
	 *
	 */
	public function delRow( $row ): bool
	{
		$oude_entry = $this->getRow( $row['invoer_id'] );
		$this->db_user->query( "DELETE FROM invoer_uren WHERE invoer_id = ? AND werknemer_id = ?", array( $row['invoer_id'], $this->_werknemer_id) );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			$this->_logUrenActie( 'delete', $oude_entry );
			return true;
		}
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Log actie
	 *
	 */
	private function _logUrenActie( $action, $row )
	{
		
		$insert['user_id'] = $this->user->user_id;
		$insert['json'] = json_encode( $row );
		$insert['action'] = $action;
		$insert['invoer_id'] = $row['invoer_id'];
		
		@$this->db_user->insert( 'invoer_uren_log', $insert );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Urenmatrix opbouwen voor invoer
	 *
	 */
	public function urenMatrix()
	{
		//eerst lege matrix opbouwen
		foreach( $this->_periode_dagen as $datum )
		{
			$matrix[$datum]['datum'] = reverseDate( $datum );
			$matrix[$datum]['week'] = Tijdvak::weeknr( $datum );
			$matrix[$datum]['dag'] = Tijdvak::dagAfkorting( $datum );
			$matrix[$datum]['class'] = '';
			if( Tijdvak::isWeekend( $datum ) )
				$matrix[$datum]['class'] = 'tr-weekend';
		}
		
		//uren ophalen
		$uren = $this->getWerknemerUren();
		
		if($uren === NULL )
			return $matrix;
		
		foreach( $uren as $key => $row )
		{
			$matrix[$row['datum']]['rows'][$key] = $row;
		}
		
		//dummy uren
		/*
		$matrix['2020-01-06']['rows'][1]['invoer_id'] = 1;
		$matrix['2020-01-06']['rows'][1]['aantal'] = 8;
		$matrix['2020-01-06']['rows'][1]['urentype_id'] = 10;
		$matrix['2020-01-06']['rows'][1]['project_tekst'] = 'Afbouw';
		$matrix['2020-01-06']['rows'][1]['locatie_tekst'] = 'Den Helder';
		
		$matrix['2020-01-06']['rows'][2]['invoer_id'] = 2;
		$matrix['2020-01-06']['rows'][2]['aantal'] = '2:30';
		$matrix['2020-01-06']['rows'][2]['urentype_id'] = 11;
		$matrix['2020-01-06']['rows'][2]['project_tekst'] = 'Timmerwerk';
		$matrix['2020-01-06']['rows'][2]['locatie_tekst'] = 'Amsterdam';*/
		
		return $matrix;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * alle uren voor werknemer ophalen
	 *
	 */
	public function getWerknemerUren()
	{
		$sql = "SELECT invoer_id, aantal, uren_type_id_werknemer, datum, project_id, project_tekst, locatie_tekst, uitkeren
				FROM invoer_uren WHERE werknemer_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?";
		
		$query = $this->db_user->query( $sql, array( $this->_werknemer_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['urentype_id'] = $row['uren_type_id_werknemer'];
			
			if( $row['project_tekst'] === NULL ) $row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL ) $row['locatie_tekst'] = '';
			
			$row['decimaal'] = $row['aantal'];
			$row['aantal'] = d2h($row['aantal']);
			
			$data[$row['invoer_id']] = $row;
		}
		
		return $data;
	}
}

?>