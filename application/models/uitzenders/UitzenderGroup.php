<?php

namespace models\uitzenders;

use models\Connector;
use models\users\UserGroup;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Uitzender Lists
 *
 * Alle lijsten van uitzenders moeten via deze class
 *
 */
class UitzenderGroup extends Connector {

	/*
	 * set columns for SELECT query
	 * @var array
	 */
	private $_cols = '*';

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
	 * Bedrijfsnaam van 1 uitzender ophalen
	 */
	static function bedrijfsnaam( $uitzender_id )
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT uitzenders_bedrijfsgegevens.bedrijfsnaam FROM uitzenders_bedrijfsgegevens
				WHERE uitzenders_bedrijfsgegevens.deleted = 0
				AND uitzender_id = ".intval($uitzender_id)." LIMIT 1";
		
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		
		return $data['bedrijfsnaam'];
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * List van uitzenders
	 */
	static function list()
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT uitzenders_bedrijfsgegevens.uitzender_id, uitzenders_bedrijfsgegevens.bedrijfsnaam
				FROM uitzenders_status
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_status.uitzender_id
				WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND uitzenders_status.archief = 0
				ORDER BY uitzenders_bedrijfsgegevens.bedrijfsnaam";
		
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['uitzender_id']] = $row['bedrijfsnaam'];
		
		return $data;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * aanpassen welke columns er opgehaald moeten worden, standaard alles
	 * param is door komma gescheiden lijst
	 * @return void
	 */
	public function setColumns( $cols = NULL )
	{
		if( $cols != NULL )
		{
			$this->_cols = $cols;
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle uitzenders tellen
	 */
	public function count()
	{
		$query = $this->db_user->query( "SELECT COUNT(uitzender_id) AS count FROM uitzenders_status WHERE complete = 1 AND archief = 0" );
		$data = DBhelper::toRow( $query );
		return $data['count'];
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle uitzenders ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();

		//start query
		$sql = "SELECT $this->_cols 
				FROM uitzenders_status
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_status.uitzender_id
				WHERE uitzenders_bedrijfsgegevens.deleted = 0";

		//archief ook?
		if( isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND uitzenders_status.archief = 0";

		if( !isset($param['actief']) && isset($param['archief']) )
			$sql .= " AND uitzenders_status.archief = 1";

		//default
		if( !isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND uitzenders_status.archief = 0";

		//zoeken, q1 is voor ID en bedrijfsnaam, q2 is voor overig
		if( isset($param['q1']) && $param['q1'] != '' )
			$sql .= " AND (uitzenders_bedrijfsgegevens.bedrijfsnaam LIKE '%". addslashes($_GET['q1'])."%' OR uitzenders_status.uitzender_id LIKE '%". addslashes($_GET['q1'])."%' ) ";
		
		//alleen nieuwe
		if( isset($param['new']) )
			$sql .= " AND uitzenders_status.complete = 0";
			
		//go
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
			$data[$row['uitzender_id']] = $row;

		//kijken of uitzender users heeft
		$users = UserGroup::listUsertypeID('uitzender', array_keys($data));
		
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
		$sql = "SELECT * FROM uitzenders_info";
		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$update['uitzender_id'] = $row['uitzender_id'];
			$update['archief'] = 0;
			$update['complete'] = 1;
			$update['info_complete'] = 1;
			$update['email_complete'] = 1;
			$update['handtekening_complete'] = 1;

			$this->db_user->insert('uitzenders_status', $update);
		}

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * lijst voor snelstart
	 */
	public function snelstartExport()
	{
		$sql = "SELECT uitzenders_status.uitzender_id, uitzenders_bedrijfsgegevens.bedrijfsnaam
				FROM uitzenders_status
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_status.uitzender_id
				WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND archief = 0 AND complete = 1";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'uitzender_id', 'NULL' );
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