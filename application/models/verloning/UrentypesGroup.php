<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class UrentypesGroup extends Connector
{

	/*
	 * @var array
	 */
	private $_error = NULL;
	
	/**
	 * @var int
	 */
	private $_inlener_id;
	/**
	 * @var int
	 */
	private $_werknemer_id;
	
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
	 * set inlener id
	 *
	 */
	public function inlener( $inlener_id )
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function werknemers( $werknemer_ids )
	{
		$this->_werknemer_ids = $werknemer_ids;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function urentypesWerknemer( $werknemer_id ) :array
	{
		$sql = "SELECT werknemers_urentypes.id, inleners_urentypes.doorbelasten_uitzender, inleners_urentypes.label, urentypes.naam, urentypes.percentage, urentypes_categorien.naam AS categorie
				FROM werknemers_urentypes
				LEFT JOIN inleners_urentypes ON inleners_urentypes.inlener_urentype_id = werknemers_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien on urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				WHERE werknemers_urentypes.deleted = 0 AND werknemers_urentypes.werknemer_id = $werknemer_id AND werknemers_urentypes.urentype_active= 1
				ORDER BY urentypes.urentype_categorie_id, inleners_urentypes.inlener_urentype_id, urentypes.percentage";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			$data[$row['id']] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uretypes voor inlener ophalen
	 *
	 */
	public function urentypesInlener()
	{
		$sql = "SELECT inleners_urentypes.*, urentypes.naam, urentypes.percentage, urentypes_categorien.naam AS categorie
				FROM inleners_urentypes
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien on urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				WHERE inleners_urentypes.deleted = 0 AND inlener_id = $this->_inlener_id
				ORDER BY urentypes.urentype_categorie_id, inleners_urentypes.inlener_urentype_id, urentypes.percentage";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'inlener_urentype_id' );

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get matrix
	 *
	 */
	public function getUrentypeWerknemerMatrix()
	{
		//alle urentypes voor inlener ophalen
		$urentypes = $this->urentypesInlener();

		//alle werknemers ophalen
		$sql = "SELECT werknemers_urentypes.id, werknemers_urentypes.urentype_active, werknemers_urentypes.werknemer_id, werknemers_urentypes.verkooptarief, werknemers_urentypes.urentype_id, werknemers_urentypes.plaatsing_id,
       				   werknemers_urentypes.inlener_urentype_id, werknemers_gegevens.achternaam, werknemers_gegevens.voornaam, werknemers_gegevens.voorletters, werknemers_gegevens.tussenvoegsel, werknemers_inleners.bruto_loon
				FROM werknemers_urentypes
				LEFT JOIN werknemers_gegevens ON werknemers_urentypes.werknemer_id = werknemers_gegevens.werknemer_id
				LEFT JOIN werknemers_status ON werknemers_urentypes.werknemer_id = werknemers_status.werknemer_id
				LEFT JOIN werknemers_inleners ON (werknemers_urentypes.plaatsing_id = werknemers_inleners.plaatsing_id  )
				WHERE werknemers_urentypes.inlener_id = $this->_inlener_id AND werknemers_gegevens.deleted = 0 AND werknemers_urentypes.deleted = 0 AND werknemers_inleners.deleted = 0
				AND werknemers_status.archief = 0
				ORDER BY achternaam, plaatsing_id
				";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return $urentypes;
		
		foreach( $query->result_array() as $row )
		{
			$row['werknemer_naam'] = make_name( $row );
			
			$urentypes[$row['inlener_urentype_id']]['werknemers'][] = $row;
		}
		
		return $urentypes;
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