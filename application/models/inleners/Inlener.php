<?php

namespace models\inleners;

use models\Connector;
use models\forms\Validator;
use models\uitzenders\Uitzender;
use models\utils\DBhelper;

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
	public $kvknr = NULL; // @var int
	public $bedrijfsnaam = NULL; // @var string
	public $uitzender_id = NULL; // @var int
	private $_uitzender_id_new = NULL; // @var int
	
	public $contacten = NULL; // @var string
	
	private $_force_check = false; // @var int
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
	public $cao_complete = NULL;

	public $next = array();
	public $prev = array();

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($inlener_id)
	{
		$this->inlener_id = intval($inlener_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Als werkgever forceren dat velden alsnog gecontroleerd moeten worden
	 */
	public function forceCheck()
	{
		$this->_force_check = true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ALLEEN VOOR DEVOLPMENT
	 * TODO: verwijderen
	 */
	public function del( $id )
	{
		if( ENVIRONMENT != 'development' )
			die('Geen toegand');
		
		$this->db_user->query( "DELETE FROM inleners_bedrijfsgegevens WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM inleners_cao WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_contactpersonen WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_emailadressen WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_factoren WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_factuurgegevens WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_kredietaanvragen WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_kredietgebruik WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_kredietgegevens WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_last_visited WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_portal_status WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_uitzenders WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_urentypes WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_inleners WHERE inlener_id = $id" );
		$this->db_user->query( "DELETE FROM	inleners_status WHERE inlener_id = $id" );
		
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check object access
	 *
	 * @return bool
	 */
	static function access( $inlener_id, $user_type, $id )
	{
		$CI =& get_instance();
		
		if( $user_type == 'uitzender' )
		{
			$query = $CI->db_user->query( "SELECT inlener_id FROM inleners_uitzenders WHERE inlener_id = ? AND uitzender_id = ? AND deleted = 0", [$inlener_id,$id] );
			if( $query->num_rows() === 0 ) return false;
			return true;
		}
		
		return false;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	* Koppel inlener aan uitzender, voor nu alleen 1 op 1
	*/
	public function koppelenAanUitzender( $uitzender_id )
	{
		//maar 1 koppeling toestaand
		if( $this->uitzenderID() !== NULL )
		{
			$this->_error[] = 'Inlener is al gekoppeld, dubbele koppeling niet toegestaan';
			return false;
		}
		
		$insert['inlener_id'] = $this->inlener_id;
		$insert['uitzender_id'] = intval($uitzender_id);
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'inleners_uitzenders', $insert );
		
		if( $this->db_user->insert_id() < 1 )
			$this->_error[] = 'Inlener kon niet worden gekoppeld aan uitzender (database fout)';
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	* Koppeling weer weghalen
	*
	*/
	public function delKoppelingUitzender( $uitzender_id )
	{
		$sql = "UPDATE inleners_uitzenders
				SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . "
				WHERE deleted = 0 AND inlener_id = ".$this->inlener_id." AND uitzender_id = ".intval($uitzender_id)." ";
		
		$this->db_user->query($sql);
		
		if( $this->db_user->affected_rows() < 1 )
			$this->_error[] = 'Er gaat wat mis (database fout)';
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	* Haal uitzender ID op als die er is
	*
	*/
	public function uitzenderID()
	{
		if( $this->uitzender_id !== NULL )
			return  $this->uitzender_id;
		
		$row = $this->select_row( 'inleners_uitzenders', array('inlener_id' => $this->inlener_id) );
		if( $row === NULL )
			return NULL;
		
		$this->uitzender_id = $row['uitzender_id'];
		
		return $row['uitzender_id'];
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
		$this->cao_complete = $this->_status['cao_complete'];

		//set public vars
		$this->bedrijfsnaam = $this->_status['bedrijfsnaam'];
		$this->kvknr = $this->_status['kvknr'];

		//volgende vorige init
		$this->next['id'] 			= $this->inlener_id;    //default self
		$this->next['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self
		$this->prev['id'] 			= $this->inlener_id;    //default self
		$this->prev['bedrijfsnaam'] = $this->bedrijfsnaam;  //default self
		
		//uitzender beperken
		$uitzender = '';
		if( $this->user->user_type == 'uitzender' )
			$uitzender = " AND iu.uitzender_id = ".$this->uitzender->id." AND iu.deleted = 0 ";

		$sql = "SELECT inleners_status.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam FROM inleners_status 
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_status.inlener_id = inleners_bedrijfsgegevens.inlener_id
				WHERE ( 
						inleners_status.inlener_id =
							IFNULL((SELECT min(inleners_status.inlener_id) FROM inleners_status LEFT JOIN inleners_uitzenders iu on inleners_status.inlener_id = iu.inlener_id
									WHERE inleners_status.inlener_id > $this->inlener_id AND inleners_status.archief = 0 AND inleners_status.complete = 1 ".$uitzender."),0)
						OR inleners_status.inlener_id =
						   IFNULL((SELECT max(inleners_status.inlener_id) FROM inleners_status LEFT JOIN inleners_uitzenders iu on inleners_status.inlener_id = iu.inlener_id
						   			WHERE inleners_status.inlener_id < $this->inlener_id AND inleners_status.archief = 0 AND inleners_status.complete = 1 ".$uitzender."),0)
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


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factuurgegevens
	 */
	public function factuurgegevens()
	{
		$sql = "SELECT * FROM inleners_factuurgegevens WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);
		return DBhelper::toRow( $query, 'NULL' );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bedrijfsgegevens
	 */
	public function bedrijfsgegevens()
	{
		$sql = "SELECT * FROM inleners_bedrijfsgegevens WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get kredietgegevens
	 */
	public function kredietgegevens()
	{
		$data['kredietlimiet'] = NULL;
		$data['kredietgebruik'] = NULL;
		
		//huidig krediet limiet
		$sql = "SELECT * FROM inleners_kredietgegevens WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		if( $query->num_rows() > 0 )
		{
			$row = $query->row_array();
			$data['kredietlimiet'] = $row['kredietlimiet'];
		}
		
		//krediet gebruik
		$sql = "SELECT * FROM inleners_kredietgebruik WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		if( $query->num_rows() > 0 )
		{
			$row = $query->row_array();
			$data['kredietgebruik'] = $row['kredietgebruik'];
		}
	
		return $data;
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
	 * get emailadressen
	 */
	public function emailadressen()
	{
		$sql = "SELECT * FROM inleners_emailadressen WHERE deleted = 0 AND inlener_id = $this->inlener_id LIMIT 1";
		$query = $this->db_user->query($sql);
		return DBhelper::toRow( $query, 'NULL' );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get factoren
	 */
	public function factoren( $factor_id = NULL )
	{
		$data = $this->select_all( 'inleners_factoren', 'factor_id', array( 'inlener_id' => $this->inlener_id ) );
		
		if( $data !== NULL && $factor_id !== NULL)
		{
			if( isset( $data[$factor_id] ) )
				return $data[$factor_id];
			else
				return NULL;
		}
		
		return $data;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Instellingen goed zetten voor inlener
	 * factoren en urentypes aanmaken
	 * @return boolean
	 */
	private function _finish()
	{
		$this->_setStandaardFactoren();
		$this->_setStandaardUren();
	
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factoren aanmaken
	 * @return boolean
	 */
	private function _setStandaardFactoren()
	{
		//geen dubbele entries
		if( $this->select_row( 'inleners_factoren', array( 'inlener_id' => $this->inlener_id ) ) !== NULL )
			return false;
		
		//standaard
		$insert['factor_hoog'] = 1.7;
		$insert['factor_laag'] = 1.45;
		
		//factoren van uitzender ophalen
		if( $this->uitzenderID() !== NULL )
		{
			$uitzender = new Uitzender( $this->uitzenderID() );
			$factoren = $uitzender->factoren();
			
			$insert['factor_hoog'] = $factoren['factor_hoog'];
			$insert['factor_laag'] = $factoren['factor_laag'];
		}
		
		$insert['default_factor'] = 1;
		$insert['factor_id'] = 1; //eerste altijd ID 1
		$insert['inlener_id'] = $this->inlener_id;
		$insert['omschrijving'] = 'standaard factoren';
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'inleners_factoren', $insert );
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uren aanmaken
	 * @return boolean
	 */
	private function _setStandaardUren()
	{
		//geen dubbele entries
		if( $this->select_row( 'inleners_urentypes', array( 'inlener_id' => $this->inlener_id ) ) !== NULL )
			return false;
		
		//standaard
		$insert['urentype_id'] = 1; //eerste altijd ID 1, is normale uren
		$insert['default_urentype'] = 1; //default aangeven
		$insert['inlener_id'] = $this->inlener_id;
		$insert['doorbelasten_uitzender'] = 0;
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'inleners_urentypes', $insert );
		return true;
		
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe inlener aanmaken
	 * Kan alleen als basis bedrijfsgegevens ingevuld zijn
	 * Entry in status tabel maken, dit levert inlener_id op
	 * Voor bemiddeling cao op complete
	 * @return boolean
	 */
	private function _new()
	{
		$insert['complete'] = 0;
		if( $this->user->werkgever_type == 'bemiddeling' )
			$insert['cao_complete'] = 1;
		
		$this->db_user->insert('inleners_status', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$this->inlener_id = $this->db_user->insert_id();
			
			//koppeling uitzender aanmaken indien gewenst
			if( $this->_uitzender_id_new !== NULL )
				$this->koppelenAanUitzender( $this->_uitzender_id_new );
				
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
	private function _set($table = '', $method = '', $where = NULL)
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
		
		//eventueel uitzender_id mee teruggeven voor eerste aanmelding
		if( $this->_uitzender_id_new !== NULL )
			$input['uitzender_id'] = $this->_uitzender_id_new;

		return $input;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * inlener heeft geen cao
	 *
	 */
	public function noCao()
	{
		$this->db_user->query("UPDATE inleners_cao SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND inlener_id = $this->inlener_id");
		
		
		$insert['inlener_id'] = $this->inlener_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['no_cao'] = 1;
		
		$this->db_user->insert( 'inleners_cao', $insert );
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
			if ( $this->user->user_type == 'werkgever' && $this->_force_check === false )
				$update_status[$property] = 1;// van leeg naar complete
			else
				$update_status[$property] = 0;// van leeg naar controle
		}
		
		//alleen werkgever mag controle uitvoeren
		if ( $this->$property === '0' && $this->user->user_type == 'werkgever')
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
				$this->contactpersoon_complete == 1 &&
				$this->cao_complete == 1
				)
			{
				$update_status['complete'] = 1;
				
				//acties voor aanmaken nieuwe inlener
				$this->_finish();
			}
			
			//update
			$this->db_user->where('inlener_id', $this->inlener_id);
			$this->db_user->update('inleners_status', $update_status);
		}
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factoren op na controle
	 *
	 */
	public function setFactoren()
	{
		//omzetten naar single level array
		if( is_array($_POST['factor_hoog']) )
		{
			$factor_id = key($_POST['factor_hoog']);
			$_POST['default_factor'] = $_POST['default_factor'][$factor_id];
			$_POST['omschrijving'] = $_POST['omschrijving'][$factor_id];
			$_POST['factor_hoog'] = $_POST['factor_hoog'][$factor_id];
			$_POST['factor_laag'] = $_POST['factor_laag'][$factor_id];
		}
		//nieuwe factor
		else
		{
			//hoogste contact id ophalen, autoincrement is niet van toepassing (zit op andere kolom)
			$sql = "SELECT MAX(factor_id) AS factor_id FROM inleners_factoren";
			$query = $this->db_user->query($sql);
			
			$data = $query->row_array();
			
			$_POST['factor_id'] = $factor_id  = $data['factor_id'] + 1;
			$_POST['inlener_id'] = $this->inlener_id;
		}
		
		$input = $this->_set('inleners_factoren', 'factoren', array('factor_id' => $factor_id) );

		//$input = $this->_set('inleners_factoren', 'factoren');
		//return $input;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete factoren
	 *
	 */
	public function delFactoren( $factor_id )
	{
		$this->delete_row( 'inleners_factoren', array('factor_id' => $factor_id)  );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 *
	 */
	public function setBedrijfsgegevens()
	{
		$input = $this->_set('inleners_bedrijfsgegevens', 'bedrijfsgegevens');
		return $input;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla emailadressen op na controle
	 * @return array
	 */
	public function setEmailadressen()
	{
		$input = $this->_set('inleners_emailadressen', 'emailadressen');
		return $input;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla factuurgegevens op na controle
	 * @return array
	 */
	public function setFactuurgegevens()
	{
		$input = $this->_set('inleners_factuurgegevens', 'factuurgegevens');
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
		
		//aanhef en tekenbevoegd toevoegen als die leeg zijn, alleen voor ajax call nodig
		if( !isset($_POST['aanhef']) )$_POST['aanhef'] = -1;
		if( !isset($_POST['tekenbevoegd']) )$_POST['tekenbevoegd'] = -1;

		$input = $this->_set('inleners_contactpersonen', 'contactpersoon', array('contact_id' => $contact_id));
		
		//extra controle eerste contactpersoon
		if( count($this->contactpersonen()) == 1 )
		{
			if( isset($_POST['tekenbevoegd']) && $_POST['tekenbevoegd'] != 1 )
				$this->_error['tekenbevoegd'][] = 'Uw eerste contactpersoon moet bevoegd zijn namens de onderneming overeenkomsten aan te gaan';
		}

		//zijn er erros, dan weer uit de database
		if ($new == true && $this->errors() !== false)
		{
			$sql = "DELETE FROM inleners_contactpersonen WHERE contact_id = $contact_id";
			$this->db_user->query($sql);
		}

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