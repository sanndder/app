<?php

namespace models\utils;

use models\Connector;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Helpt met ondernemingen en kopieeren
 *
 *
 */
class Ondernemingen extends Connector
{
	private $_uitzender_id = NULL;
	private $_inlener_id = NULL;
	private $_onderneming = NULL;
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
	 * set get uitzender ID
	 * @return int|object
	 */
	public function uitzender( $uitzender_id = NULL ) :?Ondernemingen
	{
		if( $uitzender_id === NULL )
			return $uitzender_id;
		
		$this->_uitzender_id = intval($uitzender_id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set get inlener ID
	 * @return int|object
	 */
	public function inlener( $inlener_id = NULL ) :?Ondernemingen
	{
		if( $inlener_id === NULL )
			return $inlener_id;
		
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * copy factory
	 * @return bool
	 */
	public function copy( array $onderneming ) :bool
	{
		$this->_onderneming = $onderneming;
		
		if( $this->_uitzender_id !== NULL )
			return $this->_copyUitzender();
		
		if( $this->_inlener_id !== NULL )
			return $this->_copyInlener();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * copy inlener
	 *
	 * @return bool
	 */
	public function _copyInlener() :bool
	{
		//juiste database
		$CI =& get_instance();
		$db = $CI->load->database('admin', TRUE);
		
		$db->database = $this->_onderneming['db_name'];
		$db->close();
		$db->initialize();
		
		$werkgever_id_old = $this->user->werkgever_id;
		$werkgever_id_new = $this->_onderneming['werkgever_id'];
		
		//eerst status doen voor
		$query = $this->db_user->query( "SELECT * FROM inleners_status WHERE inlener_id = $this->_inlener_id LIMIT 1" );
		$insert = $query->row_array();
		
		//insert nieuwe
		unset($insert['inlener_id']);
		$db->insert( 'inleners_status', $insert );
		
		//nieuwe ID
		$inlener_id = $db->insert_id();
		
		//check
		if( $inlener_id < 1 )
		{
			$this->_error[] = 'Fout bij nieuwe INSERT: Geen ID ontvangen.';
			return false;
		}
		
		// bedrijfsgegevens
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_bedrijfsgegevens WHERE inlener_id = $this->_inlener_id AND deleted = 0 LIMIT 1" );
		$insert = $query->row_array();
		$insert['inlener_id'] = $inlener_id;
		unset($insert['id']);
		unset($insert['timestamp']);
		$db->insert( 'inleners_bedrijfsgegevens', $insert );
		
		// inleners cao
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_cao WHERE inlener_id = $this->_inlener_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$row['inlener_id'] = $inlener_id;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		if(isset($insert))
			$db->insert_batch( 'inleners_cao', $insert );
		
		// contactpersonen
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_contactpersonen WHERE inlener_id = $this->_inlener_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$row['inlener_id'] = $inlener_id;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		$db->insert_batch( 'inleners_contactpersonen', $insert );
		
		// emailadressen
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_emailadressen WHERE inlener_id = $this->_inlener_id AND deleted = 0 LIMIT 1" );
		$insert = $query->row_array();
		$insert['inlener_id'] = $inlener_id;
		unset($insert['id']);
		unset($insert['timestamp']);
		$db->insert( 'inleners_emailadressen', $insert );
		
		// factoren
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_factoren WHERE inlener_id = $this->_inlener_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$row['inlener_id'] = $inlener_id;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		$db->insert_batch( 'inleners_factoren', $insert );
		
		// factuurgegevens
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_factuurgegevens WHERE inlener_id = $this->_inlener_id AND deleted = 0 LIMIT 1" );
		$insert = $query->row_array();
		$insert['inlener_id'] = $inlener_id;
		unset($insert['id']);
		unset($insert['timestamp']);
		$db->insert( 'inleners_factuurgegevens', $insert );
		
		// uitzenders
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_uitzenders WHERE inlener_id = $this->_inlener_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$uitzender_id_new = $this::switchUitzenderId( $row['uitzender_id'], $werkgever_id_old, $werkgever_id_new );
			
			$row['inlener_id'] = $inlener_id;
			$row['uitzender_id'] = $uitzender_id_new;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		$db->insert_batch( 'inleners_uitzenders', $insert );
		
		// urentypes
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_urentypes WHERE inlener_id = $this->_inlener_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$row['inlener_id'] = $inlener_id;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		if(isset($insert))
			$db->insert_batch( 'inleners_urentypes', $insert );
		
		// vergoedingen
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM inleners_vergoedingen WHERE inlener_id = $this->_inlener_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$row['inlener_id'] = $inlener_id;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		if(isset($insert))
			$db->insert_batch( 'inleners_vergoedingen', $insert );
		
		//user accounts kopieren
		$db->database = 'app_admin';
		$db->close();
		$db->initialize();
		
		unset($insert);
		
		$query = $db->query( "SELECT * FROM users_accounts WHERE inlener_id = $this->_inlener_id AND deleted = 0 AND werkgever_id = ".$this->user->werkgever_id );
		
		foreach( $query->result_array() as $row )
		{
			$row['inlener_id'] = $inlener_id;
			$row['werkgever_id'] = $this->_onderneming['werkgever_id'];
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		$db->insert_batch( 'users_accounts', $insert );
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * copy uitzender
	 *
	 * @return bool
	 */
	public function _copyUitzender() :bool
	{
		//juiste database
		$CI =& get_instance();
		$db = $CI->load->database('admin', TRUE);
		
		$db->database = $this->_onderneming['db_name'];
		$db->close();
		$db->initialize();
		
		//eerst status doen voor
		$query = $this->db_user->query( "SELECT * FROM uitzenders_status WHERE uitzender_id = $this->_uitzender_id LIMIT 1" );
		$insert = $query->row_array();
		
		//insert nieuwe
		unset($insert['uitzender_id']);
		$db->insert( 'uitzenders_status', $insert );
		
		//nieuwe ID
		$uitzender_id = $db->insert_id();
		
		//check
		if( $uitzender_id < 1 )
		{
			$this->_error[] = 'Fout bij nieuwe INSERT: Geen ID ontvangen.';
			return false;
		}
		
		// bedrijfsgegevens
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM uitzenders_bedrijfsgegevens WHERE uitzender_id = $this->_uitzender_id AND deleted = 0 LIMIT 1" );
		$insert = $query->row_array();
		$insert['uitzender_id'] = $uitzender_id;
		unset($insert['id']);
		unset($insert['timestamp']);
		$db->insert( 'uitzenders_bedrijfsgegevens', $insert );
		
		// contactpersonen
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM uitzenders_contactpersonen WHERE uitzender_id = $this->_uitzender_id AND deleted = 0" );
		foreach( $query->result_array() as $row )
		{
			$row['uitzender_id'] = $uitzender_id;
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		$db->insert_batch( 'uitzenders_contactpersonen', $insert );
		
		// emailadressen
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM uitzenders_emailadressen WHERE uitzender_id = $this->_uitzender_id AND deleted = 0 LIMIT 1" );
		$insert = $query->row_array();
		$insert['uitzender_id'] = $uitzender_id;
		unset($insert['id']);
		unset($insert['timestamp']);
		$db->insert( 'uitzenders_emailadressen', $insert );
		
		// factuurgegevens
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM uitzenders_factuurgegevens WHERE uitzender_id = $this->_uitzender_id AND deleted = 0 LIMIT 1" );
		$insert = $query->row_array();
		$insert['uitzender_id'] = $uitzender_id;
		unset($insert['id']);
		unset($insert['timestamp']);
		$db->insert( 'uitzenders_factuurgegevens', $insert );
		
		// handtekening
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM uitzenders_handtekening WHERE uitzender_id = $this->_uitzender_id AND deleted = 0 LIMIT 1" );
		if( $query->num_rows() > 0 )
		{
			$insert = $query->row_array();
			$insert['uitzender_id'] = $uitzender_id;
			unset( $insert['id'] );
			unset( $insert['timestamp'] );
			$db->insert( 'uitzenders_handtekening', $insert );
		}
		
		// logo
		unset($insert);
		$query = $this->db_user->query( "SELECT * FROM uitzenders_logo WHERE uitzender_id = $this->_uitzender_id AND deleted = 0 LIMIT 1" );
		if( $query->num_rows() > 0 )
		{
			$insert = $query->row_array();
			$insert['uitzender_id'] = $uitzender_id;
			unset($insert['id']);
			unset($insert['timestamp']);
			$db->insert( 'uitzenders_logo', $insert );
		
			//logo kopieren
			if( $insert['file_name'] !== NULL )
			{
				$old_path = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' .$insert['file_dir'] . '/' . $insert['file_name'];
				$new_path = UPLOAD_DIR .'/werkgever_dir_'. $this->_onderneming['werkgever_id'] .'/' .$insert['file_dir'] . '/' . $insert['file_name'];
				if( file_exists($old_path) && !is_dir($old_path) )
				{
					checkAndCreateDir(UPLOAD_DIR .'/werkgever_dir_'. $this->_onderneming['werkgever_id'] .'/' .$insert['file_dir']);
					copy( $old_path, $new_path );
				}
			}
		}
		
		//user accounts kopieren
		$db->database = 'app_admin';
		$db->close();
		$db->initialize();
		
		unset($insert);
		
		$query = $db->query( "SELECT * FROM users_accounts WHERE uitzender_id = $this->_uitzender_id AND deleted = 0 AND werkgever_id = ".$this->user->werkgever_id );

		foreach( $query->result_array() as $row )
		{
			$row['uitzender_id'] = $uitzender_id;
			$row['werkgever_id'] = $this->_onderneming['werkgever_id'];
			unset($row['id']);
			unset($row['timestamp']);
			$insert[] = $row;
		}
		$db->insert_batch( 'users_accounts', $insert );
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * link switch
	 *
	 * @return string|bool
	 */
	public static function switchUrl()
	{
		$CI =& get_instance();
		$db_admin = $CI->load->database('admin', TRUE);
		
		$werkgever_id_old = $_SESSION['logindata']['prev_werkgever_id'];
		$werkgever_id_new = $_SESSION['logindata']['werkgever_id'];
		
		$url = current_url();
		$new_url = false;

		//uitzender
		if(strpos($url,'uitzenders/dossier/') !== false )
		{
			$new_url = BASE_URL . '/crm/uitzenders';
			
			$parts = explode('/',$url );
			$uitzender_id = end($parts);
			
			//$sql = "SELECT werkgever_id, uitzender_id, user_id FROM users_accounts WHERE werkgever_id = " . $_SESSION['logindata']['prev_werkgever_id']. " AND deleted = 0";
			// user bij uitzender_id zoeken
			$query = $db_admin->query( "SELECT werkgever_id, uitzender_id, user_id FROM users_accounts WHERE uitzender_id = $uitzender_id AND werkgever_id = $werkgever_id_old AND deleted = 0 AND admin = 1 ORDER BY id LIMIT 1" );
			if( $query->num_rows() == 0 ) return $new_url;
			
			$user = $query->row_array();
			
			//nu voor nieuwe werkgeve juiste uitzender ID zoeken
			$query = $db_admin->query( "SELECT werkgever_id, uitzender_id, user_id FROM users_accounts WHERE werkgever_id = $werkgever_id_new AND user_id = ".$user['user_id']." AND deleted = 0 AND admin = 1 ORDER BY id LIMIT 1");
			if( $query->num_rows() == 0 )return $new_url;
			
			$user = $query->row_array();
			
			$new_url = str_replace( $uitzender_id, $user['uitzender_id'], $url);
			
		}
		
		//inlener
		if(strpos($url,'inleners/dossier/') !== false )
		{
			$new_url = BASE_URL . '/crm/inleners';
			
			$parts = explode('/',$url );
			$inlener_id = end($parts);
			
			//$sql = "SELECT werkgever_id, uitzender_id, user_id FROM users_accounts WHERE werkgever_id = " . $_SESSION['logindata']['prev_werkgever_id']. " AND deleted = 0";
			// user bij uitzender_id zoeken
			$query = $db_admin->query( "SELECT werkgever_id, inlener_id, user_id FROM users_accounts WHERE inlener_id = $inlener_id AND werkgever_id = $werkgever_id_old AND deleted = 0 AND admin = 1 ORDER BY id LIMIT 1" );
			if( $query->num_rows() == 0 ) return $new_url;
			
			$user = $query->row_array();
			
			//nu voor nieuwe werkgeve juiste inlener ID zoeken
			$query = $db_admin->query( "SELECT werkgever_id, inlener_id, user_id FROM users_accounts WHERE werkgever_id = $werkgever_id_new AND user_id = ".$user['user_id']." AND deleted = 0 AND admin = 1 ORDER BY id LIMIT 1");
			if( $query->num_rows() == 0 )return $new_url;
			
			$user = $query->row_array();
			
			$new_url = str_replace( $inlener_id, $user['inlener_id'], $url);
			
		}
		
		//werknemers naar zzp
		if(strpos($url,'crm/werknemers') !== false )
			$new_url = 'crm/zzp';
		
		//werknemers naar zzp
		if(strpos($url,'crm/zzp') !== false )
			$new_url = 'crm/werknemers';
		
		return $new_url;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ruimt een temp directory op, standaard ouder dan 2 minuten
	 * @return array
	 */
	public static function switchUitzenderId( $uitzender_id, $werkgever_id_old, $werkgever_id_new )
	{
		$CI =& get_instance();
		$db_admin = $CI->load->database('admin', TRUE);
		
		$query = $db_admin->query( "SELECT werkgever_id, uitzender_id, user_id FROM users_accounts WHERE uitzender_id = $uitzender_id AND werkgever_id = $werkgever_id_old AND deleted = 0 AND admin = 1 ORDER BY id LIMIT 1" );
		if( $query->num_rows() == 0 ) return NULL;
		
		$user = $query->row_array();
		
		//nu voor nieuwe werkgeve juiste uitzender ID zoeken
		$query = $db_admin->query( "SELECT werkgever_id, uitzender_id, user_id FROM users_accounts WHERE werkgever_id = $werkgever_id_new AND user_id = ".$user['user_id']." AND deleted = 0 AND admin = 1 ORDER BY id LIMIT 1");
		if( $query->num_rows() == 0 )return NULL;
		
		$user = $query->row_array();
		
		return $user['uitzender_id'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ruimt een temp directory op, standaard ouder dan 2 minuten
	 * @return array
	 */
	public static function all()
	{
		$CI =& get_instance();
		$db_admin = $CI->load->database('admin', TRUE);
		
		$sql = "SELECT werkgever_id, name, type, db_name FROM werkgevers";
		$query = $db_admin->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$data[$row['werkgever_id']] = $row;
		}
		
		return $data;
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