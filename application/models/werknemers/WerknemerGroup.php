<?php

namespace models\Werknemers;

use models\Connector;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Werknemer Lists
 *
 * Alle lijsten van werknemers moeten via deze class
 *
 */
class WerknemerGroup extends Connector {

	/*
	 * set columns for SELECT query
	 * @var array
	 */
	private $_cols = '*';

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
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * list werknemers for inlener
	 *
	 * @return array or boolean
	 */
	static function inlener( $inlener_id )
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT werknemers_gegevens.werknemer_id, achternaam, voorletters, voornaam, tussenvoegsel
				FROM werknemers_inleners
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_inleners.werknemer_id
				WHERE werknemers_gegevens.deleted = 0 ORDER BY achternaam";
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['werknemer_id']] = make_name($row);
		
		return $data;
	}
	
	
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle werknemers ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();

		//start query
		$sql = "SELECT $this->_cols 
				FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_status.werknemer_id
				WHERE werknemers_gegevens.deleted = 0";

		//archief ook?
		if( isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND werknemers_status.archief = 0";

		if( !isset($param['actief']) && isset($param['archief']) )
			$sql .= " AND werknemers_status.archief = 1";

		//default
		if( !isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND werknemers_status.archief = 0";

		//zoeken, q1 is voor ID en bedrijfsnaam, q2 is voor overig
		if( isset($param['q1']) && $param['q1'] != '' )
			$sql .= " AND (werknemers_gegevens.bedrijfsnaam LIKE '%". addslashes($_GET['q1'])."%' OR werknemers_status.werknemer_id LIKE '%". addslashes($_GET['q1'])."%' ) ";



		//go
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$data[$row['werknemer_id']] = $row;
		}

		return $data;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
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