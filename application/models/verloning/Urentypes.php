<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\inleners\Inlener;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class Urentypes extends Connector
{

	private $_inlener_id = NULL;
	
	
	/**
	 * @var int
	 */
	private $_werknemer_urentype_id = NULL;
	private $_zzp_urentype_id = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set inlener
	 *
	 */
	public function inlener( $id ) :Urentypes
	{
		$this->_inlener_id = intval( $id );
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werknemer urentype id
	 */
	public function setWerknemerUrentypeID( $id ) :Urentypes
	{
		$this->_werknemer_urentype_id = intval( $id );
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werknemer urentype id
	 */
	public function setZzpUrentypeID( $id ) :Urentypes
	{
		$this->_zzp_urentype_id = intval( $id );
		return $this;
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Verkooptarief updaten
	 *
	 */
	public function setVerkooptarief( $tarief )
	{
	
		$update['verkooptarief'] = prepareAmountForDatabase($tarief);
		
		//werknemer
		if( $this->_werknemer_urentype_id != NULL )
		{
			$this->db_user->where( 'id', $this->_werknemer_urentype_id );
			$this->db_user->update( 'werknemers_urentypes', $update );
		}
		
		//zzp
		if( $this->_zzp_urentype_id != NULL )
		{
			$this->db_user->where( 'id', $this->_zzp_urentype_id );
			$this->db_user->update( 'zzp_urentypes', $update );
		}
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Uurtarief zzp'er updaten
	 *
	 */
	public function setUurtarief( $tarief )
	{
		
		$update['uurtarief'] = prepareAmountForDatabase($tarief);
		
		//zzp
		if( $this->_zzp_urentype_id != NULL )
		{
			$this->db_user->where( 'id', $this->_zzp_urentype_id );
			$this->db_user->update( 'zzp_urentypes', $update );
		}
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * marge zzp'er updaten
	 *
	 */
	public function setMarge( $tarief )
	{
		
		$update['marge'] = prepareAmountForDatabase($tarief);
		
		//zzp
		if( $this->_zzp_urentype_id != NULL )
		{
			$this->db_user->where( 'id', $this->_zzp_urentype_id );
			$this->db_user->update( 'zzp_urentypes', $update );
		}
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}

	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Verkooptarief updaten
	 *
	 */
	public function updateVerkooptarief( $post )
	{
		$update['standaard_verkooptarief'] = prepareAmountForDatabase($post['value']);
		$this->db_user->where( 'inlener_id', $post['inlener_id'] );
		$this->db_user->where( 'inlener_urentype_id', $post['urentype_id'] );
		
		$this->db_user->update( 'inleners_urentypes', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Label updaten
	 *
	 */
	public function updateLabel( $post )
	{
		$update['label'] = $post['value'];
		$this->db_user->where( 'inlener_id', $post['inlener_id'] );
		$this->db_user->where( 'inlener_urentype_id', $post['urentype_id'] );
		
		$this->db_user->update( 'inleners_urentypes', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get categorien
	 */
	public function setActiveStatus( $status )
	{
		if( $status == 'true' )
			$update['urentype_active'] = 1;
		else
			$update['urentype_active'] = 0;
		
		//werknemer
		if( $this->_werknemer_urentype_id != NULL )
		{
			$this->db_user->where( 'id', $this->_werknemer_urentype_id );
			$this->db_user->update( 'werknemers_urentypes', $update );
		}
		
		//zzp'er
		if( $this->_zzp_urentype_id != NULL )
		{
			$this->db_user->where( 'id', $this->_zzp_urentype_id );
			$this->db_user->update( 'zzp_urentypes', $update );
		}
		
		if( $this->db_user->affected_rows() != 1 )
			$this->_error[] = 'Database error';
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get categorien
	 */
	public function categorien()
	{
		return $this->select_all( 'urentypes_categorien', 'urentype_categorie_id' );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Delete urentype
	 *
	 */
	public function delete( $urentype_id )
	{
		//Urentype ID 1 mag nooit weg!
		if( $urentype_id != 1 )
			return $this->delete_row( 'urentypes', array( 'urentype_id' => $urentype_id ) );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * validate input for inlener
	 *
	 */
	public function validateInlenerUrentype( $post = NULL )
	{
		if( $post === NULL ) $post = $_POST;
		
		//chekc of ID bestaat
		$result = $this->select_row( 'urentypes', array('urentype_id' => intval($post['urentype_id']) ) );
		
		if( $result === NULL )
			$this->_error['urentype_id'] = 'Er is een ongeldig urentype gekozen';
		
		//is doorbelasten gekozen
		if( !isset($post['doorbelasten_uitzender']) || ($post['doorbelasten_uitzender'] != 0 && $post['doorbelasten_uitzender'] != 1) )
			$this->_error['doorbelasten_uitzender'] = 'Maak een keuze a.u.b.';
		
		//is standaard tarief
		if( isset($post['standaard_verkooptarief']) && $post['standaard_verkooptarief'] != '' )
		{
			if( !is_numeric(prepareAmountForDatabase($post['standaard_verkooptarief'])) )
				$this->_error['standaard_verkooptarief'] = 'Er is een ongeldig bedrag ingevuld';
		}
		
		//show($result);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * urentypes vanuit cao aanmaken
	 *
	 */
	public function copyFromCao( $cao )
	{
		//show($cao);
		if( !isset($cao['werksoort']))
		{
			$this->_error[] = 'Geen urentypes gevonden in CAO';
			return false;
		}
		
		//door alle uren heenlopen
		foreach( $cao['werksoort'] as $werksoort )
		{
			//standaard uur overslaan
			if( $werksoort['name'] != 'Standaard uur' )
			{
				unset( $_POST );
				//toeslag
				if( $werksoort['name'] == 'Toeslag' || $werksoort['name'] == 'Ploegentoeslag' )
				{
					//percentage aanpassen
					if( $werksoort['amount'] > 100 )
						$werksoort['amount'] = $werksoort['amount'] - 100;
					
					$_POST['urentype_categorie_id'] = 2; // 2 is overuren
					$_POST['percentage'] = $werksoort['amount'] ;
					$_POST['naam'] = 'toeslag ' . $werksoort['amount']  . '%';
				}
				
				//overuren
				if( $werksoort['name'] == 'Overuur' )
				{
					$_POST['urentype_categorie_id'] = 2; // 2 is overuren
					$_POST['percentage'] = $werksoort['amount'] ;
					$_POST['naam'] = 'overuren ' . $werksoort['amount']  . '%';
				}
				
				//urentype ID ophalen
				$urentype_id = $this->_getUrentypeId( $_POST );

				//aan inlener toevoegen
				$urentype['inlener_id'] = $this->_inlener_id; 
				$urentype['urentype_id'] = $urentype_id; 
				$urentype['doorbelasten_uitzender'] = 0;
				$urentype['label'] = NULL;
				$urentype['standaard_verkooptarief'] = NULL;
				$urentype['user_id'] = $this->user->id;
				
				if( !$this->_urentypeInlenerExists($urentype) )
					$this->addUrentypeToInlener( $this->_inlener_id, $urentype );
			}
		}

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check if urentype exists with inlener
	 *
	 * @return bool
	 */
	private function _urentypeInlenerExists( $data ) :bool
	{
		$sql = "SELECT * FROM inleners_urentypes WHERE urentype_id = ? AND inlener_id = ? AND deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql, array( $data['urentype_id'], $this->_inlener_id ) );
		
		if( $query->num_rows() == 0 )
			return false;
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check if urentype exists and return ID if so
	 * else insert uretype
	 *
	 * @return int
	 */
	private function _getUrentypeId( $data ) :int
	{
		$sql = "SELECT urentype_id FROM urentypes WHERE urentype_categorie_id = ".$data['urentype_categorie_id']." AND percentage = ".$data['percentage']." LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		//nieuwe type aanmaken
		if( $query->num_rows() == 0 )
			return $this->add();
		
		$data = $query->row_array();
		return $data['urentype_id'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * validate input for inlener
	 *
	 */
	public function addUrentypeToInlener( $inlener_id, $post )
	{
		//eerst controleren
		$this->validateInlenerUrentype( $post );
		
		//stoppen bij error
		if( $this->_error !== NULL )
			return false;
		
		//cleanup
		$insert['inlener_id'] = $inlener_id;
		$insert['urentype_id'] = $post['urentype_id'];
		$insert['doorbelasten_uitzender'] = $post['doorbelasten_uitzender'];
		$insert['label'] = strip_tags(substr(trim($post['label']),0,100));
		$insert['user_id'] = $this->user->user_id;
		if( $post['standaard_verkooptarief'] != '' )
			$insert['standaard_verkooptarief'] = prepareAmountForDatabase($post['standaard_verkooptarief']);
		
		$this->db_user->insert( 'inleners_urentypes', $insert );
		
		$inlener_urentype_id = $this->db_user->insert_id();
		
		if( $inlener_urentype_id < 1 )
		{
			$this->_error[] = 'Er gaat was mis bij het toevoegen van een urentype';
			return false;
		}
		
		//toevoegen aan werknemers of zzp
		if( $this->user->werkgever_type == 'uitzenden' )
			$this->addUrentypeToWerknemers( $inlener_id, $inlener_urentype_id, $insert );
		if( $this->user->werkgever_type == 'bemiddeling' )
			$this->addUrentypeToZZP( $inlener_id, $inlener_urentype_id, $insert );
		
		//show($result);
	}
	
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add urentype to werknemers, new and old
	 * insert moet al gevalideerd zijn
	 */
	public function addUrentypeToWerknemers( $inlener_id, $inlener_urentype_id, $urentype )
	{
		//welke werknemers werken voor deze inlener
		$werknemers = WerknemerGroup::inlener( $inlener_id );
		
		//afbreken als er nog geen werknemers zijn
		if( $werknemers === NULL )
			return false;
		
		//TODO: list van maken
		//voor elk van deze werknemers de uurlonen ophalen
		$sql = "SELECT * FROM werknemers_inleners WHERE deleted = 0 AND inlener_id = $inlener_id AND werknemer_id IN (".array_keys_to_string($werknemers).")";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$insert['inlener_urentype_id'] = $inlener_urentype_id;
			$insert['inlener_id'] = $inlener_id;
			$insert['werknemer_id'] = $row['werknemer_id'];
			$insert['plaatsing_id'] = $row['plaatsing_id'];
			$insert['urentype_active'] = 1;
			$insert['urentype_id'] = $urentype['urentype_id'];
			$insert['user_id'] = $this->user->user_id;
			if(isset($urentype['standaard_verkooptarief']))
				$insert['verkooptarief'] = $urentype['standaard_verkooptarief'];
			
			$insert_batch[] = $insert;
		}
		
		$this->db_user->insert_batch( 'werknemers_urentypes', $insert_batch );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add urentype to werknemers, new and old
	 * insert moet al gevalideerd zijn
	 */
	public function addUrentypeToZZP( $inlener_id, $inlener_urentype_id, $urentype )
	{
		//welke werknemers werken voor deze inlener
		$zzpers = ZzpGroup::inlener( $inlener_id );
		
		//afbreken als er nog geen werknemers zijn
		if( $zzpers === NULL )
			return false;
		
		//TODO: list van maken
		//voor elk van deze werknemers de uurlonen ophalen
		$sql = "SELECT * FROM zzp_inleners WHERE deleted = 0 AND inlener_id = $inlener_id AND zzp_id IN (".array_keys_to_string($zzpers).")";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$insert['inlener_urentype_id'] = $inlener_urentype_id;
			$insert['inlener_id'] = $inlener_id;
			$insert['zzp_id'] = $row['zzp_id'];
			$insert['plaatsing_id'] = $row['plaatsing_id'];
			$insert['urentype_active'] = 1;
			$insert['urentype_id'] = $urentype['urentype_id'];
			$insert['user_id'] = $this->user->user_id;
			if(isset($urentype['standaard_verkooptarief']))
				$insert['verkooptarief'] = $urentype['standaard_verkooptarief'];
			
			$insert_batch[] = $insert;
		}
		
		$this->db_user->insert_batch( 'zzp_urentypes', $insert_batch );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * urentypes weghalen bij inlener voor bepaalde werknemer
	 *
	 */
	public function deleteUrentypesWerknemerForInlener( $werknemer_id, $inlener_id )
	{
		$sql = "UPDATE werknemers_urentypes SET deleted = 1, deleted_on = NOW(), deleted_by = ?	WHERE werknemer_id = ? AND inlener_id = ? AND deleted = 0";
		$this->db_user->query( $sql, array($this->user->user_id, $werknemer_id, $inlener_id) );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * urentypes weghalen bij inlener voor bepaalde zzp'er
	 *
	 */
	public function deleteUrentypesZzpForInlener( $zzp_id, $inlener_id )
	{
		$sql = "UPDATE zzp_urentypes SET deleted = 1, deleted_on = NOW(), deleted_by = ?WHERE zzp_id = ? AND inlener_id = ? AND deleted = 0";
		$this->db_user->query( $sql, array($this->user->user_id, $zzp_id, $inlener_id) );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add urentypes bij nieuwe koppeling werknemer
	 *
	 */
	public function addUrentypesWerknemerForInlener( $plaatsing_id, $werknemer_id, $inlener_id )
	{
		//check of standaard urentype goed is aangemaakt
		$inlener = new Inlener( $inlener_id );
		$inlener->checkForDefaultUrenType();
		
		$urentypesgroup = new UrentypesGroup();
		$urentypes = $urentypesgroup->inlener( $inlener_id )->getUrentypeWerknemerMatrix();
	
		foreach( $urentypes as $urentype )
		{
			unset($insert);
			$insert['urentype_active'] = 1;
			$insert['inlener_id'] = $inlener_id;
			$insert['werknemer_id'] = $werknemer_id;
			$insert['inlener_urentype_id'] = $urentype['inlener_urentype_id'];
			$insert['urentype_id'] = $urentype['urentype_id'];
			$insert['plaatsing_id'] = $plaatsing_id;
			$insert['verkooptarief'] = $urentype['standaard_verkooptarief'];
			
			$insert_batch[] = $insert;
		}
		
		if( isset($insert_batch) )
		{
			$this->db_user->insert_batch( 'werknemers_urentypes', $insert_batch );
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add urentypes bij nieuwe koppeling werknemer
	 *
	 */
	public function addUrentypesZzpForInlener( $plaatsing_id, $zzp_id, $inlener_id )
	{
		$urentypesgroup = new UrentypesGroup();
		$urentypes = $urentypesgroup->inlener( $inlener_id )->getUrentypeZzpMatrix();
		
		if( !isset($urentypes) || !is_array($urentypes) || count($urentypes) == 0 )
			return NULL;
		
		foreach( $urentypes as $urentype )
		{
			unset($insert);
			$insert['urentype_active'] = 1;
			$insert['inlener_id'] = $inlener_id;
			$insert['zzp_id'] = $zzp_id;
			$insert['inlener_urentype_id'] = $urentype['inlener_urentype_id'];
			$insert['urentype_id'] = $urentype['urentype_id'];
			$insert['plaatsing_id'] = $plaatsing_id;
			$insert['verkooptarief'] = $urentype['standaard_verkooptarief'];
			$insert['marge'] = 1.25;
			
			$insert_batch[] = $insert;
		}
		
		if( isset($insert_batch) )
		{
			$this->db_user->insert_batch( 'zzp_urentypes', $insert_batch );
		}
	}

	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add urentype
	 *
	 */
	public function add()
	{
		$validator = new Validator();
		$validator->table('urentypes')->input($_POST)->run();
		
		$input = $validator->data();

		//geen fouten, nieuwe insert doen
		if ($validator->success())
		{
			//bestaat urentype al?
			$sql = "SELECT urentype_id FROM urentypes WHERE deleted = 0 AND urentype_categorie_id = ".$input['urentype_categorie_id']." AND percentage = '".$input['percentage']."'";
			$query = $this->db_user->query( $sql );

			if( $query->num_rows() > 0 )
			{
				$this->_error[] = 'Urentype bestaat al';
				return false;
			}
			
			$input['user_id'] = $this->user->user_id;
			$this->db_user->insert( 'urentypes', $input );
			
			if( $this->db_user->insert_id() > 0 )
				return  $this->db_user->insert_id();
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get All
	 * TODO: verplaatsen naar group, naa moet all() worden
	 */
	public function getAll()
	{
		$sql = "SELECT urentypes.*, urentypes_categorien.naam AS categorie_naam, urentypes_categorien.label FROM urentypes
    			LEFT JOIN urentypes_categorien on urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				WHERE urentypes.deleted = 0
    			ORDER BY urentypes.urentype_categorie_id, urentypes.naam";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$data[$row['label']][] = $row;
		}
		
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