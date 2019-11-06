<?php

namespace models\Documenten;
use models\Connector;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ID class
 *
 * regelt alles omtrent ID bewijs van werknemer of freelancer
 *
 */
class IDbewijs extends Connector {

	/*
	 * @vars
	 */
	private $_table = 'werknemer_idbewijs';
	private $_key = 'werknemer_id';
	private $_entity_id = NULL;
	
	private $_url_voorkant = NULL;
	private $_url_achterkant = NULL;
	private $_file_voorkant = NULL;
	private $_file_achterkant = NULL;
	
	private $_file_id = NULL;
	
	/*
	 * @var array
	 */
	protected $_error = NULL;



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @param file array
	 * @return $this
	 */
	public function __construct()
	{
		parent::__construct();
		
		
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * entity is werknemer
	 * @return object
	 */
	public function werknemer( $werknemer_id )
	{
		$this->_setTable('werknemers_idbewijs', 'werknemer_id');
		$this->_setEntityID( $werknemer_id );
		
		if( $this->_url_voorkant === NULL )
			$this->_getIDbewijsFromDatabase();
		
		return $this;
	}


	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * save img object to database
	 * @return object
	 */
	public function imgObjectToDatabase( string $side, object $img )
	{
		if( $side == 'voorkant' )
			$side = 1;
		elseif( $side == 'achterkant' )
			$side = 2;
		else
			return NULL;
		
		$sql = "UPDATE $this->_table
				SET
				    file_".$side." = AES_ENCRYPT('".addslashes(file_get_contents( $img->path() ))."', UNHEX(SHA2('".UPLOAD_SECRET."',512))),
				    file_".$side."_size = ".filesize($img->path())."
				WHERE
					$this->_key = $this->_entity_id
				LIMIT 1
				";
		
		$this->db_user->query($sql);
		
		//reload
		$this->_getIDbewijsFromDatabase();
		
		return $this;
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * return url
	 * @return object
	 */
	public function url( $side = 'voorkant' )
	{
		if( $side == 'voorkant' )
			return $this->_url_voorkant;
		
		if( $side == 'achterkant' )
			return $this->_url_achterkant;
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * return image base 64
	 * @return object
	 */
	public function image( $side = 'voorkant', $file_id = NULL )
	{
		//extra beveiliging tegen gokken, update naar md5 hash
		if( $file_id !== NULL )
		{
			if( $file_id != $this->_file_id )
				die('Geen toegang');
		}
		if( $side == 'voorkant' )
			return $this->_file_voorkant;
		
		if( $side == 'achterkant' )
			return $this->_file_achterkant;
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set table voor id bewijs
	 * @return object
	 */
	private function _getIDbewijsFromDatabase()
	{
		$sql = "SELECT
					AES_DECRYPT( $this->_table.file_1, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file_1,
					AES_DECRYPT( $this->_table.file_2, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file_2,
					id
				FROM $this->_table
				WHERE $this->_key = $this->_entity_id AND deleted = 0
				LIMIT 1";
		
		$query = $this->db_user->query( $sql );

		//nieuwe insert
		if( $query->num_rows() == 0 )
		{
			$this->db_user->insert( $this->_table, array( $this->_key => $this->_entity_id, 'user_id' => $this->user->user_id ) );
			return NULL;
		}
		
		$data = $query->row_array();
		
		$this->_file_id = $data['id'];
		
		if( $data['file_1'] !== NULL )
		{
			$this->_file_voorkant = $data['file_1'];
			$this->_url_voorkant = 'image/idbewijs/voorkant/werknemer/' . $this->_entity_id . '/' . $data['id'];
		}
		
		if( $data['file_1'] !== NULL )
		{
			$this->_file_achterkant = $data['file_2'];
			$this->_url_achterkant = 'image/idbewijs/achterkant/werknemer/' . $this->_entity_id . '/' . $data['id'];
		}
	
	}
	
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set table voor id bewijs
	 * @return object
	 */
	private function _setTable( $table, $key )
	{
		$this->_table = $table;
		$this->_key = $key;
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set id voor werknemer of freelancer
	 * @return object
	 */
	private function _setEntityID( $id )
	{
		$this->_entity_id = intval($id);
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