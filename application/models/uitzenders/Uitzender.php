<?php

namespace models\uitzenders;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Ondernemingen;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Uitzender class
 *
 *
 *
 */

class Uitzender extends Connector
{

	private $_status = NULL; // @var array

	public $uitzender_id = NULL; // @var int
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
	public $aanmeld_ip = NULL;

	public $next = array();
	public $prev = array();

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct($uitzender_id)
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID($uitzender_id);

		//get status
		$this->getStatus();

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Haal gekoppelde ondernemingen op
	 * TODO: verplaatsen naar ondernemingen
	 */
	public function ondernemingen()
	{
		$bedrijfsgegevens = $this->bedrijfsgegevens();
		
		$ondernemingen = Ondernemingen::all();
		
		$CI =& get_instance();
		$db = $CI->load->database('admin', TRUE);
		
		//kijken waar kvk bestaat
		foreach( $ondernemingen as $o )
		{
			$db->database = $o['db_name'];
			$db->close();
			$db->initialize();
			
			$sql = "SELECT uitzenders_status.archief, uitzenders_bedrijfsgegevens.bedrijfsnaam, uitzenders_bedrijfsgegevens.kvknr
					FROM uitzenders_status
					LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_status.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
					WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND uitzenders_bedrijfsgegevens.kvknr = '".$bedrijfsgegevens['kvknr']."' LIMIT 1";
			$query = $db->query( $sql );
			
			$uitzender = $query->row_array();
			
			if( is_array($uitzender))
				$ondernemingen[$o['werkgever_id']] = $ondernemingen[$o['werkgever_id']] + $uitzender;
		}
		
		return $ondernemingen;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Kopieer uitzender
	 *
	 */
	public function copyToOndernemingen( int $werkgever_id ) :bool
	{
		//chekc for dubbel en rechten
		$ondernemingen = $this->ondernemingen();
		
		if( !isset($ondernemingen[$werkgever_id]) )
		{
			$this->_error[] = '<b>KOPPELING MISLUKT:</b> Onderneming niet gevonden';
			return false;
		}
		
		if( isset($ondernemingen[$werkgever_id]['bedrijfsnaam']) )
		{
			$this->_error[] = '<b>KOPPELING MISLUKT:</b> Onderneming is al gekoppeld';
			return false;
		}
		
		$ondernemingenHelper = new Ondernemingen();
		
		if( !$ondernemingenHelper->uitzender( $this->uitzender_id )->copy( $ondernemingen[$werkgever_id] ) )
		{
			$this->_error[] = $ondernemingenHelper->errors();
			return false;
		}
		
		return true;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($uitzender_id)
	{
		$this->uitzender_id = intval($uitzender_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 *
	 */
	public function setArchief( $archief )
	{
		//naar archief
		if( $archief )
		{
			$update['archief'] = 1;
		}
		
		//uit archief
		if( !$archief )
		{
			$update['archief'] = 0;
		}
		
		$update['last_update_by'] = $this->user->id;
		
		$this->db_user->where( 'uitzender_id', $this->uitzender_id );
		$this->db_user->update( 'uitzenders_status', $update );
		
		$this->archief = $update['archief'];
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ALLEEN VOOR DEVOLPMENT
	 * TODO: verwijderen
	 */
	public function del( $id )
	{
		if( $this->user->user_id != 2 )
			die('Geen toegand');
		

		$this->db_user->query( "DELETE FROM inleners_uitzenders WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM uitzenders_bedrijfsgegevens WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM uitzenders_av_accepted WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_contactpersonen WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_emailadressen WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_factoren WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_factuurgegevens WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_handtekening WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_last_visited WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_uitzenders WHERE uitzender_id = $id" );
		$this->db_user->query( "DELETE FROM	uitzenders_status WHERE uitzender_id = $id" );
		
		
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
		$sql = "SELECT * FROM uitzenders_status
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_status.uitzender_id
				WHERE uitzenders_bedrijfsgegevens.deleted = 0 AND uitzenders_status.uitzender_id = $this->uitzender_id
				LIMIT 1";

		$query = $this->db_user->query($sql);

		//bij leeg alles wel aanmaken
		if ($query->num_rows() == 0)
			return false;

		$this->_status = $query->row_array();

		$this->complete = $this->_status['complete'];
		$this->archief = $this->_status['archief'];
		$this->bedrijfsgegevens_complete = $this->_status['bedrijfsgegevens_complete'];
		$this->factuurgegevens_complete = $this->_status['factuurgegevens_complete'];
		$this->contactpersoon_complete = $this->_status['contactpersoon_complete'];
		$this->emailadressen_complete = $this->_status['emailadressen_complete'];
		$this->aanmeld_ip = $this->_status['ip'];

		//set public vars
		$this->bedrijfsnaam = $this->_status['bedrijfsnaam'];

		//volgende vorige init
		$this->next['id'] 			= $this->uitzender_id;    //default self
		$this->next['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self
		$this->prev['id'] 			= $this->uitzender_id;    //default self
		$this->prev['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self

		$sql = "SELECT uitzenders_status.uitzender_id, uitzenders_bedrijfsgegevens.bedrijfsnaam FROM uitzenders_status 
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_status.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
				WHERE ( 
						uitzenders_status.uitzender_id = IFNULL((SELECT min(uitzenders_status.uitzender_id) FROM uitzenders_status WHERE uitzenders_status.uitzender_id > $this->uitzender_id AND uitzenders_status.archief = 0 AND uitzenders_status.complete = 1),0) 
						OR uitzenders_status.uitzender_id = IFNULL((SELECT max(uitzenders_status.uitzender_id) FROM uitzenders_status WHERE uitzenders_status.uitzender_id < $this->uitzender_id AND uitzenders_status.archief = 0 AND uitzenders_status.complete = 1),0)
					  )
				";

		$query = $this->db_user->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if ($row['uitzender_id'] > $this->uitzender_id)
				{
					$this->next['id'] = $row['uitzender_id'];
					$this->next['bedrijfsnaam'] = $row['bedrijfsnaam'];
				}
				else
				{
					$this->prev['id'] = $row['uitzender_id'];
					$this->prev['bedrijfsnaam'] = $row['bedrijfsnaam'];
				}
			}
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factuurgegevens
	 */
	public function factuurgegevens()
	{
		return $this->select_row( 'uitzenders_factuurgegevens', array( 'uitzender_id' => $this->uitzender_id ) );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijfsgegevens
	 */
	public function bedrijfsgegevens()
	{
		return $this->select_row( 'uitzenders_bedrijfsgegevens', array( 'uitzender_id' => $this->uitzender_id ) );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Korting per inlener ophalen
	 */
	public function factuurKorting()
	{
		$query = $this->db_user->query( "SELECT * FROM uitzenders_korting WHERE uitzender_id = ? AND deleted = 0", array( $this->uitzender_id ) );
		return DBhelper::toArray( $query, 'inlener_id', 'NULL' );
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersonen
	 */
	public function contactpersonen()
	{
		$query = $this->db_user->query( "SELECT * FROM uitzenders_contactpersonen WHERE deleted = 0 AND uitzender_id = $this->uitzender_id ORDER BY achternaam ASC, voorletters ASC" );
		return DBhelper::toArray( $query, 'contact_id', 'NULL' );
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get emailadressen
	 */
	public function emailadressen()
	{
		return $this->select_row( 'uitzenders_emailadressen', array( 'uitzender_id' => $this->uitzender_id ) );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get emailadressen
	 */
	public function systeeminstellingen()
	{
		return $this->select_row( 'uitzenders_systeeminstellingen', array( 'uitzender_id' => $this->uitzender_id ) );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get contactpersonen
	 */
	public function contactpersoon($contact_id)
	{
		$sql = "SELECT * FROM uitzenders_contactpersonen WHERE contact_id = $contact_id AND deleted = 0 AND uitzender_id = $this->uitzender_id LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return NULL;

		$row = $query->row_array();
		$row['naam'] = make_name($row);

		return $row;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factoren
	 */
	public function factoren()
	{
		return $this->select_row( 'uitzenders_factoren', array( 'uitzender_id' => $this->uitzender_id ) );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get logo
	 */
	public function logo( $method = 'path' )
	{
		$sql = "SELECT * FROM uitzenders_logo WHERE deleted = 0 AND uitzender_id = $this->uitzender_id LIMIT 1";
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
			return 'image/logouitzender/' . $this->uitzender_id . '?' . $row['id'];

		return $row;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get handtekeneing
	 */
	public function handtekening( $method = 'path' )
	{
		$sql = "SELECT AES_DECRYPT( uitzenders_handtekening.file, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file, id 
				FROM uitzenders_handtekening 
				WHERE uitzender_id = $this->uitzender_id AND deleted = 0
				LIMIT 1";

		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		 	return NULL;

		$data = $query->row_array();

		if( $method == 'url' )
			return 'image/handtekeninguitzender/' . $this->uitzender_id . '?' . $data['id'];

		if( $method == 'path' )
			return $data['file'];

		//echo "<img src='data:image/jpeg;base64," . base64_encode( $data['file'] )."'>";
		//die();

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  del logo
	 */
	public function delLogo()
	{
		return $this->delete_row( 'uitzenders_logo', array( 'uitzender_id' => $this->uitzender_id ) );
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  del handtekening
	 */
	public function delHandtekening()
	{
		return $this->delete_row( 'uitzenders_handtekening', array( 'uitzender_id' => $this->uitzender_id ) );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe uitzender aanmaken
	 * Kan alleen als basis bedrijfsgegevens ingevuld zijn
	 * Entry in status tabel maken, dit levert uitzender_id op
	 * @return boolean
	 */
	public function _new()
	{
		$insert['complete'] = 0;
		$insert['ip'] = $_SERVER['REMOTE_ADDR'];
		$this->db_user->insert('uitzenders_status', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$this->uitzender_id = $this->db_user->insert_id();
			$this->_setCookie();
			
			return true;
		}

		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Cookie aanmaken met uitzender ID
	 * @return void
	 */
	public function _setCookie()
	{
		set_cookie('new_uitzender_id', $this->uitzender_id, 60*60*48 );//expire na 48 uur
	}
	
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla data op na controle
	 * Oude gegevens worden als verwijderd aangemerkt
	 * Geeft ingevoerde data terug
	 * @return array|boolean
	 */
	public function _set($table = '', $method = '', $where = NULL)
	{
		$validator = new Validator();
		$validator->table($table)->input($_POST)->run();

		$input = $validator->data();

		//juitse paramter meegeven
		if ( $where !== NULL )
			$id = current($where);
		else
			$id = NULL;
		
		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if ($validator->success())
		{
			//nieuwe uitzender aanmaken? Alleen mogelijk vanaf method Bedrijfsgegevens
			if ($this->uitzender_id == 0 && $method == 'bedrijfsgegevens')
			{
				if (!$this->_new())
				{
					$this->_error[] = 'Uitzender kan niet worden aangemaakt';
					return false;
				}
			}

			//zijn er daadwerkelijk wijzigingen?
			if (inputIsDifferent($input, $this->$method($id)))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE $table 
						SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " 
						WHERE deleted = 0 AND uitzender_id = $this->uitzender_id";
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

					$input['uitzender_id'] = $this->uitzender_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert($table, $input);
					
				}
				else
				{
					$this->_error[] = 'Database error: update mislukt';
				}

			}
			
			//update status wanneer nodig
			if( $this->complete == 0 )
				$this->_updateStatus($method . '_complete');
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
		if ($this->$property == 0 && $this->user->user_type == 'werkgever')
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
			{
				$update_status['complete'] = 1;
				
				//coockie weghalen, geen fouten accepteren
				@delete_cookie('new_uitzender_id');
			}
			

			//update
			$this->db_user->where('uitzender_id', $this->uitzender_id);
			$this->db_user->update('uitzenders_status', $update_status);
		}
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factoren op na controle
	 *
	 */
	public function setFactoren()
	{
		$input = $this->_set('uitzenders_factoren', 'factoren');
		return $input;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 *
	 */
	public function setBedrijfsgegevens()
	{
		$input = $this->_set('uitzenders_bedrijfsgegevens', 'bedrijfsgegevens');
		return $input;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla emailadressen op na controle
	 *
	 */
	public function setEmailadressen()
	{
		$input = $this->_set('uitzenders_emailadressen', 'emailadressen');
		return $input;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla systeeminstellingen op na controle
	 *
	 */
	public function setSysteeminstellingen()
	{
		$input = $this->_set('uitzenders_systeeminstellingen', 'systeeminstellingen');
		return $input;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factuurgegevens op na controle
	 * @return array
	 */
	public function setFactuurgegevens()
	{
		$input = $this->_set('uitzenders_factuurgegevens', 'factuurgegevens');
		return $input;

	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
			$sql = "SELECT MAX(contact_id) AS contact_id FROM uitzenders_contactpersonen";
			$query = $this->db_user->query($sql);

			$data = $query->row_array();

			$insert['contact_id'] = $data['contact_id'] + 1;
			$insert['uitzender_id'] = $this->uitzender_id;
			$this->db_user->insert('uitzenders_contactpersonen', $insert);

			if ($this->db_user->insert_id() > 0)
			{
				$new = true;
				$contact_id = $insert['contact_id'];
			}

		}
		
		//aanhef en tekenbevoegd toevoegen als die leeg zijn, alleen voor ajax call nodig
		if( !isset($_POST['aanhef']) )$_POST['aanhef'] = -1;
		if( !isset($_POST['tekenbevoegd']) )$_POST['tekenbevoegd'] = -1;

		$input = $this->_set('uitzenders_contactpersonen', 'contactpersoon', array('contact_id' => $contact_id));

		//extra controle eerste contactpersoon
		if( count($this->contactpersonen()) == 1 )
		{
			if( isset($_POST['tekenbevoegd']) && $_POST['tekenbevoegd'] != 1 )
				$this->_error['tekenbevoegd'][] = 'Uw eerste contactpersoon moet bevoegd zijn namens de onderneming overeenkomsten aan te gaan';
		}

		//zijn er errors, dan weer uit de database
		if ($new == true && $this->errors() !== false)
		{
			$sql = "DELETE FROM uitzenders_contactpersonen WHERE contact_id = $contact_id";
			$this->db_user->query($sql);
		}

		return $input;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * del contactpersoon
	 */
	public function delContactpersoon( $id )
	{
		$sql = "UPDATE uitzenders_contactpersonen SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND contact_id = $id";
		$this->db_user->query($sql);
		
		if ($this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * contactpersoon goedkeuren en aanmeldprocess afsluiten
	 */
	public function approveContactpersoon( $id )
	{
		$this->setContactpersoon( $id );
		$this->_updateStatus( 'contactpersoon_complete' );
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