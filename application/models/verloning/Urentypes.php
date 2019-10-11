<?php

namespace models\Verloning;

use models\Connector;
use models\Forms\Validator;
use models\Utils\DBhelper;
use models\Werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class Urentypes extends Connector
{

	/*
	 * @var array
	 */
	private $_error = NULL;
	

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get categorien
	 */
	public function categorien()
	{
		return $this->select_all( 'urentypes_categorien', 'urentype_categorie_id' );
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
		
		//TODO: aan werknemers toevoegen
		$urentype_id_inlener = $this->db_user->insert_id();
		
		if( $urentype_id_inlener < 1 )
		{
			$this->_error[] = 'Er gaat was mis bij het toevoegen van een urentype';
			return false;
		}
		
		//toevoegen aan werknemers
		$this->addUrentypeToWerknemers( $inlener_id, $insert );
		
		
		//show($result);
	}
	
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Add urentype to werknemers, new and old
	 * insert moet al gevalideerd zijn
	 */
	public function addUrentypeToWerknemers( $inlener_id, $urentype )
	{
		//welke werknemers werken voor deze inlener
		$werknemers = WerknemerGroup::inlener( $inlener_id );
		
		//afbreken als er nog geen werknemers zijn
		if( $werknemers === NULL )
			return false;
		
		//TODO: list van maken
		//voor elk van deze werknemers de uurlonen ophalen
		$sql = "SELECT * FROM werknemers_uurloon WHERE deleted = 0 AND werknemer_id IN (".array_keys_to_string($werknemers).")";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$insert['inlener_id'] = $inlener_id;
			$insert['werknemer_id'] = $row['werknemer_id'];
			$insert['uurloon_id'] = $row['uurloon_id'];
			$insert['urentype_active'] = 1;
			$insert['urentype_id'] = $urentype['urentype_id'];
			$insert['user_id'] = $this->user->user_id;
			if(isset($urentype['standaard_verkooptarief']))
				$insert['verkooptarief'] = $urentype['standaard_verkooptarief'];
			
			$insert_batch[] = $insert;
		}
		
		$this->db_user->insert_batch( 'werknemers_urentypes', $insert_batch );
		
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
	}
	

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get All
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