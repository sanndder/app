<?php

namespace models\inleners;

use models\Connector;
use models\users\UserGroup;
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
		$sql = "SELECT COUNT(inleners_status.inlener_id) AS count 
				FROM inleners_status
				LEFT JOIN inleners_uitzenders ON inleners_status.inlener_id = inleners_uitzenders.inlener_id
				WHERE inleners_uitzenders.deleted = 0 AND inleners_status.complete = 1 AND inleners_status.archief = 0";

		if( $this->user->user_type == 'uitzender')
			$sql .= " AND inleners_uitzenders.uitzender_id = ". $this->uitzender->id;

		$query = $this->db_user->query( $sql );

		$data = DBhelper::toRow( $query );
		return $data['count'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * lijst voor ureninvoer, minder data via ajax sturen
	 * Alleen met plaatsing
	 */
	public function listForUreninvoer( $tijdvakinfo = NULL)
	{
		//init
		$data = array();

		//start query
		$sql = "SELECT inleners_bedrijfsgegevens.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam, inleners_factuurgegevens.frequentie
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_factuurgegevens ON inleners_factuurgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_uitzenders ON inleners_status.inlener_id = inleners_uitzenders.inlener_id
				LEFT JOIN werknemers_inleners ON inleners_status.inlener_id = werknemers_inleners.inlener_id
				LEFT JOIN zzp_inleners ON inleners_status.inlener_id = zzp_inleners.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_status.archief = 0 AND inleners_status.complete = 1 AND inleners_factuurgegevens.deleted = 0
				AND inleners_uitzenders.deleted = 0 AND inleners_status.hide_ureninvoer = 0 AND
				((werknemers_inleners.deleted = 0 AND werknemers_inleners.inlener_id IS NOT NULL) OR (zzp_inleners.deleted = 0 AND zzp_inleners.inlener_id IS NOT NULL))";
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND inleners_uitzenders.uitzender_id = ".$this->uitzender->id." ";
		
		//specifieke uitzender?
		if( $this->_uitzender_id !== NULL )
			$sql .= " AND inleners_uitzenders.uitzender_id = $this->_uitzender_id ";
		
		//sort
		$sql .= " GROUP BY inlener_id ORDER BY inleners_factuurgegevens.frequentie DESC, inleners_bedrijfsgegevens.bedrijfsnaam";
		
		//go
		$query = $this->db_user->query($sql);
		
		if ($query->num_rows() == 0)
			return $data;
		
		foreach ($query->result_array() as $row)
		{
			$row['facturen'] = 0;
			
			if( $row['frequentie'] == 'w') $f = 'week';
			if( $row['frequentie'] == 'm') $f = 'maand';
			if( $row['frequentie'] == '4w') $f = '4 weken';
			
			$data[$f][] = $row;
			
			$inlener_ids[$row['inlener_id']] = $f;
		}
		
		//facturen erbij
		$sql = "SELECT COUNT(factuur_id) AS aantal, inlener_id
				FROM facturen WHERE tijdvak = ? AND jaar = ? AND periode = ? AND deleted = 0 AND marge = 0 AND concept = 0 AND credit = 0 AND inlener_id IN (".array_keys_to_string($inlener_ids).")
				GROUP BY inlener_id";
		
		$query = $this->db_user->query($sql, array($tijdvakinfo['tijdvak'], $tijdvakinfo['jaar'], $tijdvakinfo['periode']));
		
		if ($query->num_rows() == 0)
			return $data;
		
		foreach( $query->result_array() as $row )
			$facturen[$row['inlener_id']] = $row['aantal'];
		
		foreach( $data as &$arr )
		{
			foreach( $arr as &$inlener )
			{
				if( isset($facturen[$inlener['inlener_id']]) )
					$inlener['facturen'] = $facturen[$inlener['inlener_id']];
			}
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
		$sql = "SELECT inleners_bedrijfsgegevens.*, inleners_status.*, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender, uitzenders_bedrijfsgegevens.uitzender_id, inleners_factuurgegevens.factoring, inleners_factuurgegevens.factuur_wachtrij
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_factuurgegevens ON inleners_factuurgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_uitzenders ON inleners_status.inlener_id = inleners_uitzenders.inlener_id
				LEFT JOIN uitzenders_bedrijfsgegevens ON inleners_uitzenders.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_uitzenders.deleted = 0 AND uitzenders_bedrijfsgegevens.deleted = 0 AND (inleners_factuurgegevens.deleted = 0 OR inleners_factuurgegevens.deleted IS NULL)";
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND inleners_uitzenders.uitzender_id = ".$this->uitzender->id." ";
		
		//complete?
		if( isset($param['complete']) )
			$sql .= " AND inleners_status.complete = " . intval($param['complete']);
		
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
			$data[$row['inlener_id']] = $row;
		
		//kijken of inlener users heeft
		$users = UserGroup::listUsertypeID('inlener', array_keys($data));
		
		foreach ( $users as $user )
			$data[$user]['user'] = 1;
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Bedrijfsnaam opvragen
	 *
	 */
	static function bedrijfsnaam( $inlener_id )
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT inleners_bedrijfsgegevens.bedrijfsnaam FROM inleners_bedrijfsgegevens
				WHERE inleners_bedrijfsgegevens.deleted = 0
				AND inlener_id = ".intval($inlener_id)." LIMIT 1";
		
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
	static function list( $uitzender_id = NULL )
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT inleners_bedrijfsgegevens.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_uitzenders ON inleners_status.inlener_id = inleners_uitzenders.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_status.archief = 0";
		
		if( $uitzender_id !== NULL )
			$sql .= " AND inleners_uitzenders.uitzender_id = ". intval($uitzender_id);
		
		$sql .= " ORDER BY inleners_bedrijfsgegevens.bedrijfsnaam";
		
		$query = $db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['inlener_id']] = $row['bedrijfsnaam'];
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * wat heeft de inlener wel en niet gedaan
	 *
	 */
	public function aanmeldActies( $inlener_id = NULL )
	{
		
		$sql = "SELECT inleners_status.inlener_id, inleners_status.complete, inleners_av_accepted.av_id, inleners_bedrijfsgegevens.bedrijfsnaam
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_av_accepted ON inleners_av_accepted.inlener_id = inleners_status.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0";
		
		if( $inlener_id !== NULL )
			$sql .= " AND inleners_status.inlener_id = $inlener_id LIMIT 1";
		else
			$sql .= " AND inleners_status.archief = 0";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		//array aanmaken
		foreach( $query->result_array() as $row )
		{
			$data[$row['inlener_id']] = $row;
		}
		
		//users erbij
		$usergroup = new UserGroup();
		$users = $usergroup->forInleners( array_keys($data) );
		
		//documenten
		// TODO naar document group met juiste template ID
		if( $this->user->werkgever_type == 'uitzenden' )
			$sql = "SELECT inlener_id, document_id FROM documenten WHERE signed_file_name_display IS NOT NULL AND inlener_id IN (".array_keys_to_string($data).") AND deleted = 0 AND file_name_display = 'overeenkomst_van_opdracht.pdf'";
		if( $this->user->werkgever_type == 'bemiddeling' )
			$sql = "SELECT inlener_id, document_id FROM documenten WHERE signed_file_name_display IS NOT NULL AND inlener_id IN (".array_keys_to_string($data).") AND deleted = 0 AND file_name_display = 'overeenkomst_inlener.pdf'";
		
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
				$documenten[$row['inlener_id']] = $row;
		}

		//data aanvullen
		foreach( $data as $i_id => $row )
		{
			//users
			if(isset($users[$i_id]))
			{
				$data[$i_id]['user'] = $users[$i_id]['user_id'];
				if( $users[$i_id]['password'] !== NULL )
					$data[$i_id]['user_password'] = true;
				else
					$data[$i_id]['user_password'] = NULL;
			}
			else
			{
				$data[$i_id]['user'] = NULL;
				$data[$i_id]['user_password'] = NULL;
			}
			
			//documenten
			if(isset($documenten[$i_id]))
				$data[$i_id]['overeenkomst_opdracht'] = true;
			else
				$data[$i_id]['overeenkomst_opdracht'] = NULL;
			
			//opschonen
			if( $inlener_id === NULL )
			{
				if( $data[$i_id]['av_id'] !== NULL && $data[$i_id]['user'] !== NULL && $data[$i_id]['user_password'] !== NULL && $data[$i_id]['overeenkomst_opdracht'] !== NULL )
					unset( $data[$i_id] );
			}
		}
		
		if( $inlener_id !== NULL )
			return $data[$inlener_id];
		
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
	 * lijst voro snelstart
	 */
	public function snelstartExport()
	{
		$sql = "SELECT inleners_status.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam, inleners_factuurgegevens.termijn
				FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				LEFT JOIN inleners_factuurgegevens ON inleners_factuurgegevens.inlener_id = inleners_status.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_factuurgegevens.deleted = 0 AND archief = 0 AND complete = 1";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'inlener_id', 'NULL' );
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