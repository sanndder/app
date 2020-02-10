<?php

namespace models\zzp;

use models\Connector;
use models\forms\Valid;
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

class Zzp extends Connector
{

	private $_status = NULL; // @var array

	public $zzp_id = NULL; // @var int
	public $id = NULL; // @var int
	public $naam = NULL; // @var string
	public $bedrijfsnaam = NULL; // @var string
	private $_uitzender_id_new = NULL; // @var int
	
	/*
	 * @var array
	 */
	private $_error = NULL;

	public $complete = NULL;
	public $archief = NULL;
	public $bedrijfsgegevens_complete = NULL;
	public $persoonsgegevens_complete = NULL;
	public $documenten_complete = NULL;
	public $factuurgegevens_complete = NULL;

	public $next = array();
	public $prev = array();

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct($zzp_id)
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID($zzp_id);

		//get status
		$this->getStatus();

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($zzp_id)
	{
		$this->zzp_id = intval($zzp_id);
		$this->id = $this->zzp_id;
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
		$sql = "SELECT * FROM zzp_status
				LEFT JOIN zzp_bedrijfsgegevens ON zzp_bedrijfsgegevens.zzp_id = zzp_status.zzp_id
				LEFT JOIN zzp_persoonsgegevens ON zzp_persoonsgegevens.zzp_id = zzp_status.zzp_id
				WHERE zzp_bedrijfsgegevens.deleted = 0 AND zzp_status.zzp_id = $this->zzp_id
				LIMIT 1";

		$query = $this->db_user->query($sql);

		//bij leeg alles wel aanmaken
		if ($query->num_rows() == 0)
			return false;

		$this->_status = $query->row_array();
		$this->_status['naam'] = make_name($this->_status);

		$this->complete = $this->_status['complete'];
		$this->archief = $this->_status['archief'];
		$this->persoonsgegevens_complete = $this->_status['persoonsgegevens_complete'];
		$this->bedrijfsgegevens_complete = $this->_status['bedrijfsgegevens_complete'];
		$this->documenten_complete = $this->_status['documenten_complete'];
		$this->factuurgegevens_complete = $this->_status['factuurgegevens_complete'];

		//set public vars
		$this->naam = $this->_status['bedrijfsnaam'];
		$this->bedrijfsnaam = $this->_status['bedrijfsnaam'];

		//volgende vorige init
		$this->next['id'] = $this->zzp_id;    //default self
		$this->next['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self
		$this->prev['id'] = $this->zzp_id;    //default self
		$this->prev['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self

		$sql = "SELECT zzp_status.zzp_id, zzp_bedrijfsgegevens.bedrijfsnaam, zzp_persoonsgegevens.*
				FROM zzp_status
				LEFT JOIN zzp_bedrijfsgegevens ON zzp_status.zzp_id = zzp_bedrijfsgegevens.zzp_id
				LEFT JOIN zzp_persoonsgegevens  ON zzp_status.zzp_id = zzp_persoonsgegevens.zzp_id
				WHERE (
						zzp_status.zzp_id = IFNULL((SELECT min(zzp_status.zzp_id) FROM zzp_status WHERE zzp_status.zzp_id > $this->zzp_id AND zzp_status.archief = 0 AND zzp_status.complete = 1),0)
						OR zzp_status.zzp_id = IFNULL((SELECT max(zzp_status.zzp_id) FROM zzp_status WHERE zzp_status.zzp_id < $this->zzp_id AND zzp_status.archief = 0 AND zzp_status.complete = 1),0)
					  )
				";

		$query = $this->db_user->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row['naam'] = make_name($row);

				if ($row['zzp_id'] > $this->zzp_id)
				{
					$this->next['id'] = $row['zzp_id'];
					$this->next['naam'] = $row['naam'];
				}
				else
				{
					$this->prev['id'] = $row['zzp_id'];
					$this->prev['naam'] = $row['naam'];
				}
			}
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get persoonsgegevens
	 */
	public function persoonsgegevens()
	{
		$sql = "SELECT * FROM zzp_persoonsgegevens WHERE deleted = 0 AND zzp_id = $this->zzp_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		return DBhelper::toRow($query, 'NULL');
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijfsgegevens
	 */
	public function bedrijfsgegevens()
	{
		$sql = "SELECT * FROM zzp_bedrijfsgegevens WHERE deleted = 0 AND zzp_id = $this->zzp_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		return DBhelper::toRow($query, 'NULL');
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 *
	 */
	public function setBedrijfsgegevens()
	{
		$input = $this->_set('zzp_bedrijfsgegevens', 'bedrijfsgegevens');
		return $input;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla persoonsgegevens op na controle
	 *
	 */
	public function setPersoonsgegevens()
	{
		$input = $this->_set('zzp_persoonsgegevens', 'persoonsgegevens');
		return $input;
	}
	
		/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factuurgegevens op na controle
	 * @return array
	 */
	public function setFactuurgegevens()
	{
		$input = $this->_set('zzp_factuurgegevens', 'factuurgegevens');
		return $input;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factuurgegevens
	 */
	public function factuurgegevens()
	{
		$sql = "SELECT * FROM zzp_factuurgegevens WHERE deleted = 0 AND zzp_id = $this->zzp_id LIMIT 1";
		$query = $this->db_user->query($sql);
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe werknemer aanmaken
	 * Kan alleen als basis gegevens ingevuld zijn
	 * Entry in status tabel maken, dit levert zzp_id op
	 * @return boolean
	 */
	public function _new()
	{
		$insert['complete'] = 0;
		$this->db_user->insert('zzp_status', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$this->zzp_id = $this->db_user->insert_id();
			
			//koppeling uitzender aanmaken indien gewenst
			if( $this->_uitzender_id_new !== NULL )
				$this->setUitzender( $this->_uitzender_id_new );
			
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
		//uitzender ID loskoppelen
		if( isset($_POST['uitzender_id']) && intval($_POST['uitzender_id']) > 0 )
			$this->_uitzender_id_new = intval($_POST['uitzender_id']);
		
		//altijd er uit
		unset($_POST['uitzender_id']);
		
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
			if ($this->zzp_id == 0 && $method == 'bedrijfsgegevens')
			{
				if (!$this->_new())
				{
					$this->_error[] = "ZZP'er kan niet worden aangemaakt";
					return false;
				}
			}

			//zijn er daadwerkelijk wijzigingen?
			if (inputIsDifferent($input, $this->$method($id)))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE $table
						SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . "
						WHERE deleted = 0 AND zzp_id = $this->zzp_id";
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

					$input['zzp_id'] = $this->zzp_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert($table, $input);				}
				else
				{
					$this->_error[] = 'Database error: update mislukt';
				}
			}
			
			//update status wanneer nodig
			if ($this->complete == 0)
				$this->_updateStatus($method . '_complete');
			
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
		
		//eventueel uitzender_id mee teruggeven voor eerste aanmelding
		if( $this->_uitzender_id_new !== NULL )
			$input['uitzender_id'] = $this->_uitzender_id_new;

		return $input;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * return inlener ID'S en bedrijfsnamen als array
	 *
	 */
	public function inleners()
	{
		$sql = "SELECT zzp_inleners.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam
       			FROM zzp_inleners
    			LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = zzp_inleners.inlener_id
    			WHERE inleners_bedrijfsgegevens.deleted = 0 AND zzp_inleners.deleted = 0 AND zzp_id = $this->zzp_id";
		
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
		$sql = "SELECT zzp_uitzenders.uitzender_id, uitzenders_bedrijfsgegevens.bedrijfsnaam
       			FROM zzp_uitzenders
    			LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = zzp_uitzenders.uitzender_id
    			WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND zzp_uitzenders.deleted = 0 AND zzp_id = $this->zzp_id";
		
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
	 * uitzender ID
	 *
	 */
	public function uitzenderID()
	{
		$uitzenders = $this->uitzender();
		if( $uitzenders === NULL )
			return NULL;
		
		return $uitzenders['uitzender_id'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Hang werknemer onder een uitzender
	 *
	 */
	public function setUitzender( $uitzender_id )
	{
		//max aan 1 uitzender
		$sql = "UPDATE zzp_uitzenders SET deleted = 1,  deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND zzp_id = $this->zzp_id";
		$this->db_user->query( $sql );
		
		$insert['zzp_id'] = $this->zzp_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['uitzender_id'] = intval($uitzender_id);
		
		$this->db_user->insert( 'zzp_uitzenders', $insert );
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Update status van een onderdeel en controleer of alles compleet is
	 *
	 */
	private function _updateStatus($property)
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
		if ($this->$property === '0' && $this->user->user_type == 'werkgever')
			$update_status[$property] = 1;//van controle naar compleet

		//alleen uitvoeren wanneer nodig
		if (isset($update_status))
		{
			$this->$property = $update_status[$property];//update property

			//controle op alle sub statussen
			if (
				$this->persoonsgegevens_complete == 1 &&
				$this->bedrijfsgegevens_complete == 1 &&
				$this->documenten_complete == 1 &&
				$this->factuurgegevens_complete == 1
			)
				$update_status['complete'] = 1;

			//update
			$this->db_user->where('zzp_id', $this->zzp_id);
			$this->db_user->update('zzp_status', $update_status);
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
			$this->db_user->where('zzp_id', $this->zzp_id);
			$this->db_user->update('zzp_status', $update_status);
		}
		
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * TODO: weghalen
	 *
	 */
	public function contract()
	{
		$sql = "SELECT * FROM documenten WHERE deleted = 0 AND zzp_id = $this->zzp_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		$data = $query->row_array();
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