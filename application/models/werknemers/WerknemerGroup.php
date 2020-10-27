<?php

namespace models\werknemers;

use models\Connector;
use models\users\UserGroup;
use models\utils\Codering;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Werknemer Lists
 *
 * Alle lijsten van werknemers moeten via deze class
 *
 */
class WerknemerGroup extends Connector {
	
	/*
	 * @var array met werknemer ID's
	 */
	private $_werknemer_ids = array();
	
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
	 * list werknemers for inlener
	 *
	 * @return array | bool
	 */
	static function inlener( $inlener_id )
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT werknemers_gegevens.werknemer_id, achternaam, voorletters, voornaam, tussenvoegsel
				FROM werknemers_inleners
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_inleners.werknemer_id
				WHERE werknemers_inleners.inlener_id = $inlener_id
				AND werknemers_gegevens.deleted = 0 AND werknemers_inleners.deleted = 0
				ORDER BY achternaam";
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['werknemer_id']] = make_name($row);
		
		return $data;
	}



	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * welke ID's naar array
	 *
	 */
	public function setWerknemers( array $ids ) :WerknemerGroup
	{
		$this->_werknemer_ids = array_keys($ids);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle werknemers tellen
	 */
	public function count()
	{
		$sql = "SELECT COUNT(werknemers_status.werknemer_id) AS count 
				FROM werknemers_status 
				LEFT JOIN werknemers_uitzenders ON werknemers_status.werknemer_id = werknemers_uitzenders.werknemer_id
				WHERE werknemers_uitzenders.deleted = 0 AND werknemers_status.complete = 1 AND werknemers_status.archief = 0 ";

		if( $this->user->user_type == 'uitzender')
			$sql .= " AND werknemers_uitzenders.uitzender_id = ". $this->uitzender->id;

		$query = $this->db_user->query( $sql );

		$data = DBhelper::toRow( $query );
		return $data['count'];
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * welke stipp versie
	 *
	 */
	public function pensioen() :array
	{
		$pensioen = array();
		
		if(count($this->_werknemer_ids) > 0 )
		{
			$query = $this->db_user->query( "SELECT werknemer_id, stipp FROM werknemers_pensioen WHERE deleted = 0 AND werknemer_id IN (" . implode( ',', $this->_werknemer_ids ) . ")" );
			
			foreach( $query->result_array() as $row )
			{
				$pensioen[$row['werknemer_id']]['stipp'] = $row['stipp'];
			}
		}
		return $pensioen;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * welke stipp versie
	 *
	 */
	public function zorgverzekering() :?array
	{
		$sql = "SELECT werknemers_verloning_instellingen.inhouden_zorgverzekering, werknemers_verloning_instellingen.werknemer_id, werknemers_gegevens.*, werknemers_dienstverband_duur.indienst
				FROM werknemers_verloning_instellingen
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_verloning_instellingen.werknemer_id
				LEFT JOIN werknemers_dienstverband_duur ON werknemers_dienstverband_duur.werknemer_id = werknemers_verloning_instellingen.werknemer_id
				WHERE inhouden_zorgverzekering = 1 AND werknemers_verloning_instellingen.deleted = 0
				";
		$query = $this->db_user->query( $sql );

		if( $query->num_rows() == 0 )
			return NULL;
		
		$nat = Codering::listNationaliteiten();
		$land = Codering::listLanden();
		$codes = Codering::listLandCodes();

		foreach( $query->result_array() as $row )
		{
			$row['nationaltieit'] = !is_null($row['nationaltieit_id']) ? $nat[sprintf("%04d", $row['nationaltieit_id'])] : NULL;
			$row['land'] = !is_null($row['woonland_id']) ? $land[$row['woonland_id']] : NULL;
			$row['land_code'] =  !is_null($row['woonland_id']) ? $codes[$row['woonland_id']] : NULL;
			
			$data[$row['werknemer_id']] = $row;
		}
		
		show($data);
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle werknemers ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();

		//start query
		$sql = "SELECT werknemers_status.*, werknemers_gegevens.*,
       					werknemers_uitzenders.uitzender_id,
       					werknemers_inleners.inlener_id, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender
				FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_status.werknemer_id
				LEFT JOIN werknemers_uitzenders ON werknemers_status.werknemer_id = werknemers_uitzenders.werknemer_id
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = werknemers_uitzenders.uitzender_id
				LEFT JOIN werknemers_inleners ON werknemers_status.werknemer_id = werknemers_inleners.werknemer_id
				WHERE werknemers_gegevens.deleted = 0 AND (uitzenders_bedrijfsgegevens.deleted = 0 OR uitzenders_bedrijfsgegevens.deleted IS NULL)
				  AND (werknemers_uitzenders.deleted = 0 OR werknemers_uitzenders.deleted IS NULL )
				  AND (werknemers_inleners.deleted = 0 OR werknemers_inleners.deleted IS NULL  )
				";
		
		//beveiligen
		if( $this->user->user_type == 'external' )
			die('Geen toegand');
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND werknemers_uitzenders.uitzender_id = ".$this->uitzender->id." ";
		
		//beveiligen
		if( $this->user->user_type == 'inlener' )
			$sql .= " AND werknemers_inleners.inlener_id = ".$this->inlener->id." ";

		//archief ook?
		if( isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND werknemers_status.archief = 0";

		if( !isset($param['actief']) && isset($param['archief']) )
			$sql .= " AND werknemers_status.archief = 1";

		//default
		if( !isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND werknemers_status.archief = 0";

		//zoeken, q1 is voor ID en naam, q2 is voor overig
		if( isset($param['q1']) && $param['q1'] != '' )
			$sql .= " AND (werknemers_gegevens.achternaam LIKE '%". addslashes($_GET['q1'])."%' OR werknemers_gegevens.voornaam LIKE '%". addslashes($_GET['q1'])."%' OR  werknemers_status.werknemer_id LIKE '%". addslashes($_GET['q1'])."%' ) ";
		
		//zoeken, q1 is voor ID en naam, q2 is voor overig
		if( isset($param['q2']) && $param['q2'] != '' )
			$sql .= " AND (werknemers_gegevens.bsn LIKE '%". addslashes($_GET['q2'])."%' OR werknemers_gegevens.mobiel LIKE '%". addslashes($_GET['q2'])."%' OR werknemers_gegevens.bsn LIKE '%". addslashes($_GET['q2'])."%'
			 OR  werknemers_gegevens.iban LIKE '%". addslashes($_GET['q2'])."%' OR werknemers_gegevens.telefoon LIKE '%". addslashes($_GET['q2'])."%' OR werknemers_gegevens.email LIKE '%". addslashes($_GET['q2'])."%' ) ";
		
		
		//specifieke uitzender?
		if( isset($param['uitzender_id']) && $param['uitzender_id'] != '' )
			$sql .= " AND werknemers_uitzenders.uitzender_id = ".intval($param['uitzender_id'])." ";

		//specifieke inlener?
		if( isset($param['inlener_id']) )
			$sql .= " AND werknemers_inleners.inlener_id = ".intval($param['inlener_id'])." ";
		
		//nieuw?
		if( isset($param['new']) )
			$sql .= " AND werknemers_status.complete = 0 ";
		
		//group
			$sql .= " GROUP BY werknemers_status.werknemer_id";

		//go
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$data[$row['werknemer_id']] = $row;
		}
		
		//plaatsingen erbij
		$sql = "SELECT werknemers_inleners.werknemer_id, werknemers_inleners.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam
				FROM werknemers_inleners
    			LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = werknemers_inleners.inlener_id
				WHERE werknemers_inleners.deleted = 0 AND werknemer_id IN (".array_keys_to_string($data).")";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
				$data[$row['werknemer_id']]['inleners'][$row['inlener_id']] = $row['bedrijfsnaam'];
		}
		
		//kijken of uitzender users heeft
		$users = UserGroup::listUsertypeID('werknemer', array_keys($data));
		
		foreach ( $users as $user )
			$data[$user]['user'] = 1;
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Shortcut naar all met parameters
	 */
	public function new()
	{
		return $this->all( array('new' => true) );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * TEMP
	 */
	public function copy()
	{
		$sql = "SELECT * FROM werknemers_info";
		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$update['werknemer_id'] = $row['werknemer_id'];
			$update['archief'] = 0;
			$update['complete'] = 1;
			$update['info_complete'] = 1;
			$update['email_complete'] = 1;
			$update['handtekening_complete'] = 1;

			$this->db_user->insert('werknemers_status', $update);
		}

	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array | bool
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