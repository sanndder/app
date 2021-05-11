<?php

namespace models\werknemers;

use models\Connector;
use models\forms\Valid;
use models\forms\Validator;
use models\utils\DBhelper;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Werknemer class
 *
 *
 *
 */

class Et extends Connector
{
	/**
	 * @var int
	 */
	private $_file_id = NULL;
	private $_dagtekening = NULL;
	private $_werknemer_id;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct( $werknemer_id )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		$this->setID( $werknemer_id );
		
		$this->init();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * databse vullen wanneer ET wordt aangezet
	 *
	 */
	public function init()
	{
		//instellingen
		$query = $this->db_user->query( "SELECT id FROM werknemer_et_settings WHERE werknemer_id = $this->_werknemer_id LIMIT 1" );
		if( $query->num_rows() == 0 )
		{
			$insert['werknemer_id'] = $this->_werknemer_id;
			$this->db_user->insert( 'werknemer_et_settings', $insert );
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Alles ingevuld?
	 *
	 */
	public function isComplete()
	{
		if( $this->verblijf() !== NULL && $this->_dagtekening != NULL && $this->fileBsn() !== NULL )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Set ID
	 *
	 */
	public function setID( $werknemer_id )
	{
		$this->_werknemer_id = intval( $werknemer_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * upload bsn ophalen
	 *
	 * @return string|void
	 */
	public function fileBsn()
	{
		$sql = "SELECT * FROM werknemer_et_bsn WHERE deleted = 0 AND werknemer_id = $this->_werknemer_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$row = $query->row_array();
		
		//full path
		$file_path = UPLOAD_DIR . '/werkgever_dir_' . $this->user->werkgever_id . '/' . $row['file_dir'] . '/' . $row['file_name'];
		
		//check
		if( !file_exists( $file_path ) )
			return NULL;
		
		$this->_dagtekening = $row['dagtekening'];
		$this->_file_id = $row['id'];
		return $file_path;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 *  get file ID
	 *
	 */
	public function fileID()
	{
		return $this->_file_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 *  del bsn file
	 */
	public function delbsn()
	{
		$this->db_user->query( "UPDATE werknemer_et_bsn SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND werknemer_id = $this->_werknemer_id" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Instellingen
	 *
	 * @return array
	 */
	public function settings()
	{
		$query = $this->db_user->query( "SELECT * FROM werknemer_et_settings WHERE werknemer_id = $this->_werknemer_id AND deleted = 0" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * verblijf
	 *
	 * @return array
	 */
	public function verblijf()
	{
		$query = $this->db_user->query( "SELECT * FROM werknemer_et_verblijf WHERE werknemer_id = $this->_werknemer_id AND deleted = 0" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set verblijf
	 *
	 * @return array
	 */
	public function setVerblijf()
	{
		
		$validator = new Validator();
		$validator->table( 'werknemers_et_verblijf' )->input( $_POST )->run();
		
		$input = $validator->data();

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//zijn er daadwerkelijk wijzigingen?
			if( inputIsDifferent( $input, $this->verblijf() ) )
			{
				//alle vorige entries als deleted
				$sql = "UPDATE werknemer_et_verblijf SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND werknemer_id = $this->_werknemer_id";
				$this->db_user->query( $sql );
				
				//alleen wanneer de update lukt om dubbele entries te voorkomen
				if( $this->db_user->affected_rows() != -1 )
				{
					$input['werknemer_id'] = $this->_werknemer_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert( 'werknemer_et_verblijf', $input );
					
					if( $this->db_user->insert_id() > 0 )
						$this->_insert_id = $this->db_user->insert_id();
					else
						$this->_error[] = 'Database error: insert mislukt';
				} else
				{
					$this->_error[] = 'Database error: update mislukt';
				}
			}
		} //fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
		
		return $input;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * validate dagtekening
	 * @return bool
	 */
	public function _validateDagtekening( $datum ): bool
	{
		//to internatiol format
		$datum = reverseDate( $datum );
		
		//valid date
		if( !Valid::date( $datum ) )
		{
			$this->_error[] = 'Ingevoerde datum is ongeldig';
			return false;
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * dagtekening opslaan
	 *
	 * @return bool
	 */
	public function setDagtekening( $datum )
	{
		if( $this->_validateDagtekening( $datum ) === false )
			return false;
		
		$update['dagtekening'] = reverseDate( $datum );
		$this->db_user->where( 'id', $this->_file_id );
		$this->db_user->update( 'werknemer_et_bsn', $update );
		
		$this->_dagtekening = $update['dagtekening'];
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * dagtekening ophalen
	 *
	 * @return ?string
	 */
	public function dagtekening()
	{
		return $this->_dagtekening;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset( $_GET['debug'] ) )
		{
			if( $this->_error === NULL )
				show( 'Geen errors' );
			else
				show( $this->_error );
		}
		
		if( $this->_error === NULL )
			return false;
		
		return $this->_error;
	}
}

?>