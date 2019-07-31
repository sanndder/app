<?php

namespace models\Uitzenders;

use models\Connector;
use models\Forms\Validator;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Uitzender class
 *
 *
 *
 */
class Uitzender extends Connector {

	private $_info = NULL; // @var array

	public $uitzender_id = NULL; // @var int
	public $bedrijfsnaam = NULL; // @var string

	public $contacten = NULL; // @var string


	/*
	 * @var array
	 */
	private $_error = NULL;


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $uitzender_id )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID( $uitzender_id );

		//get info
		$this->getInfo();

	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID( $uitzender_id )
	{
		$this->uitzender_id = intval($uitzender_id);
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 */
	public function get( $field )
	{
		if( isset($this->_info[$field]) )
			return $this->_info[$field];

		return NULL;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get basic info
	 */
	public function getInfo()
	{
		$sql = "SELECT * FROM uitzenders_status
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_status.uitzender_id
				WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND uitzenders_status.uitzender_id = $this->uitzender_id
				LIMIT 1";

		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		$this->_info = $query->row_array();

		//set public vars
		$this->bedrijfsnaam = $this->_info['bedrijfsnaam'];

	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijsfgegevens
	 */
	public function factuurgegevens()
	{
		$sql = "SELECT * FROM uitzenders_factuurgegevens WHERE deleted = 0 AND uitzender_id = $this->uitzender_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ( $query->num_rows() == 0 )
			return NULL;

		return $query->row_array();
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijsfgegevens
	 */
	public function bedrijfsgegevens()
	{
		$sql = "SELECT * FROM uitzenders_bedrijfsgegevens WHERE deleted = 0 AND uitzender_id = $this->uitzender_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ( $query->num_rows() == 0 )
			return NULL;

		return $query->row_array();
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersonen
	 */
	public function contactpersonen()
	{
		$sql = "SELECT * FROM uitzenders_contactpersonen WHERE deleted = 0 AND uitzender_id = $this->uitzender_id ORDER BY achternaam ASC, voorletters ASC";
		$query = $this->db_user->query($sql);

		if ( $query->num_rows() == 0 )
			return NULL;

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$data[$row['contact_id']] = $row;
		}

		return $data;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersonen
	 */
	public function emailadressen()
	{
		$sql = "SELECT * FROM uitzenders_emailadressen WHERE deleted = 0 AND uitzender_id = $this->uitzender_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ( $query->num_rows() == 0 )
			return NULL;

		return $query->row_array();
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersonen
	 */
	public function contactpersoon( $contact_id )
	{
		$sql = "SELECT * FROM uitzenders_contactpersonen WHERE contact_id = $contact_id AND deleted = 0 AND uitzender_id = $this->uitzender_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ( $query->num_rows() == 0 )
			return NULL;

		$row = $query->row_array();
		$row['naam'] = make_name($row);

		return $row;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla data op na controle
	 * Oude gegevens worden als verwijderd aangemerkt
	 * Geeft ingevoerde data terug
	 * @return array
	 */
	public function _set( $table = '', $class = '', $where = NULL )
	{
		$validator = new Validator();
		$validator->table( $table )->input( $_POST )->run();

		$input = $validator->data();

		//juitse paramter meegeven
		if( $where !== NULL)
			$id = current($where);
		else
			$id = NULL;

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//zijn er daadwerkelijk wijzigingen?
			if( inputIsDifferent( $input, $this->$class( $id ) ))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE $table 
						SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." 
						WHERE deleted = 0 AND uitzender_id = $this->uitzender_id";
				//extra WHERE clause
				if(is_array($where))
					$sql .= " AND ".key($where)." = ".current($where)." ";

				$this->db_user->query($sql);

				//alleen wanneer de update lukt om dubbele entries te voorkomen
				if ($this->db_user->affected_rows() != -1)
				{
					//extra veld
					if( $where !== NULL)
						$input[key($where)] = current($where);

					$input['uitzender_id'] = $this->uitzender_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert($table, $input);
				}
				else
				{
					$this->_error[] = 'Database error: update mislukt';
				}

			}
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}

		return $input;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 *
	 */
	public function setBedrijfsgegevens()
	{
		$input = $this->_set( 'uitzenders_bedrijfsgegevens', 'bedrijfsgegevens' );
		return $input;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla emailadresseb op na controle
	 *
	 */
	public function setEmailadressen()
	{
		$input = $this->_set( 'uitzenders_emailadressen', 'emailadressen' );
		return $input;
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factuurgegevens op na controle
	 * @return array
	 */
	public function setFactuurgegevens()
	{
		$input = $this->_set( 'uitzenders_factuurgegevens', 'factuurgegevens' );
		return $input;

	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factuurgegevens op na controle
	 * @return array
	 */
	public function setContactpersoon( $contact_id )
	{
		$input = $this->_set( 'uitzenders_contactpersonen', 'contactpersoon', array( 'contact_id' => $contact_id ) );
		return $input;
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