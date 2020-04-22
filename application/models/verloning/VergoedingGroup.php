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

class VergoedingGroup extends Connector
{

	/*
	 * @var array
	 */
	private $_error = NULL;
	
	/**
	 * @var int
	 */
	private $_inlener_id;
	private $_werknemer_id;
	private $_zzp_id;
	/**
	 * @var int
	 */
	private $_werknemer_ids;
	
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
	public function inlener( $inlener_id ) :VergoedingGroup
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function werknemers( $werknemer_ids ) :VergoedingGroup
	{
		$this->_werknemer_ids = $werknemer_ids;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function werknemer( $id ) :VergoedingGroup
	{
		$this->_werknemer_id = intval($id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer id
	 *
	 */
	public function zzp( $id ) :VergoedingGroup
	{
		$this->_zzp_id = intval($id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * alle vergoedingen ophalen
	 *
	 */
	public function all()
	{
		$sql = "SELECT * FROM vergoedingen WHERE deleted = 0 ORDER BY naam";
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'vergoeding_id' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vergoedingen voor werknemer ophalen
	 *
	 */
	public function vergoedingenWerknemer()
	{
		$sql = "SELECT werknemers_vergoedingen.id, inleners_vergoedingen.vergoeding_type, werknemers_vergoedingen.inlener_id, inleners_vergoedingen.bedrag_per_uur, inleners_vergoedingen.doorbelasten, inleners_vergoedingen.uitkeren_werknemer, inleners_vergoedingen.label
   				, vergoedingen.naam, vergoedingen.belast
				FROM werknemers_vergoedingen
				LEFT JOIN inleners_vergoedingen ON inleners_vergoedingen.inlener_vergoeding_id = werknemers_vergoedingen.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON inleners_vergoedingen.vergoeding_id = vergoedingen.vergoeding_id
				WHERE werknemers_vergoedingen.inlener_id = $this->_inlener_id AND inleners_vergoedingen.deleted = 0 AND werknemers_vergoedingen.deleted = 0 AND  werknemers_vergoedingen.vergoeding_active = 1
				AND werknemers_vergoedingen.werknemer_id = $this->_werknemer_id
				ORDER BY vergoedingen.naam";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'id', 'array' );
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vergoedingen voor zzper ophalen
	 *
	 */
	public function vergoedingenZzp()
	{
		$sql = "SELECT zzp_vergoedingen.id, inleners_vergoedingen.vergoeding_type, zzp_vergoedingen.inlener_id, inleners_vergoedingen.bedrag_per_uur, inleners_vergoedingen.doorbelasten, inleners_vergoedingen.uitkeren_werknemer, inleners_vergoedingen.label
   				, vergoedingen.naam, vergoedingen.belast
				FROM zzp_vergoedingen
				LEFT JOIN inleners_vergoedingen ON inleners_vergoedingen.inlener_vergoeding_id = zzp_vergoedingen.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON inleners_vergoedingen.vergoeding_id = vergoedingen.vergoeding_id
				WHERE zzp_vergoedingen.inlener_id = $this->_inlener_id AND inleners_vergoedingen.deleted = 0 AND zzp_vergoedingen.deleted = 0 AND  zzp_vergoedingen.vergoeding_active = 1
				AND zzp_vergoedingen.zzp_id = $this->_zzp_id
				ORDER BY vergoedingen.naam";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'id', 'array' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vergoedingen voor inlener ophalen
	 *
	 */
	public function vergoedingenInlener()
	{
		$sql = "SELECT inleners_vergoedingen.*, vergoedingen.naam, vergoedingen.belast
				FROM inleners_vergoedingen
				LEFT JOIN vergoedingen ON inleners_vergoedingen.vergoeding_id = vergoedingen.vergoeding_id
				WHERE inleners_vergoedingen.deleted = 0 AND inlener_id = $this->_inlener_id
				ORDER BY vergoedingen.naam";
		
		$query = $this->db_user->query( $sql );

		return DBhelper::toArray( $query, 'inlener_vergoeding_id' );
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add vergoedingen bij nieuwe koppeling
	 *
	 */
	public function addVergoedingenWerknemerForInlener( $plaatsing_id, $werknemer_id, $inlener_id )
	{
		$vergoedingen = $this->inlener( $inlener_id )->vergoedingenInlener();

		if( $vergoedingen === NULL )
			return NULL;
		
		foreach( $vergoedingen as $vergoeding )
		{
			unset($insert);
			$insert['inlener_id'] = $inlener_id;
			$insert['werknemer_id'] = $werknemer_id;
			$insert['inlener_vergoeding_id'] = $vergoeding['inlener_vergoeding_id'];
			$insert['vergoeding_active'] = 1;
			$insert['vergoeding_id'] =  $vergoeding['vergoeding_id'];
			$insert['user_id'] = $this->user->user_id;
			
			$insert_batch[] = $insert;
		}
		
		if( isset($insert_batch) )
		{
			$this->db_user->insert_batch( 'werknemers_vergoedingen', $insert_batch );
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vergoedingen weghalen bij inlener voor bepaalde werknemer
	 *
	 */
	public function deleteVergoedingenWerknemerForInlener( $werknemer_id, $inlener_id )
	{
		$sql = "UPDATE werknemers_vergoedingen SET deleted = 1, deleted_on = NOW(), deleted_by = ?	WHERE werknemer_id = ? AND inlener_id = ? AND deleted = 0";
		$this->db_user->query( $sql, array($this->user->user_id, $werknemer_id, $inlener_id) );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werknemers met vergoedingen ophalen
	 *
	 */
	public function werknemersEnVergoedingen()
	{
		//alle urentypes voor inlener ophalen
		$vergoedingen = $this->vergoedingenInlener();
		
		$sql = "SELECT werknemers_vergoedingen.vergoeding_active, werknemers_vergoedingen.id, inleners_vergoedingen.*, vergoedingen.naam, vergoedingen.belast ,werknemers_gegevens.achternaam,
       				werknemers_gegevens.voornaam, werknemers_gegevens.voorletters, werknemers_gegevens.tussenvoegsel
				FROM werknemers_vergoedingen
				LEFT JOIN inleners_vergoedingen ON inleners_vergoedingen.inlener_vergoeding_id = werknemers_vergoedingen.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON werknemers_vergoedingen.vergoeding_id = vergoedingen.vergoeding_id
				LEFT JOIN werknemers_gegevens ON werknemers_vergoedingen.werknemer_id = werknemers_gegevens.werknemer_id
				LEFT JOIN werknemers_status ON werknemers_vergoedingen.werknemer_id = werknemers_status.werknemer_id
				WHERE werknemers_vergoedingen.inlener_id = $this->_inlener_id AND werknemers_vergoedingen.deleted = 0 AND inleners_vergoedingen.deleted = 0
					AND vergoedingen.deleted = 0 AND werknemers_gegevens.deleted = 0 AND werknemers_status.archief = 0
				ORDER BY werknemers_gegevens.achternaam";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return $vergoedingen;
		
		foreach( $query->result_array() as $row )
		{
			$row['werknemer_naam'] = make_name( $row );
			$vergoedingen[$row['inlener_vergoeding_id']]['werknemers'][] = $row;
		}
		
		return $vergoedingen;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array | bool
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