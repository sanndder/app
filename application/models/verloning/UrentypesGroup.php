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
	 * urentypes voor werknemer
	 *
	 */
	public function urentypesWerknemer( $werknemer_id, $include_not_active = false ) :array
	{
		$sql = "SELECT werknemers_urentypes.id, inleners_urentypes.doorbelasten_uitzender, inleners_urentypes.label, urentypes.naam, urentypes.percentage, urentypes_categorien.naam AS categorie, werknemers_urentypes.verkooptarief,
     			werknemers_urentypes.urentype_active, inleners_urentypes.default_urentype
				FROM werknemers_urentypes
				LEFT JOIN inleners_urentypes ON inleners_urentypes.inlener_urentype_id = werknemers_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien on urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				WHERE werknemers_urentypes.deleted = 0 AND werknemers_urentypes.werknemer_id = $werknemer_id AND inleners_urentypes.inlener_id = $this->_inlener_id
				";
		
		//alleen actief
		if( !$include_not_active )
			$sql .= " AND werknemers_urentypes.urentype_active = 1 ";
		
		$sql .=	"ORDER BY inleners_urentypes.default_urentype DESC, urentypes.urentype_categorie_id, inleners_urentypes.inlener_urentype_id, urentypes.percentage";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			//werknemer mag nooit uurtarief zien
			if( $this->user->user_type == 'werknemer' ) unset($row['verkooptarief']);
			
			$data[$row['id']] = $row;
		}

		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * urentypes voor zzp'er
	 *
	 */
	public function urentypesZzp( $zzp_id, $include_not_active = false ) :array
	{
		$sql = "SELECT zzp_urentypes.id, inleners_urentypes.doorbelasten_uitzender, inleners_urentypes.label, urentypes.naam, urentypes.percentage, urentypes_categorien.naam AS categorie,zzp_urentypes.verkooptarief,
     			zzp_urentypes.urentype_active, inleners_urentypes.default_urentype, zzp_urentypes.uurtarief, zzp_urentypes.marge
				FROM zzp_urentypes
				LEFT JOIN inleners_urentypes ON inleners_urentypes.inlener_urentype_id = zzp_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien on urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				WHERE zzp_urentypes.deleted = 0 AND zzp_urentypes.zzp_id = $zzp_id AND inleners_urentypes.inlener_id = $this->_inlener_id
				";
		
		//alleen actief
		if( !$include_not_active )
			$sql .= " AND zzp_urentypes.urentype_active = 1 ";
		
		$sql .=	" ORDER BY urentypes.urentype_categorie_id, inleners_urentypes.inlener_urentype_id, urentypes.percentage";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			//zzp mag nooit uurtarief zien
			if( $this->user->user_type == 'zzp' ) unset($row['verkooptarief']);
			if( $this->user->user_type == 'zzp' ) unset($row['marge']);
			
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
	 * get matrix werknemer
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
	 * get matrix
	 *
	 */
	public function getUrentypeZzpMatrix()
	{
		//alle urentypes voor inlener ophalen
		$urentypes = $this->urentypesInlener();
		
		//alle werknemers ophalen
		$sql = "SELECT zzp_urentypes.id, zzp_urentypes.urentype_active, zzp_urentypes.zzp_id, zzp_urentypes.verkooptarief, zzp_urentypes.urentype_id, zzp_urentypes.plaatsing_id,
       				   zzp_urentypes.inlener_urentype_id, zzp_bedrijfsgegevens.bedrijfsnaam, zzp_persoonsgegevens.voornaam, zzp_persoonsgegevens.voorletters, zzp_persoonsgegevens.tussenvoegsel, zzp_persoonsgegevens.achternaam
				FROM zzp_urentypes
				LEFT JOIN zzp_bedrijfsgegevens ON zzp_urentypes.zzp_id = zzp_bedrijfsgegevens.zzp_id
				LEFT JOIN zzp_persoonsgegevens ON zzp_urentypes.zzp_id = zzp_persoonsgegevens.zzp_id
				LEFT JOIN zzp_status ON zzp_urentypes.zzp_id = zzp_status.zzp_id
				LEFT JOIN zzp_inleners ON (zzp_urentypes.plaatsing_id = zzp_inleners.plaatsing_id  )
				WHERE zzp_urentypes.inlener_id = $this->_inlener_id AND zzp_bedrijfsgegevens.deleted = 0 AND zzp_persoonsgegevens.deleted = 0 AND zzp_urentypes.deleted = 0 AND zzp_inleners.deleted = 0
				AND zzp_status.archief = 0
				ORDER BY achternaam, plaatsing_id
				";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return $urentypes;
		
		foreach( $query->result_array() as $row )
		{
			$row['zzp_naam'] = make_name( $row );
			$urentypes[$row['inlener_urentype_id']]['zzp'][] = $row;
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