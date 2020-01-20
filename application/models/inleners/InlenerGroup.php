<?php

namespace models\inleners;

use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Inlener Lists
 *
 * Alle lijsten van inleners moeten via deze class
 *
 */
class InlenerGroup extends Connector {
	
	private $_uitzender_id = NULL;
	private $_exclude_ids = NULL;
	
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
	 * Alle inleners tellen
	 */
	public function count()
	{
		$query = $this->db_user->query( "SELECT COUNT(inlener_id) AS count FROM inleners_status WHERE complete = 1 AND archief = 0" );
		$data = DBhelper::toRow( $query );
		return $data['count'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * lijst voor ureninvoer, minder data via ajax sturen
	 */
	public function listForUreninvoer()
	{
		//init
		$data = array();
		
		//start query
		$sql = "SELECT inleners_bedrijfsgegevens.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam, inleners_factuurgegevens.frequentie
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_factuurgegevens ON inleners_factuurgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_uitzenders ON inleners_status.inlener_id = inleners_uitzenders.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_status.archief = 0 AND inleners_status.complete = 1 AND inleners_factuurgegevens.deleted = 0";
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND inleners_uitzenders.uitzender_id = ".$this->uitzender->id." ";
		
		//specifieke uitzender?
		if( $this->_uitzender_id !== NULL )
			$sql .= " AND inleners_uitzenders.uitzender_id = $this->_uitzender_id ";
		
		//sort
		$sql .= " ORDER BY inleners_factuurgegevens.frequentie DESC, inleners_bedrijfsgegevens.bedrijfsnaam ";
		
		//go
		$query = $this->db_user->query($sql);
		
		if ($query->num_rows() == 0)
			return $data;
		
		foreach ($query->result_array() as $row)
		{
			if( $row['frequentie'] == 'w') $f = 'week';
			if( $row['frequentie'] == 'm') $f = 'maand';
			if( $row['frequentie'] == '4w') $f = '4 weken';
			
			$data[$f][$row['inlener_id']] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle inleners ophalen aan de hand van de zoekcriteria
	 */
	public function all( $param = NULL )
	{
		//init
		$data = array();

		//start query
		$sql = "SELECT inleners_bedrijfsgegevens.*, inleners_status.*, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender, uitzenders_bedrijfsgegevens.uitzender_id
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_uitzenders ON inleners_status.inlener_id = inleners_uitzenders.inlener_id
				LEFT JOIN uitzenders_bedrijfsgegevens ON inleners_uitzenders.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_uitzenders.deleted = 0 AND uitzenders_bedrijfsgegevens.deleted = 0";
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND inleners_uitzenders.uitzender_id = ".$this->uitzender->id." ";
		
		//archief ook?
		if( isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND inleners_status.archief = 0";

		if( !isset($param['actief']) && isset($param['archief']) )
			$sql .= " AND inleners_status.archief = 1";

		//default
		if( !isset($param['actief']) && !isset($param['archief']) )
			$sql .= " AND inleners_status.archief = 0";

		//zoeken, q1 is voor ID en bedrijfsnaam, q2 is voor overig
		if( isset($param['q1']) && $param['q1'] != '' )
			$sql .= " AND (inleners_bedrijfsgegevens.bedrijfsnaam LIKE '%". addslashes($_GET['q1'])."%' OR inleners_status.inlener_id LIKE '%". addslashes($_GET['q1'])."%' ) ";

		//specifieke uitzender?
		if( isset($param['uitzender_id']) )
			$sql .= " AND inleners_uitzenders.uitzender_id = ".intval($param['uitzender_id'])." ";
		
		//specifieke uitzender?
		if( $this->_uitzender_id !== NULL )
			$sql .= " AND inleners_uitzenders.uitzender_id = $this->_uitzender_id ";
		
		//ID's uitsluiten
		if( $this->_exclude_ids !== NULL )
			$sql .= " AND inleners_status.inlener_id NOT IN (". implode(',',$this->_exclude_ids).") ";
		
		//specifieke uitzender?
		if( isset($param['new']) )
			$sql .= " AND inleners_status.complete = 0 ";
		
		//sort
		$sql .= " ORDER BY inleners_bedrijfsgegevens.bedrijfsnaam ";

		//go
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result_array() as $row)
		{
			$data[$row['inlener_id']] = $row;
		}

		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uitzender ID toevoegen
	 */
	public function uitzender( $uitzender_id ) :InlenerGroup
	{
		$this->_uitzender_id = intval($uitzender_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uitzender ID toevoegen
	 */
	public function exclude(  array $inlener_ids) :InlenerGroup
	{
		$this->_exclude_ids = $inlener_ids;
		return $this;
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
		$sql = "SELECT * FROM inleners_info";
		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$update['inlener_id'] = $row['inlener_id'];
			$update['archief'] = 0;
			$update['complete'] = 1;
			$update['info_complete'] = 1;
			$update['email_complete'] = 1;
			$update['handtekening_complete'] = 1;

			$this->db_user->insert('inleners_status', $update);
		}

	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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