<?php

namespace models\werknemers;

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

class Werknemer extends Connector
{

	private $_status = NULL; // @var array

	public $werknemer_id = NULL; // @var int
	public $id = NULL; // @var int
	public $naam = NULL; // @var string
	public $gb_datum = NULL; // @var string
	public $leeftijd = NULL; // @var string
	private $_uitzender_id_new = NULL; // @var int
	
	public $complete = NULL;
	public $archief = NULL;
	public $gegevens_complete = NULL;
	public $documenten_complete = NULL;
	public $dienstverband_complete = NULL;
	public $verloning_complete = NULL;
	public $etregeling_complete = 1;
	public $deelnemer_etregeling = NULL;

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
	public function akkoordLoonheffing()
	{
		$sql = "SELECT signed_on FROM documenten WHERE file_dir = 'documenten/arbeidsovereenkomst' AND deleted = 0 AND signed = 1 AND werknemer_id = $this->werknemer_id ";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		{
			return NULL;
		}

		$data = $query->row_array();
		return $data['signed_on'];
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($werknemer_id)
	{
		$this->werknemer_id = intval($werknemer_id);
		$this->id = $this->werknemer_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set Archief
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
		
		$this->db_user->where( 'werknemer_id', $this->werknemer_id );
		$this->db_user->update( 'werknemers_status', $update );
		
		$this->archief = $update['archief'];
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ALLEEN VOOR DEVOLPMENT
	 * TODO: verwijderen
	 */
	public function del( $id )
	{
		if( ENVIRONMENT != 'development' )
			die('Geen toegang');
		
		$this->db_user->query( "DELETE FROM werknemers_dienstverband_cao WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM werknemers_dienstverband_duur WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_gegevens WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_idbewijs WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_inleners WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_last_visited WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_pensioen WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_uitzenders WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_urentypes WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_uurloon WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_vergoedingen WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_verloning_instellingen WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemer_et_bsn WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemer_et_inhouding_huisvesting WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemer_et_settings WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemer_et_verblijf WHERE werknemer_id = $id" );
		$this->db_user->query( "DELETE FROM	werknemers_status WHERE werknemer_id = $id" );
		
		
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
		$sql = "SELECT werknemers_gegevens.*, werknemers_status.*
				FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_gegevens.werknemer_id = werknemers_status.werknemer_id
				LEFT JOIN werknemers_uitzenders ON werknemers_uitzenders.werknemer_id = werknemers_status.werknemer_id
				WHERE werknemers_gegevens.deleted = 0 AND werknemers_status.werknemer_id = $this->werknemer_id
				";
		
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND werknemers_uitzenders.uitzender_id = ".$this->uitzender->id." AND werknemers_uitzenders.deleted = 0";
		
		$sql .= " LIMIT 1";
		
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
		$this->etregeling_complete = $this->_status['etregeling_complete'];

		//set public vars
		$this->naam = $this->_status['naam'];
		$this->gb_datum = $this->_status['gb_datum'];

		//volgende vorige init
		$this->next['id'] = $this->werknemer_id;    //default self
		$this->next['naam'] = $this->naam;  //default self
		$this->prev['id'] = $this->werknemer_id;    //default self
		$this->prev['naam'] = $this->naam;  //default self
		
		//ET regeling ?
		$query = $this->db_user->query( "SELECT et_regeling FROM werknemers_verloning_instellingen WHERE werknemer_id = $this->werknemer_id AND deleted = 0" );
		$this->deelnemer_etregeling = DBhelper::toRow( $query, 'NULL', 'et_regeling' );
		
		//vorige volgende
		$sql = "SELECT werknemers_status.werknemer_id, werknemers_gegevens.achternaam, werknemers_gegevens.voorletters, werknemers_gegevens.voornaam, werknemers_gegevens.tussenvoegsel
				FROM werknemers_status
				LEFT JOIN werknemers_gegevens ON werknemers_status.werknemer_id = werknemers_gegevens.werknemer_id
				LEFT JOIN werknemers_uitzenders ON werknemers_uitzenders.werknemer_id = werknemers_status.werknemer_id
				WHERE (
						werknemers_status.werknemer_id = IFNULL((SELECT min(werknemers_status.werknemer_id) FROM werknemers_status
																	WHERE werknemers_status.werknemer_id > $this->werknemer_id
																	  AND werknemers_status.archief = 0 AND werknemers_status.complete = 1),0)
						OR werknemers_status.werknemer_id = IFNULL((SELECT max(werknemers_status.werknemer_id) FROM werknemers_status
																	WHERE werknemers_status.werknemer_id < $this->werknemer_id
																	  AND werknemers_status.archief = 0 AND werknemers_status.complete = 1),0)
					  )
				";
		
		if( $this->user->user_type == 'uitzender' )
			$sql .= " AND werknemers_uitzenders.uitzender_id = ".$this->uitzender->id." ";

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
	 * leeftijd van de medewerker
	 *
	 * @return string
	 */
	public function leeftijd()
	{
		$date = new \DateTime( $this->gb_datum );
		$now = new \DateTime();
		$interval = $now->diff($date);
		$this->leeftijd = $interval->y;
		
		return $this->leeftijd;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * haal ET  regeling object
	 *
	 * @return object
	 */
	public function etregeling() :?Et
	{
		$query = $this->db_user->query( "SELECT et_regeling FROM werknemers_verloning_instellingen WHERE werknemer_id = $this->werknemer_id AND et_regeling = 1 AND deleted = 0 LIMIT 1" );
		if( $query->num_rows() > 0 )
			return new Et( $this->werknemer_id );
		
		return NULL;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get persoonsgegevens
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
		$insert['complete'] = NULL;
		$this->db_user->insert('werknemers_status', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$this->werknemer_id = $this->db_user->insert_id();
			
			//koppeling uitzender aanmaken indien gewenst
			if( $this->_uitzender_id_new !== NULL )
				$this->setUitzender( $this->_uitzender_id_new );
			
			return true;
		}

		return false;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Hoofd cao, NBBU of BOUW
	 *
	 * @return ?string
	 */
	public function defaultCao() :?string
	{
		$query = $this->db_user->query( "SELECT default_cao FROM werknemers_dienstverband_cao WHERE deleted = 0 AND werknemer_id = $this->werknemer_id" );
		return DBhelper::toRow($query, 'NULL', 'default_cao');
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set hoofd cao, NBBU of BOUW
	 *
	 * @return bool
	 */
	public function setDefaultCao( $cao ) :bool
	{
		//geen dubbele entries
		if( $cao == $this->defaultCao() )
			return true;
		
		//validate
		if( !in_array( $cao, array( 'NBBU', 'BOUW','BOUW-UTA') ) )
		{
			$this->_error['cao'] = 'Selecteer een CAO'; //custom key meegeven voor aanmeld wizard
			return false;
		}
		
		//oude entry weggooien
		$this->db_user->query( "UPDATE werknemers_dienstverband_cao SET deleted = 1,  deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND werknemer_id = $this->werknemer_id" );
		
		$insert['werknemer_id'] = $this->werknemer_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['default_cao'] = $cao;
		
		$this->db_user->insert( 'werknemers_dienstverband_cao', $insert );
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * start algemene dienstverband, geen fase start
	 *
	 * @return string
	 */
	public function pensioen() :?array
	{
		return $this->select_row( 'werknemers_pensioen', array( 'werknemer_id' => $this->werknemer_id ));
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * pensioen TODO: uitbreiden
	 *
	 * @return string
	 */
	public function setPensioen( $stipp ) :?bool
	{
		//oude entry weggooien
		$this->db_user->query( "UPDATE werknemers_pensioen SET deleted = 1,  deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND werknemer_id = $this->werknemer_id" );
		
		$insert['werknemer_id'] = $this->werknemer_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['stipp'] = $stipp;
		
		$this->db_user->insert( 'werknemers_pensioen', $insert );
		
		return true;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * start algemene dienstverband, geen fase start
	 *
	 * @return string
	 */
	public function startDienstverband() :?string
	{
		$query = $this->db_user->query( "SELECT indienst FROM werknemers_dienstverband_duur WHERE deleted = 0 AND werknemer_id = $this->werknemer_id" );
		return DBhelper::toRow($query, 'NULL', 'indienst');
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  set start algemene dienstverband, geen fase start
	 *
	 * @return bool
	 */
	public function setStartDienstverband( $datum ) :?bool
	{
		$datum = reverseDate($datum);

		//geen dubbele entries
		if( $datum !== NULL && $datum == $this->startDienstverband() )
			return true;
		
		//validate
		if( !Valid::date($datum) )
		{
			$this->_error['indienst'] = 'Ongeldige datum start dienstverband'; //custom key meegeven voor aanmeld wizard
			return false;
		}
		
		//max aan 1 uitzender
		$this->db_user->query( "UPDATE werknemers_dienstverband_duur SET deleted = 1,  deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND werknemer_id = $this->werknemer_id" );
		
		$insert['werknemer_id'] = $this->werknemer_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['indienst'] = $datum;
		
		$this->db_user->insert( 'werknemers_dienstverband_duur', $insert );
		
		return true;
	}



	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla data op na controle
	 * Oude gegevens worden als verwijderd aangemerkt
	 * Geeft ingevoerde data terug
	 * @return bool|array
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
			//nieuwe werknemer aanmaken? Alleen mogelijk vanaf method gegevens
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
		$sql = "UPDATE werknemers_uitzenders SET deleted = 1,  deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND werknemer_id = $this->werknemer_id";
		$this->db_user->query( $sql );
		
		$insert['werknemer_id'] = $this->werknemer_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['uitzender_id'] = intval($uitzender_id);
		
		$this->db_user->insert( 'werknemers_uitzenders', $insert );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Update status dienstverband
	 *
	 */
	public function dienstverbandIsSet()
	{
		$this->_updateStatus( 'dienstverband_complete' );
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

		if( isset($update_status[$property]) )
			$this->$property = $update_status[$property];//update property
			
		//controle op alle sub statussen
		if (
			$this->gegevens_complete === NULL ||
			$this->documenten_complete === NULL ||
			$this->dienstverband_complete === NULL ||
			$this->verloning_complete === NULL ||
			$this->etregeling_complete === NULL
		)
			$update_status['complete'] = NULL;
		
		//controle op alle sub statussen
		if (
			$this->gegevens_complete == 1 &&
			$this->documenten_complete == 1 &&
			$this->dienstverband_complete == 1 &&
			$this->verloning_complete == 1 &&
			$this->etregeling_complete == 1
		)
			$update_status['complete'] = 1;
		
		if (
			$this->gegevens_complete == 0 &&
			$this->documenten_complete == 0 &&
			$this->dienstverband_complete == 0 &&
			$this->verloning_complete == 0 &&
			$this->etregeling_complete != NULL
		)
			$update_status['complete'] = 0;

		//update
		$this->db_user->where('werknemer_id', $this->werknemer_id);
		$this->db_user->update('werknemers_status', $update_status);
		
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
	 * TODO: weghalen
	 *
	 */
	public function contract()
	{
		$sql = "SELECT * FROM documenten WHERE deleted = 0 AND werknemer_id = $this->werknemer_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		$data = $query->row_array();
		return $data;
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
	 * Sla verloning op na controle
	 *
	 */
	public function setVerloning()
	{
		//oude record evrwijderen
		$this->db_user->query( "UPDATE werknemers_verloning_instellingen SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE werknemer_id = $this->werknemer_id AND deleted = 0", array( $this->user->user_id ) );
		
		$insert['werknemer_id'] = $this->werknemer_id;
		
		$insert['loonheffingskorting'] = intval( $_POST['loonheffingskorting'] );
		$insert['loonheffingskorting_vanaf'] = reverseDate( $_POST['loonheffingskorting_vanaf'] );
		
		$insert['inhouden_zorgverzekering'] = intval( $_POST['inhouden_zorgverzekering'] );
		
		$insert['vakantiegeld_direct'] = intval( $_POST['vakantiegeld_direct'] );
		$insert['feestdagen_direct'] = intval( $_POST['feestdagen_direct'] );
		$insert['kortverzuim_direct'] = intval( $_POST['kortverzuim_direct'] );
		$insert['atv_direct'] = intval( $_POST['atv_direct'] );
		$insert['vakantieuren_bovenwettelijk_direct'] = intval( $_POST['vakantieuren_bovenwettelijk_direct'] );
		
		if( $this->user->user_type == 'werkgever' )
		{
			if(isset($_POST['werkgever_nummer']))
				$insert['werkgever_nummer'] = intval( $_POST['werkgever_nummer'] );
			
			if(isset($_POST['vakantieuren_wettelijk_direct']))
				$insert['vakantieuren_wettelijk_direct'] = intval( $_POST['vakantieuren_wettelijk_direct'] );
			
			$insert['aantal_vakantiedagen_wettelijk'] = intval( $_POST['aantal_vakantiedagen_wettelijk'] );
			$insert['aantal_vakantiedagen_bovenwettelijk'] = intval( $_POST['aantal_vakantiedagen_bovenwettelijk'] );
			$insert['aantal_atv_dagen'] = intval( $_POST['aantal_atv_dagen'] );
		}
		
		$insert['et_regeling'] = intval($_POST['et_regeling']);
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'werknemers_verloning_instellingen', $insert );
		
		$this->deelnemer_etregeling = $insert['et_regeling'];
		
		//wanneer nodig status update
		if( $this->complete != 1 )
			$this->_updateStatus('verloning_complete');
		
		//ET compleet wanneer noodig
		if( $this->complete != 1 && $insert['et_regeling'] == 0)
			$this->_updateStatus('etregeling_complete');
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ET regeling is compleet
	 *
	 */
	public function setEtComplete()
	{
		$this->_updateStatus('etregeling_complete');
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get verloning instellingen
	 */
	public function verloning()
	{
		$sql = "SELECT * FROM werknemers_verloning_instellingen WHERE deleted = 0 AND werknemer_id = $this->werknemer_id LIMIT 1";
		$query = $this->db_user->query($sql);
		
		return DBhelper::toRow($query, 'NULL');
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