<?php

namespace models\verloning;

use models\Connector;
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

class InvoerKm extends Invoer
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct( ?Invoer $invoer = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $invoer !== NULL )
			$this->copySettings( $invoer );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * gegevens overnemen van invoer object
	 *
	 * @return void
	 */
	public function copySettings( Invoer $invoer )
	{
		$this->setTijdvak( $invoer->tijdvakinfo() );
		
		if( $invoer->inlener() !== NULL )
			$this->setInlener( $invoer->inlener()  );
		
		if( $invoer->uitzender() !== NULL )
			$this->setUitzender( $invoer->uitzender()  );
		
		if( $invoer->werknemers() !== NULL )
			$this->setWerknemers( $invoer->werknemers() );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij opslaan
	 * TODO: betere controle, zit datum in periode? Max uren
	 */
	private function _validateInput( $row ): ?array
	{
		if( $row['opmerking_tekst'] == '' )
			$row['opmerking_tekst'] = NULL;
		
		//afronden
		$row['aantal'] = round( $row['aantal'],2 );
		$row['invoer_id'] = intval( $row['invoer_id'] );
		
		if( is_numeric($row['project_id']))
			$row['project_id'] = intval($row['project_id']);
		else
			$row['project_id'] = NULL;
		
		//$row['datum'] = reverseDate( $row['datum'] );
		
		return $row;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij ophalen
	 *
	 */
	public function getRow( $invoer_id ): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM invoer_kilometers WHERE invoer_id = ?", array( $invoer_id ) );
		return DBhelper::toRow( $query, 'NULL' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij opslaan
	 * TODO: fout afhandeling
	 */
	public function setRow( $row ): ?array
	{
		$tijdvakinfo = $this->tijdvakinfo();
		$row['datum'] = $tijdvakinfo['periode_start'];
		
		$set = $this->_validateInput( $row );
		$set['aantal_snelste'] = $set['aantal'];
		
		//nieuwe entry
		if( $set['invoer_id'] == 0 )
		{
			$set['uitzender_id'] = $this->_uitzender_id;
			$set['inlener_id'] = $this->_inlener_id;
			$set['zzp_id'] = $this->_zzp_id;
			$set['werknemer_id'] = $this->_werknemer_id;
			
			$this->db_user->insert( 'invoer_kilometers', $set );
			
			$set['invoer_id'] = $this->db_user->insert_id();
			
		}
		else
		{
			$oude_entry = $this->getRow( $set['invoer_id'] );
			
			$this->db_user->where( 'invoer_id', $set['invoer_id'] );
			$this->db_user->where( 'factuur_id', NULL );
			$this->db_user->where( 'werknemer_id', $this->_werknemer_id );
			$this->db_user->update( 'invoer_kilometers', $set );
			
			$this->_logKmActie( 'update', $oude_entry );
		}
		
		return $set;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Alle data voor periode verwijderen
	 *
	 */
	public function clearAll(): bool
	{
		if( $this->user->werkgever_type == 'uitzenden' )
		{
			$sql = "DELETE FROM invoer_kilometers WHERE factuur_id IS NULL AND werknemer_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?";
			$this->db_user->query( $sql, array( $this->_werknemer_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
			
		}
		
		if( $this->user->werkgever_type == 'bemiddeling' )
		{
			$sql = "DELETE FROM invoer_kilometers WHERE factuur_id IS NULL AND zzp_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?";
			$this->db_user->query( $sql, array( $this->_zzp_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
			
		}
		
		if( $this->db_user->affected_rows() != -1 )
		{
			$this->_logKmActie( 'clear' );
			return true;
		}
		
		$this->_error[] = 'Kilometers konden niet worden verwijderd';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij verwijderen
	 *
	 */
	public function delRow( $row ): bool
	{
		$oude_entry = $this->getRow( $row['invoer_id'] );
		$this->db_user->query( "DELETE FROM invoer_kilometers WHERE factuur_id IS NULL AND invoer_id = ? AND werknemer_id = ?", array( $row['invoer_id'], $this->_werknemer_id) );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			$this->_logKmActie( 'delete', $oude_entry );
			return true;
		}
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log actie
	 *
	 */
	private function _logKmActie( $action, $row = NULL )
	{
		
		$insert['user_id'] = $this->user->user_id;
		$insert['action'] = $action;
		
		if( $row !== NULL)
		{
			$insert['json'] = json_encode( $row );
			$insert['invoer_id'] = $row['invoer_id'];
		}
		
		@$this->db_user->insert( 'invoer_kilometers_log', $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * samenvatting ophalen voor overzicht
	 *
	 */
	public function getWerknemerKmSamenvatting() :?array
	{
		$totaalRows = $this->getWerknemersKilometerRijen();
		$km = NULL;
		
		if( $totaalRows === NULL )
			return $km;
		
		foreach( $totaalRows as $werknemer_id => $kmRows )
		{
			foreach( $kmRows as $row )
			{
				if( !isset($km[$werknemer_id][$row['doorbelasten']]) )
					$km[$werknemer_id][$row['doorbelasten']] = 0;
				
				$km[$werknemer_id][$row['doorbelasten']] += $row['aantal'];
			}
		}
		
		return $km;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle kilometers voor alle werknemers
	 *
	 */
	public function getWerknemersKilometerRijen()
	{
		$sql = "SELECT invoer_id, aantal, datum, project_id, project_tekst, locatie_tekst, opmerking_tekst, doorbelasten, locatie_van, locatie_naar, uitkeren, werknemer_id
				FROM invoer_kilometers WHERE werknemer_id IN (".array_keys_to_string($this->_werknemer_ids).") AND inlener_id = ? AND datum >= ? AND datum <= ?
				ORDER BY datum";
		
		$query = $this->db_user->query( $sql, array( $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['project_tekst'] === NULL ) $row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL ) $row['locatie_tekst'] = '';
			if( $row['opmerking_tekst'] === NULL ) $row['opmerking_tekst'] = '';
			
			$row['datum'] = reverseDate($row['datum']);
			
			$data[$row['werknemer_id']][$row['invoer_id']] = $row;
		}
		
		return $data;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle kilometers voor werknemer voor facturatie ophalen
	 *
	 */
	public function getWerknemerKilometerRijen()
	{
		$sql = "SELECT invoer_id, aantal, datum, project_id, project_tekst, locatie_tekst, opmerking_tekst, doorbelasten, locatie_van, locatie_naar, uitkeren
				FROM invoer_kilometers WHERE invoer_kilometers.factuur_id IS NULL AND werknemer_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?
				ORDER BY datum";
		
		$query = $this->db_user->query( $sql, array( $this->_werknemer_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['project_tekst'] === NULL ) $row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL ) $row['locatie_tekst'] = '';
			if( $row['opmerking_tekst'] === NULL ) $row['opmerking_tekst'] = '';
			
			$row['datum'] = reverseDate($row['datum']);
			
			$data[] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle kilometers voor werknemer voor facturatie ophalen
	 *
	 */
	public function getZzpKilometerRijen()
	{
		$sql = "SELECT invoer_id, aantal, datum, project_id, project_tekst, locatie_tekst, opmerking_tekst, doorbelasten, locatie_van, locatie_naar
				FROM invoer_kilometers WHERE invoer_kilometers.factuur_id IS NULL AND zzp_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?
				ORDER BY datum";
		
		$query = $this->db_user->query( $sql, array( $this->_zzp_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['project_tekst'] === NULL ) $row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL ) $row['locatie_tekst'] = '';
			if( $row['opmerking_tekst'] === NULL ) $row['opmerking_tekst'] = '';
			
			$row['datum'] = reverseDate($row['datum']);
			
			$data[] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle kilometers voor werknemer ophalen
	 *
	 */
	public function getWerknemerKilometers()
	{
		$sql = "SELECT invoer_id, aantal, datum, project_id, project_tekst, locatie_tekst, opmerking_tekst, doorbelasten, locatie_van, locatie_naar, uitkeren
				FROM invoer_kilometers WHERE werknemer_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?
				ORDER BY datum";
		
		$query = $this->db_user->query( $sql, array( $this->_werknemer_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['project_tekst'] === NULL ) $row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL ) $row['locatie_tekst'] = '';
			if( $row['opmerking_tekst'] === NULL ) $row['opmerking_tekst'] = '';
			
			$row['datum'] = reverseDate($row['datum']);
			
			$data[] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle kilometers voor werknemer ophalen
	 *
	 */
	public function getZzpKilometers()
	{
		$sql = "SELECT invoer_id, aantal, datum, project_id, project_tekst, locatie_tekst, opmerking_tekst, doorbelasten, locatie_van, locatie_naar
				FROM invoer_kilometers WHERE zzp_id = ? AND inlener_id = ? AND datum >= ? AND datum <= ?
				ORDER BY datum";
		
		$query = $this->db_user->query( $sql, array( $this->_zzp_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['project_tekst'] === NULL ) $row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL ) $row['locatie_tekst'] = '';
			if( $row['opmerking_tekst'] === NULL ) $row['opmerking_tekst'] = '';
			
			$row['datum'] = reverseDate($row['datum']);
			
			$data[] = $row;
		}
		
		return $data;
	}
}

?>