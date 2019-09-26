<?php

namespace models\Inleners;

use models\Connector;
use models\Forms\Validator;
use models\Utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Inlener class
 *
 *
 *
 */

class Inlener extends Connector
{

	private $_status = NULL; // @var array

	public $inlener_id = NULL; // @var int
	public $bedrijfsnaam = NULL; // @var string

	public $contacten = NULL; // @var string


	/*
	 * @var array
	 */
	private $_error = NULL;

	public $complete = NULL;
	public $archief = NULL;
	public $emailadressen_complete = NULL;
	public $contactpersoon_complete = NULL;
	public $factuurgegevens_complete = NULL;
	public $bedrijfsgegevens_complete = NULL;

	public $next = array();
	public $prev = array();

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct($inlener_id)
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID($inlener_id);

		//get status
		$this->getStatus();

	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($inlener_id)
	{
		$this->inlener_id = intval($inlener_id);
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 */
	public function get($field)
	{
		if (isset($this->_status[$field]))
			return $this->_status[$field];

		return NULL;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get basic info
	 */
	public function getStatus()
	{
		//status opahlen en basis gegevens
		$sql = "SELECT * FROM inleners_status
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_status.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_status.inlener_id = $this->inlener_id
				LIMIT 1";

		$query = $this->db_user->query($sql);

		//bij nu afbreken
		if ($query->num_rows() == 0)
			return false;

		$this->_status = $query->row_array();

		$this->complete = $this->_status['complete'];
		$this->archief = $this->_status['archief'];
		$this->bedrijfsgegevens_complete = $this->_status['bedrijfsgegevens_complete'];
		$this->factuurgegevens_complete = $this->_status['factuurgegevens_complete'];
		$this->contactpersoon_complete = $this->_status['contactpersoon_complete'];
		$this->emailadressen_complete = $this->_status['emailadressen_complete'];

		//set public vars
		$this->bedrijfsnaam = $this->_status['bedrijfsnaam'];

		//volgende vorige init
		$this->next['id'] 			= $this->inlener_id;    //default self
		$this->next['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self
		$this->prev['id'] 			= $this->inlener_id;    //default self
		$this->prev['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self

		$sql = "SELECT inleners_status.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam FROM inleners_status 
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_status.inlener_id = inleners_bedrijfsgegevens.inlener_id
				WHERE ( 
						inleners_status.inlener_id = IFNULL((SELECT min(inleners_status.inlener_id) FROM inleners_status WHERE inleners_status.inlener_id > $this->inlener_id AND inleners_status.archief = 0 AND inleners_status.complete = 1),0) 
						OR inleners_status.inlener_id = IFNULL((SELECT max(inleners_status.inlener_id) FROM inleners_status WHERE inleners_status.inlener_id < $this->inlener_id AND inleners_status.archief = 0 AND inleners_status.complete = 1),0)
					  )
				";

		$query = $this->db_user->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if ($row['inlener_id'] > $this->inlener_id)
				{
					$this->next['id'] = $row['inlener_id'];
					$this->next['bedrijfsnaam'] = $row['bedrijfsnaam'];
				}
				else
				{
					$this->prev['id'] = $row['inlener_id'];
					$this->prev['bedrijfsnaam'] = $row['bedrijfsnaam'];
				}
			}
		}
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factuurgegevens
	 */
	public function factuurgegevens()
	{
		$sql = "SELECT * FROM inleners_factuurgegevens WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		return $query->row_array();
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijsfgegevens
	 */
	public function bedrijfsgegevens()
	{
		$sql = "SELECT * FROM inleners_bedrijfsgegevens WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		return $query->row_array();
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersonen
	 */
	public function contactpersonen()
	{
		$sql = "SELECT * FROM inleners_contactpersonen WHERE deleted = 0 AND inlener_id = $this->inlener_id ORDER BY achternaam ASC, voorletters ASC";
		$query = $this->db_user->query($sql);

		$data = DBhelper::toArray( $query, 'contact_id', 'NULL' );
		return $data;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get emailadressen
	 */
	public function emailadressen()
	{
		$sql = "SELECT * FROM inleners_emailadressen WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		return $query->row_array();
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersoon
	 */
	public function contactpersoon($contact_id)
	{
		$sql = "SELECT * FROM inleners_contactpersonen WHERE contact_id = $contact_id AND deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		$row = $query->row_array();
		$row['naam'] = make_name($row);

		return $row;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factoren
	 */
	public function factoren()
	{
		$sql = "SELECT factor_normaal, factor_overuren FROM inleners_factoren WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		$row = $query->row_array();

		return $row;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get logo
	 */
	public function logo( $method = 'path' )
	{
		$sql = "SELECT * FROM inleners_logo WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		$row = $query->row_array();

		//full path
		$file_path =  UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $row['file_dir'] . '/' . $row['file_name'];

		//check
		if( !file_exists($file_path))
			return false;

		if( $method == 'path' )
			return $file_path;

		if( $method == 'url' )
			return 'image/logoinlener/' . $this->inlener_id . '?' . $row['id'];

		return $row;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get handtekening
	 */
	public function handtekening( $method = 'path' )
	{
		$sql = "SELECT AES_DECRYPT( inleners_handtekening.file, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file, inlener_id
				FROM inleners_handtekening 
				WHERE inlener_id = $this->inlener_id AND deleted = 0 LIMIT 1";

		$sql = "SELECT AES_DECRYPT( inleners_handtekening.file, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file, id 
				FROM inleners_handtekening 
				WHERE inlener_id = $this->inlener_id AND deleted = 0
				LIMIT 1";

		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		 	return NULL;

		$data = $query->row_array();

		if( $method == 'url' )
			return 'image/handtekeninginlener/' . $this->inlener_id . '?' . $data['id'];


		if( $method == 'path' )
			return $data['file'];

		//echo "<img src='data:image/jpeg;base64," . base64_encode( $data['file'] )."'>";
		//die();

	}


	

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe inlener aanmaken
	 * Kan alleen als basis bedrijfsgegevens ingevuld zijn
	 * Entry in status tabel maken, dit levert inlener_id op
	 * @return boolean
	 */
	public function _new()
	{
		$insert['complete'] = 0;
		$this->db_user->insert('inleners_status', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$this->inlener_id = $this->db_user->insert_id();
			return true;
		}

		return false;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
			//nieuwe inlener aanmaken? Alleen mogelijk vanaf method Bedrijfsgegevens
			if ($this->inlener_id == 0 && $method == 'bedrijfsgegevens')
			{
				if (!$this->_new())
				{
					$this->_error[] = 'Inlener kan niet worden aangemaakt';
					return false;
				}
			}

			//zijn er daadwerkelijk wijzigingen?
			if (inputIsDifferent($input, $this->$method($id)))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE $table 
						SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " 
						WHERE deleted = 0 AND inlener_id = $this->inlener_id";
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

					$input['inlener_id'] = $this->inlener_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert($table, $input);

					//update status wanneer nodig
					if( $this->complete == 0 )
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


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
				$this->bedrijfsgegevens_complete == 1 &&
				$this->emailadressen_complete == 1 &&
				$this->factuurgegevens_complete == 1 &&
				$this->contactpersoon_complete == 1
			)
				$update_status['complete'] = 1;

			//update
			$this->db_user->where('inlener_id', $this->inlener_id);
			$this->db_user->update('inleners_status', $update_status);
		}
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factoren op na controle
	 *
	 */
	public function setFactoren()
	{
		$input = $this->_set('inleners_factoren', 'factoren');
		return $input;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 *
	 */
	public function setBedrijfsgegevens()
	{
		$input = $this->_set('inleners_bedrijfsgegevens', 'bedrijfsgegevens');
		return $input;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla emailadressen op na controle
	 * @return array
	 */
	public function setEmailadressen()
	{
		$input = $this->_set('inleners_emailadressen', 'emailadressen');
		return $input;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factuurgegevens op na controle
	 * @return array
	 */
	public function setFactuurgegevens()
	{
		$input = $this->_set('inleners_factuurgegevens', 'factuurgegevens');
		return $input;

	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla contactpersoon op na controle
	 * Maak eventueel een nieuw contact aan
	 * @return array
	 */
	public function setContactpersoon($contact_id)
	{
		$new = false;

		//nieuw contact aanmaken indien nodig
		if ($contact_id == 0)
		{
			//hoogste contact id ophalen, autoincrement is niet van toepassing (zit op andere kolom)
			$sql = "SELECT MAX(contact_id) AS contact_id FROM inleners_contactpersonen";
			$query = $this->db_user->query($sql);

			$data = $query->row_array();

			$insert['contact_id'] = $data['contact_id'] + 1;
			$insert['inlener_id'] = $this->inlener_id;
			$this->db_user->insert('inleners_contactpersonen', $insert);

			if ($this->db_user->insert_id() > 0)
			{
				$new = true;
				$contact_id = $insert['contact_id'];
			}

		}

		$input = $this->_set('inleners_contactpersonen', 'contactpersoon', array('contact_id' => $contact_id));

		//zijn er erros, dan weer uit de database
		if ($new == true && $this->errors() !== false)
		{
			$sql = "DELETE FROM inleners_contactpersonen WHERE contact_id = $contact_id LIMIT 1";
			$this->db_user->query($sql);
		}

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