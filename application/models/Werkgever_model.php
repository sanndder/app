<?php

use models\documenten\DocumentFactory;
use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Werkgever class
 * Wordt altijd geladen en is overal beschikbaar
 * Haalt alles uit de user databse,  gegevens uit de admin database komen in user_model
 *
 */

class Werkgever_model extends MY_Model
{

	/*
	 * @var int
	 * entiteit id
	 */
	private $_entiteit_id = NULL;
	
	/*
	 * @var array
	 * cache bedrijfsgegevens
	 */
	private $_bedrijfsgegevens = NULL;
	
	/*
	 * @var string
	 * WID externe links
	 */
	private $_wid = NULL;
	
	/*
	 * @var string
	 * hash voro externe links
	 */
	private $_hash = NULL;
	
	/*
	 * @var array
	 * entiteiteb
	 */
	private $_entiteiten = NULL;
	
	private $_error = NULL;
	private $_insert_id = NULL;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * construct
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		//change ID if GET is set
		if( isset($_GET['entity_id']) )
			$this->setEntiteitID( $_GET['entity_id'] );
		
		//nog niks geselecteerd? Dan default entiteit
		if( $this->session->entiteit_id == NULL )
			$this->_setDefaultEntiteitID();
		else
			$this->setEntiteitID( $this->session->entiteit_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set entity ID
	 *
	 */
	public function setEntiteitID( $id )
	{
		$this->_entiteit_id = intval($id);
		
		//check
		$sql = "SELECT entiteit_id FROM werkgever_entiteiten WHERE entiteit_id = $this->_entiteit_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		//nooit ongeldige ID toestaan
		if( $query->num_rows() == 0 )
			die('Invalid Entity ID');
		
		//ook sessie instellen
		$this->session->set_userdata('entiteit_id', $this->_entiteit_id);
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set default entiteit
	 *
	 */
	private function _setDefaultEntiteitID()
	{
		$sql = "SELECT entiteit_id FROM werkgever_entiteiten WHERE deleted = 0 AND default_entiteit = 1 LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		
		$this->setEntiteitID($data['entiteit_id']);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * List all entities
	 *
	 */
	public function listEntiteiten()
	{
		//get all
		$sql = "SELECT werkgever_entiteiten.*, werkgever_bedrijfsgegevens.bedrijfsnaam
				FROM werkgever_entiteiten
				LEFT JOIN werkgever_bedrijfsgegevens ON werkgever_entiteiten.entiteit_id = werkgever_bedrijfsgegevens.entiteit_id
				WHERE werkgever_entiteiten.deleted = 0 AND werkgever_bedrijfsgegevens.deleted = 0
				ORDER BY default_entiteit DESC, schermnaam";
		
		$query = $this->db_user->query( $sql );
		
		//DBhelper niet gebruiken, custom array
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			//geen schermnaam opgegeven, dan bedrijfsnaam
			$row['schermnaam'] = $row['schermnaam'] ?? $row['bedrijfsnaam'];
			$data[$row['entiteit_id']] = $row;
		}
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werkgever ID voor externe links
	 *
	 */
	public function wid()
	{
		if( $this->_wid == NULL )
			$this->_getWIDandHash();
		
		return  $this->_wid;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werkgever hash voor externe links
	 *
	 */
	public function hash()
	{
		if( $this->_hash == NULL )
			$this->_getWIDandHash();
		
		return  $this->_hash;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * WID en hash ophalen
	 *
	 */
	private function _getWIDandHash()
	{
		$sql = "SELECT wid, wg_hash FROM werkgevers WHERE werkgever_id = ".$this->user->werkgever_id." LIMIT 1";
		$query = $this->db_admin->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			$this->_wid = $data['wid'];
			$this->_hash = $data['wg_hash'];
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * extra document veld
	 *
	 */
	public function addDocumentVeld( $naam = NULL, $geldigheid = NULL ) :bool
	{
		if( $naam === NULL || strlen($naam) == 0)
		{
			$this->_error[] = 'Ongeldige naam';
			return false;
		}
		
		if( $geldigheid === NULL )
		{
			$this->_error[] = 'Ongeldige geldigheid';
			return false;
		}
		
		$naam_clean = str_replace( ' ', '_', $naam);
		$naam_clean = str_replace( array('@','#','$','%','&','*',"'",'"','/','\\'), '', $naam_clean);
		
		
		$insert['naam'] = $naam;
		$insert['naam_clean'] = $naam_clean;
		$insert['geldigheid'] = $geldigheid;
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'werkgever_documenten_instellingen', $insert );
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * velden ophalen
	 *
	 */
	public function documentVelden()
	{
		$query = $this->db_user->query( "SELECT * FROM werkgever_documenten_instellingen WHERE deleted = 0 ORDER BY naam" );
		return DBhelper::toArray( $query, 'document_id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * del document veld
	 *
	 */
	public function delDocumentVeld( $id = NULL ) :bool
	{
		$this->db_user->query( "UPDATE werkgever_documenten_instellingen SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE deleted = 0 AND document_id = ?", array( $this->user->user_id, intval($id) ) );
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * del document veld
	 *
	 */
	public function delDocument( $id = NULL ) :bool
	{
		$this->db_user->query( "UPDATE werkgever_documenten SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE deleted = 0 AND document_id = ?", array( $this->user->user_id, intval($id) ) );
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * document ophalen
	 *
	 */
	public function document( $file_id = NULL ) :?array
	{
		$query = $this->db_user->query( "SELECT * FROM werkgever_documenten WHERE deleted = 0 AND file_id = ? LIMIT 1", array($file_id) );
		return DBhelper::toRow( $query, 'NULL' );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * documenten ophalen
	 *
	 */
	public function documenten( ) :?array
	{
		$query = $this->db_user->query( "SELECT werkgever_documenten.*, werkgever_documenten_instellingen.*
											FROM werkgever_documenten
											LEFT JOIN werkgever_documenten_instellingen ON werkgever_documenten.document_id = werkgever_documenten_instellingen.document_id
											WHERE werkgever_documenten.deleted = 0 AND werkgever_documenten_instellingen.deleted = 0 ORDER BY werkgever_documenten_instellingen.naam" );
		return DBhelper::toArray( $query, 'document_id', 'NULL' );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * geupload document afhandelen
	 *
	 */
	public function updateDagtekening( $file_id, $dagtekening ) :bool
	{
		if( $file_id === NULL || $dagtekening === NULL )
			return false;
		
		$dagtekening = reverseDate($dagtekening);
		
		$query = $this->db_user->query( "SELECT werkgever_documenten_instellingen.geldigheid
										 	FROM werkgever_documenten
											LEFT JOIN werkgever_documenten_instellingen ON werkgever_documenten_instellingen.document_id = werkgever_documenten.document_id
											WHERE file_id = $file_id AND werkgever_documenten.deleted = 0 LIMIT 1" );
		$geldigheid =  DBhelper::toRow( $query, 'NULL', 'geldigheid');
		$geldigheid = intval($geldigheid);

		if( $geldigheid == 0)
			$update['geldig_tot'] = NULL;
		else
			$update['geldig_tot'] = date('Y-m-d', strtotime("+$geldigheid months", strtotime($dagtekening) ));
		
		$update['dagtekening'] = $dagtekening;
		$this->db_user->where( 'file_id', $file_id );
		$this->db_user->update( 'werkgever_documenten', $update );
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * geupload document afhandelen
	 *
	 */
	public function handleUploadedDocument( $file_id, $document_id ) :bool
	{
		$query = $this->db_user->query( "SELECT geldigheid FROM werkgever_documenten_instellingen WHERE document_id = $document_id AND deleted = 0 LIMIT 1" );
		$geldigheid =  DBhelper::toRow( $query, 'NULL', 'geldigheid');
		
		if( $geldigheid == 0)
			$update['geldig_tot'] = NULL;
		else
			$update['geldig_tot'] = date('Y-m-d', strtotime("+$geldigheid months"));
		
		$update['dagtekening'] = date('Y-m-d');
		$update['document_id'] = $document_id;
		$this->db_user->where( 'file_id', $file_id );
		$this->db_user->update( 'werkgever_documenten', $update );
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		return false;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get 1 bankrekening
	 *
	 */
	public function bankrekening( $id )
	{
		$bankrekeningen = $this->bankrekeningen();

		if(isset($bankrekeningen[$id]))
			return $bankrekeningen[$id];

		return NULL;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get  array bankrekeningen
	 *
	 */
	public function bankrekeningen()
	{
		$sql = "SELECT * FROM werkgever_bankrekeningen WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id ORDER BY omschrijving ASC";
		$query = $this->db_user->query($sql);

		$bankrekeningen = DBhelper::toArray( $query, 'id', 'array');
		return $bankrekeningen;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * opslaan Algemene voorwaarden
	 *
	 */
	public function setAV()
	{
		$sql = "UPDATE werkgever_av SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0";
		$this->db_user->query($sql);
		
		$insert['voorwaarden'] = $_POST['editor'];
		$insert['user_id'] = $this->user->user_id;
		$insert['entiteit_id'] = $this->_entiteit_id;
		
		$this->db_user->insert( 'werkgever_av', $insert );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Algemene voorwaarden naar pdf en instellen als actief
	 *
	 */
	public function publicateAV()
	{
		//document aanmaken
		$document = DocumentFactory::create( 'AlgemeneVoorwaarden' );
		$document->pdf();
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * AV html ophalen
	 *
	 */
	public function AVhtml()
	{
		$sql = "SELECT voorwaarden FROM werkgever_av WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id";
		$query = $this->db_user->query( $sql );
		
		$data = DBhelper::toRow( $query );
		return $data['voorwaarden'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * AV id ophalen
	 *
	 */
	public function AVID()
	{
		$sql = "SELECT id FROM werkgever_av WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id";
		$query = $this->db_user->query( $sql );
		
		$data = DBhelper::toRow( $query );
		return $data['id'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * AV pdf
	 *
	 */
	public function AVpdf( $method = 'inline' )
	{
		$sql = "SELECT * FROM werkgever_av WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			die('Geen voorwaarden gevonden');
		
		$data = $query->row_array();
		
		$pdf = new \models\file\Pdf($data);
		$pdf->$method();
	
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Del bankrekening
	 *
	 */
	public function delBankrekening( $id )
	{
		$sql = "UPDATE werkgever_bankrekeningen SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND id = $id";
		$this->db_user->query($sql);

		if ($this->db_user->affected_rows() > 0 )
			return true;

		return false;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set bankrekening
	 *
	 */
	public function setBankrekening()
	{
		//welk id is geklikt
		$id = key( $_POST['set']);

		//array naar ander format
		$input = formatPostArray($_POST, 'set');

		$validator = new Validator();
		$validator->table( 'werkgever_bankrekeningen' )->input( $input[$id] )->run();

		$input = $validator->data();

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//nieuwe insert
			if( $id == 0 )
			{
				$input['entiteit_id'] = $this->_entiteit_id;
				$input['user_id'] = $this->user->user_id;
				$this->db_user->insert('werkgever_bankrekeningen', $input);

				if ($this->db_user->insert_id() > 0)
					$this->_insert_id = $this->db_user->insert_id();
				else
					$this->_error[] = 'Database error: insert mislukt';
			}
			else
			{
				//zijn er daadwerkelijk wijzigingen?
				if( inputIsDifferent( $input, $this->bankrekening($id) ))
				{
					//alle vorige entries als deleted
					$sql = "UPDATE werkgever_bankrekeningen SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND id = $id";
					$this->db_user->query($sql);

					//alleen wanneer de update lukt om dubbele entries te voorkomen
					if ($this->db_user->affected_rows() != -1)
					{
						$input['entiteit_id'] = $this->_entiteit_id;
						$input['user_id'] = $this->user->user_id;
						$this->db_user->insert('werkgever_bankrekeningen', $input);

						if ($this->db_user->insert_id() > 0)
							$this->_insert_id = $this->db_user->insert_id();
						else
							$this->_error[] = 'Database error: insert mislukt';
					}
					else
					{
						$this->_error[] = 'Database error: update mislukt';
					}
				}
				//zelfde ID terug
				else
				{
					$this->_insert_id = $id;
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
	 * Get nieuw aangemaakte id
	 *
	 */
	public function getInsertId()
	{
		return $this->_insert_id;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Handtekening downloaden
	 *
	 */
	/*
	public function handtekening()
	{
		$sql = "SELECT AES_DECRYPT( uitzenders_handtekening.file, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file FROM uitzenders_handtekening LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();

			//$type = 'image/jpeg';
			//header('Content-Type:'.$type);
			//echo (base64_encode($data['file']));
			echo "<img src='data:image/jpeg;base64," . base64_encode( $data['file'] )."'>";
			die();
		}
	}*/
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get handtekeneing
	 */
	public function handtekening( $method = 'path' )
	{
		$sql = "SELECT AES_DECRYPT( werkgever_handtekening.file, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file, id
				FROM werkgever_handtekening
				WHERE entiteit_id = $this->_entiteit_id AND deleted = 0
				LIMIT 1";
		
		$query = $this->db_user->query($sql);
		
		if ($query->num_rows() == 0)
			return NULL;
		
		$data = $query->row_array();
		
		if( $method == 'url' )
			return 'image/handtekeningwerkgever/' . $this->_entiteit_id . '?' . $data['id'];
			
		if( $method == 'path' )
			return $data['file'];
		
		//echo "<img src='data:image/jpeg;base64," . base64_encode( $data['file'] )."'>";
		//die();
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get bedrijfsnaam
	 */
	public function bedrijfsnaam()
	{
		$bedrijfsgegevens = $this->bedrijfsgegevens();
		return $bedrijfsgegevens['bedrijfsnaam'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  get logo
	 */
	public function logo( $method = 'path' )
	{
		$sql = "SELECT * FROM werkgever_logo WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id LIMIT 1";
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
			return 'image/logowerkgever/' . $this->_entiteit_id . '?' . $row['id'];
		
		return $row;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  del logo
	 */
	public function delLogo()
	{
		$sql = "UPDATE werkgever_logo
					SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . "
					WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id";
		
		$this->db_user->query($sql);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  del handtekening
	 */
	public function delHandtekening()
	{
		$sql = "UPDATE werkgever_handtekening
					SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . "
					WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id";
		
		$this->db_user->query($sql);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get bedrijfsgegevens
	 *
	 */
	public function bedrijfsgegevens( $refresh = false )
	{
		if( isset($this->_bedrijfsgegevens[$this->_entiteit_id]) && $this->_bedrijfsgegevens[$this->_entiteit_id] !== NULL && !$refresh )
			return $this->_bedrijfsgegevens[$this->_entiteit_id];
		
		$query = $this->db_user->query("SELECT * FROM werkgever_bedrijfsgegevens WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id ORDER BY id DESC LIMIT 1");
		$this->_bedrijfsgegevens[$this->_entiteit_id] = DBhelper::toRow( $query, 'NULL' );
		
		//IBAN erbij, TODO default aanmaken voor factuur
		$bankrekeningen = $this->bankrekeningen();
		$iban = current($bankrekeningen);
		$this->_bedrijfsgegevens[$this->_entiteit_id]['iban'] = $iban['iban'];
		
		return $this->_bedrijfsgegevens[$this->_entiteit_id];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get factoring instellingen
	 *
	 */
	public function factoring()
	{
		$query = $this->db_user->query("SELECT * FROM settings_factoring WHERE deleted = 0 ORDER BY id DESC LIMIT 1");
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * entiteit ID
	 *
	 */
	public function entiteitID()
	{
		return $this->_entiteit_id;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 * Oude gegevens worden als verwijderd aangemerkt
	 * Geeft ingevoerde data terug
	 * @return array
	 */
	public function setBedrijfsgegevens()
	{
		$validator = new Validator();
		$validator->table( 'werkgever_bedrijfsgegevens' )->input( $_POST )->run();

		$input = $validator->data();

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//zijn er daadwerkelijk wijzigingen?
			if( inputIsDifferent( $input, $this->bedrijfsgegevens() ))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE werkgever_bedrijfsgegevens SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND entiteit_id = $this->_entiteit_id";
				$this->db_user->query($sql);

				//alleen wanneer de update lukt om dubbele entries te voorkomen
				if ($this->db_user->affected_rows() != -1)
				{
					$input['entiteit_id'] = $this->_entiteit_id;
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert('werkgever_bedrijfsgegevens', $input);
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
	 * Toon errors
	 * @return array | bool
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