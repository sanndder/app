<?php

namespace models\zzp;

use models\Connector;
use models\users\UserGroup;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * zzp Lists
 *
 * Alle lijsten van zzper moeten via deze class
 *
 */
class ZzpGroup extends Connector {


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
	 * list zzpers for inlener
	 *
	 * @return array | bool
	 */
	static function inlener( $inlener_id )
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT zzp_persoonsgegevens.zzp_id, achternaam, voorletters, voornaam, tussenvoegsel
				FROM zzp_inleners
				LEFT JOIN zzp_persoonsgegevens ON zzp_persoonsgegevens.zzp_id = zzp_inleners.zzp_id
				WHERE zzp_inleners.inlener_id = $inlener_id
				AND zzp_persoonsgegevens.deleted = 0 AND zzp_inleners.deleted = 0
				ORDER BY achternaam";
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['zzp_id']] = make_name($row);
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle zzper tellen
	 */
	public function count()
	{
		$sql = "SELECT COUNT(zzp_status.zzp_id) AS count
				FROM zzp_status
				LEFT JOIN zzp_uitzenders ON zzp_status.zzp_id = zzp_uitzenders.zzp_id
				WHERE complete = 1 AND archief = 0";
				
		if( $this->user->user_type == 'uitzender')
			$sql .= " AND zzp_uitzenders.uitzender_id = ". $this->uitzender->id;
		
		$query = $this->db_user->query( $sql );
		
		$data = DBhelper::toRow( $query );
		return $data['count'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * List van zzp'ers
	 */
	static function list()
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT zzp_bedrijfsgegevens.zzp_id, zzp_bedrijfsgegevens.bedrijfsnaam
				FROM zzp_status
				LEFT JOIN zzp_bedrijfsgegevens ON zzp_bedrijfsgegevens.zzp_id = zzp_status.zzp_id
				WHERE zzp_bedrijfsgegevens.deleted = 0 AND zzp_status.archief = 0
				ORDER BY zzp_bedrijfsgegevens.bedrijfsnaam";
		
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['zzp_id']] = $row['bedrijfsnaam'];
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle zzper ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();

		//start query
		$sql = "SELECT zzp_status.*, zzp_bedrijfsgegevens.*, zzp_persoonsgegevens.*,
       					zzp_uitzenders.uitzender_id,
       					zzp_inleners.inlener_id, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender
				FROM zzp_status
				LEFT JOIN zzp_persoonsgegevens ON zzp_persoonsgegevens.zzp_id = zzp_status.zzp_id
				LEFT JOIN zzp_bedrijfsgegevens ON zzp_bedrijfsgegevens.zzp_id = zzp_status.zzp_id
				LEFT JOIN zzp_uitzenders ON zzp_status.zzp_id = zzp_uitzenders.zzp_id
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = zzp_uitzenders.uitzender_id
				LEFT JOIN zzp_inleners ON zzp_status.zzp_id = zzp_inleners.zzp_id
				WHERE zzp_persoonsgegevens.deleted = 0 AND (uitzenders_bedrijfsgegevens.deleted = 0 OR uitzenders_bedrijfsgegevens.deleted IS NULL)
				  AND (zzp_uitzenders.deleted = 0 OR zzp_uitzenders.deleted IS NULL )
				  AND (zzp_inleners.deleted = 0 OR zzp_inleners.deleted IS NULL  )
				";
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND zzp_uitzenders.uitzender_id = ".$this->uitzender->id." ";
		
		//beveiligen
		if( $this->user->user_type == 'inlener' )
			$sql .= " AND zzp_inleners.inlener_id = ".$this->inlener->id." ";

		//archief ook?
		if( isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND zzp_status.archief = 0";

		if( !isset($param['actief']) && isset($param['archief']) )
			$sql .= " AND zzp_status.archief = 1";

		//default
		if( !isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND zzp_status.archief = 0";

		//zoeken, q1 is voor ID en bedrijfsnaam, q2 is voor overig
		if( isset($param['q1']) && $param['q1'] != '' )
			$sql .= " AND (zzp_bedrijfsgegevens.bedrijfsnaam LIKE '%". addslashes($_GET['q1'])."%' OR zzp_status.zzp_id LIKE '%". addslashes($_GET['q1'])."%' ) ";
		
		//specifieke uitzender?
		if( isset($param['uitzender_id']) )
			$sql .= " AND zzp_uitzenders.uitzender_id = ".intval($param['uitzender_id'])." ";

		//specifieke inlener?
		if( isset($param['inlener_id']) )
			$sql .= " AND zzp_inleners.inlener_id = ".intval($param['inlener_id'])." ";
		
		//nieuw?
		if( isset($param['new']) )
			$sql .= " AND zzp_status.complete = 0 ";
		
		//group
			$sql .= " GROUP BY zzp_status.zzp_id";

		//go
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$data[$row['zzp_id']] = $row;
		}
		
		//kijken of uitzender users heeft
		$users = UserGroup::listUsertypeID('zzp', array_keys($data));
		
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
		$sql = "SELECT * FROM zzp_info";
		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$update['zzp_id'] = $row['zzp_id'];
			$update['archief'] = 0;
			$update['complete'] = 1;
			$update['info_complete'] = 1;
			$update['email_complete'] = 1;
			$update['handtekening_complete'] = 1;

			$this->db_user->insert('zzp_status', $update);
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