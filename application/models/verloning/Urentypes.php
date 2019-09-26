<?php

namespace models\Verloning;

use models\Connector;
use models\Forms\Validator;
use models\Utils\DBhelper;

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
		return $this->delete_row( 'urentypes', array( 'urentype_id' => $urentype_id ) );
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