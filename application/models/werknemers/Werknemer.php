<?php

namespace models\werknemers;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class Werknemer extends Connector
{

	private $_status = NULL; // @var array

	public $werknemer_id = NULL; // @var int
	public $naam = NULL; // @var string

	/*
	 * @var array
	 */
	private $_error = NULL;

	public $complete = NULL;
	public $archief = NULL;
	public $gegevens_complete = NULL;
	public $documenten_complete = NULL;
	public $dienstverband_complete = NULL;
	public $verloning_complete = NULL;

	public $next = array();
	public $prev = array();

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct($werknemer_id)
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID($werknemer_id);

		//get status
		$this->getStatus();

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($werknemer_id)
	{
		$this->werknemer_id = intval($werknemer_id);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 */
	public function get($field)
	{
		if (isset($this->_status[$field]))
			return $this->_status[$field];

		return NULL;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get basic info
	 */
	public function getStatus()
	{
		//status opahlen en basis gegevens
		$sql = "SELECT * FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_status.werknemer_id
				WHERE werknemers_gegevens.deleted = 0 AND werknemers_status.werknemer_id = $this->werknemer_id
				LIMIT 1";

		$query = $this->db_user->query($sql);

		//bij leeg alles wel aanmaken
		if ($query->num_rows() == 0)
			return false;

		$this->_status = $query->row_array();
		$this->_status['naam'] = make_name($this->_status);

		$this->complete = $this->_status['complete'];
		$this->archief = $this->_status['archief'];
		$this->gegevens_complete = $this->_status['gegevens_complete'];
		$this->documenten_complete = $this->_status['documenten_complete'];
		$this->dienstverband_complete = $this->_status['dienstverband_complete'];
		$this->verloning_complete = $this->_status['verloning_complete'];

		//set public vars
		$this->naam = $this->_status['naam'];

		//volgende vorige init
		$this->next['id'] = $this->werknemer_id;    //default self
		$this->next['naam'] = $this->naam;  //default self
		$this->prev['id'] = $this->werknemer_id;    //default self
		$this->prev['naam'] = $this->naam;  //default self

		$sql = "SELECT werknemers_status.werknemer_id, werknemers_gegevens.achternaam, werknemers_gegevens.voorletters, werknemers_gegevens.voornaam, werknemers_gegevens.tussenvoegsel
				FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_status.werknemer_id = werknemers_gegevens.werknemer_id
				WHERE ( 
						werknemers_status.werknemer_id = IFNULL((SELECT min(werknemers_status.werknemer_id) FROM werknemers_status WHERE werknemers_status.werknemer_id > $this->werknemer_id AND werknemers_status.archief = 0 AND werknemers_status.complete = 1),0) 
						OR werknemers_status.werknemer_id = IFNULL((SELECT max(werknemers_status.werknemer_id) FROM werknemers_status WHERE werknemers_status.werknemer_id < $this->werknemer_id AND werknemers_status.archief = 0 AND werknemers_status.complete = 1),0)
					  )
				";

		$query = $this->db_user->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row['naam'] = make_name($row);

				if ($row['werknemer_id'] > $this->werknemer_id)
				{
					$this->next['id'] = $row['werknemer_id'];
					$this->next['naam'] = $row['naam'];
				}
				else
				{
					$this->prev['id'] = $row['werknemer_id'];
					$this->prev['naam'] = $row['naam'];
				}
			}
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijsfgegevens
	 */
	public function gegevens()
	{
		$sql = "SELECT * FROM werknemers_gegevens WHERE deleted = 0 AND werknemer_id = $this->werknemer_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		return DBhelper::toRow($query, 'NULL');
	}

	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe werknemer aanmaken
	 * Kan alleen als basis gegevens ingevuld zijn
	 * Entry in status tabel maken, dit levert werknemer_id op
	 * @return boolean
	 */
	public function _new()
	{
		$insert['complete'] = 0;
		$this->db_user->insert('werknemers_status', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$this->werknemer_id = $this->db_user->insert_id();
			return true;
		}

		return false;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla data op na controle
	 * Oude gegevens worden als verwijderd aangemerkt
	 * Geeft ingevoerde data terug
	 * @return array
	 */
	public function _set($table = '', $method = '', $where = NULL)
	{
		$validator = new Validator();
		$validator->table($table)->input($_POST)->run();

		$input = $validator->data();

		//juitse paramter meegeven
		if ($where !== NULL)
			$id = current($where);
		else
			$id = NULL;

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if ($validator->success())
		{
			//nieuwe werknemer aanmaken? Alleen mogelijk vanaf method Bedrijfsgegevens
			if ($this->werknemer_id == 0 && $method == 'gegevens')
			{
				if (!$this->_new())
				{
					$this->_error[] = 'Werknemer kan niet worden aangemaakt';
					return false;
				}
			}

			//zijn er daadwerkelijk wijzigingen?
			if (inputIsDifferent($input, $this->$method($id)))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE $table 
						SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " 
						WHERE deleted = 0 AND werknemer_id = $this->werknemer_id";
				//extra WHERE clause
				if (is_array($where))
					$sql .= " AND " . key($where) . " = " . current($where) . " ";

				$this->db_user->query($sql);

				//alleen wanneer de update lukt om dubbele entries te voorkomen
				if ($this->db_user->affected_rows() != -1)
				{
					//extra veld
					if ($where !== NULL)
						$input[key($where)] = current($where);

					$input['werknemer_id'] = $this->werknemer_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert($table, $input);

					//update status wanneer nodig
					if ($this->complete == 0)
						$this->_updateStatus($method . '_complete');

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
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * return inlener ID'S en bedrijfsnamen als array
	 *
	 */
	public function inleners()
	{
		$sql = "SELECT werknemers_inleners.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam
       			FROM werknemers_inleners
    			LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = werknemers_inleners.inlener_id
    			WHERE inleners_bedrijfsgegevens.deleted = 0 AND werknemers_inleners.deleted = 0 AND werknemer_id = $this->werknemer_id";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[$row['inlener_id']] = $row['bedrijfsnaam'];
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * return inlener ID'S en bedrijfsnamen als array
	 *
	 */
	public function uitzender()
	{
		$sql = "SELECT werknemers_uitzenders.uitzender_id, uitzenders_bedrijfsgegevens.bedrijfsnaam
       			FROM werknemers_uitzenders
    			LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = werknemers_uitzenders.uitzender_id
    			WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND werknemers_uitzenders.deleted = 0 AND werknemer_id = $this->werknemer_id";
		
		$query = $this->db_user->query( $sql );

		if( $query->num_rows() == 0 )
			return NULL;
		
		$row = $query->row_array();
		
		$data['uitzender_id'] = $row['uitzender_id'];
		$data['bedrijfsnaam'] = $row['bedrijfsnaam'];

		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Hang werknemer onder een uitzender
	 *
	 */
	public function setUitzender( $uitzender_id )
	{
		//max aan 1 uitzender
		$sql = "UPDATE werknemers_uitzenders SET deleted = 1,  deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND werknemer_id = $this->werknemer_id";
		$this->db_user->query( $sql );
		
		$insert['werknemer_id'] = $this->werknemer_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['uitzender_id'] = intval($uitzender_id);
		
		$this->db_user->insert( 'werknemers_uitzenders', $insert );
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Update status van een onderdeel en controleer of alles compleet is
	 *
	 */
	public function _updateStatus($property)
	{
		if ($this->$property === NULL)
		{
			//werkgever hoeft niet gecontroleerd te worden
			if ($this->user->user_type == 'werkgever')
				$update_status[$property] = 1;// van leeg naar complete
			else
				$update_status[$property] = 0;// van leeg naar controle
		}
		//alleen werkgever mag controle uitvoeren
		if ($this->$property === 0 && $this->user->user_type == 'werkgever')
			$update_status[$property] = 1;//van controle naar compleet

		//alleen uitvoeren wanneer nodig
		if (isset($update_status))
		{
			$this->$property = $update_status[$property];//update property

			//controle op alle sub statussen
			if (
				$this->gegevens_complete == 1 &&
				$this->documenten_complete == 1 &&
				$this->dienstverband_complete == 1 &&
				$this->verloning_complete == 1
			)
				$update_status['complete'] = 1;

			//update
			$this->db_user->where('werknemer_id', $this->werknemer_id);
			$this->db_user->update('werknemers_status', $update_status);
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Document status naar complete of er weer af
	 *
	 */
	public function documenten_complete( $complete = true )
	{
		if( $complete )
			$this->_updateStatus( 'documenten_complete' );
		else
		{
			$update_status['documenten_complete'] = NULL;
			$update_status['complete'] = 0;
			
			//update
			$this->db_user->where('werknemer_id', $this->werknemer_id);
			$this->db_user->update('werknemers_status', $update_status);
		}
			
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla gegevens op na controle
	 *
	 */
	public function setGegevens()
	{
		$input = $this->_set('werknemers_gegevens', 'gegevens');
		return $input;
	}
	


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
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