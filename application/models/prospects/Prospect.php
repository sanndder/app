<?php

namespace models\prospects;

use models\Connector;
use models\forms\Validator;
use models\users\UserGroup;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Propect class
 *
 *
 *
 */

class Prospect extends Connector
{

	private $_prospect_id = NULL; // @var int
	private $_valid_data = array();
	private $_insert_id = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $prospect_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID($prospect_id);
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 *
	 */
	public function setID($prospect_id)
	{
		$this->_prospect_id = intval($prospect_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Check prospect
	 *
	 */
	public function validate()
	{
		$validator = new Validator();
		
		if( isset($_POST['new']) ) $table = 'prospect_new';
		if( isset($_POST['set']) ) $table = 'prospect_set';
		
		$validator->table( $table )->input( $_POST )->run();
		
		$this->_valid_data = $validator->data();
		
		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
			return true;
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
			return false;
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe prospect
	 *
	 */
	public function new()
	{
		$id = $this->lastID( 'prospects', 'prospect_id' );
		
		$insert['prospect_id'] = $id+1;
		$insert['user_id'] = $this->user->id;
		$insert['am_id'] = $this->user->id;
		
		$insert['bedrijfsnaam'] = $this->_valid_data['bedrijfsnaam'];
		$insert['telefoon'] = $this->_valid_data['telefoon'];
		$insert['status_id'] = $this->_valid_data['status'];
		$insert['reden_geen_interesse'] = $this->_valid_data['reden'];
		
		$this->db_user->insert( 'prospects', $insert );
		$this->_insert_id = $this->db_user->insert_id();
		if( $this->_insert_id > 0 )
			return true;
		
		return false;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * TODO: beveiliggen
	 *
	 */
	public function set( $field, $val )
	{
		$sql = "SELECT $field FROM prospects WHERE prospect_id = $this->_prospect_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return false;
		
		$old_value = DBhelper::toRow($query,'NULL', $field);
		
		//geen wijziging is niet updaten
		if( $old_value == $val )
			return true;
		
		$update[$field] = trim($val);
		$this->db_user->where( 'prospect_id', $this->_prospect_id );
		$this->db_user->update( 'prospects', $update );
		
		if( $this->db_user->affected_rows() != -1  )
		{
			$this->_logChange( $field, $old_value, $val );
			return true;
		}
		
		return false;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Taak afronden of juist niet
	 *
	 */
	public function toggleTaak( $taak_id, $state)
	{
		if( $state == 'true' )
		{
			$update['afgerond'] = 1;
			$update['afgerond_door'] = $this->user->id;
			$update['afgerond_op'] = date('Y-m-d');
		}
		else
		{
			$update['afgerond'] = 0;
			$update['afgerond_door'] = NULL;
			$update['afgerond_op'] = NULL;
		}
		
		$this->db_user->where( 'taak_id', $taak_id);
		$this->db_user->where( 'prospect_id', $this->_prospect_id );
		$this->db_user->update( 'prospects_taken', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * TODO: verbeteren
	 * Notitie toevoegen
	 *
	 */
	public function addNotitie()
	{
		$insert['prospect_id'] = $this->_prospect_id;
		$insert['type'] = $_POST['type'];
		$insert['notitie'] = $_POST['notitie'];
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'prospects_notities', $insert );
		if( $this->db_user->insert_id() > 0 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * TODO: verbeteren
	 * Taak toevoegen
	 *
	 */
	public function addTaak()
	{
		
		
		$insert['prospect_id'] = $this->_prospect_id;
		$insert['actie'] = trim($_POST['actie']);
		$insert['datum'] = reverseDate($_POST['datum']);
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'prospects_taken', $insert );
		if( $this->db_user->insert_id() > 0 )
			return true;
		
		return false;
	}


	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * log change
	 *
	 */
	private function _logChange( $field, $old, $new )
	{
		$insert['prospect_id'] = $this->_prospect_id;
		$insert['user_id'] = $this->user->id;
		$insert['name'] = $field;
		$insert['old_val'] = $old;
		$insert['new_val'] = $new;
		
		$this->db_user->insert( 'prospects_changelog', $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get insert ID
	 *
	 */
	public function insertID()
	{
		return $this->_insert_id;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * details
	 *
	 */
	public function details()
	{
		$query = $this->db_user->query( "SELECT * FROM prospects WHERE prospect_id = $this->_prospect_id AND deleted = 0 LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * notities
	 *
	 */
	public function notities()
	{
		$query = $this->db_user->query( "SELECT * FROM prospects_notities WHERE prospect_id = $this->_prospect_id AND deleted = 0 ORDER BY timestamp DESC" );
		$data = DBhelper::toArray( $query, 'notitie_id', 'NULL',  );
		
		$data = UserGroup::findUserNames($data);
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * notities
	 *
	 */
	public function taken()
	{
		$sql = "SELECT * FROM prospects_taken
				WHERE prospect_id = $this->_prospect_id AND deleted = 0 AND (afgerond_op > DATE_SUB(NOW(), INTERVAL 7 DAY) OR afgerond_op IS NULL )
				ORDER BY afgerond ASC, datum ASC";
		
		
		$query = $this->db_user->query( $sql );
		$data = DBhelper::toArray( $query, 'taak_id', 'NULL',  );
		
		$data = UserGroup::findUserNames($data);
		
		return $data;
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