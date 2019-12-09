<?php

namespace models\Verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class Vergoeding extends Connector
{

	/*
	 * @var array
	 */
	private $_error = NULL;
	
	
	
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
	 * Add vergoeding
	 *
	 */
	public function add()
	{
		$validator = new Validator();
		$validator->table('vergoedingen')->input($_POST)->run();
		
		$input = $validator->data();
		
		//geen fouten, nieuwe insert doen
		if ($validator->success())
		{
			//bestaat vergoeding al?
			$sql = "SELECT vergoeding_id FROM vergoedingen WHERE deleted = 0 AND naam = ? LIMIT 1";
			$query = $this->db_user->query( $sql, array($input['naam']) );
			
			if( $query->num_rows() > 0 )
			{
				$this->_error[] = 'Vergoeding bestaat al';
				return false;
			}
			
			$input['user_id'] = $this->user->user_id;
			$this->db_user->insert( 'vergoedingen', $input );
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete vergoeding
	 *
	 */
	public function delete( $vergoeding_id )
	{
		//Urentype ID 1 mag nooit weg!
		return $this->delete_row( 'vergoedingen', array( 'vergoeding_id' => $vergoeding_id ) );
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