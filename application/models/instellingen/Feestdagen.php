<?php

namespace models\instellingen;

use models\Connector;
use models\forms\Valid;
use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Minimumloonc class
 *
 *
 *
 */

class Feestdagen extends Connector
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
	 * add feestdag
	 *
	 */
	public function add()
	{
		$validator = new Validator();
		$validator->table('settings_feestdagen')->input($_POST)->run();
		
		$input = $validator->data();
		
		//geen fouten, nieuwe insert doen
		if ($validator->success())
		{
			//select_row
			
			//bestaat urentype al?
			$sql = "SELECT id FROM settings_feestdagen WHERE deleted = 0 AND datum = '".$input['datum']."' LIMIT 1";
			$query = $this->db_user->query( $sql );
			
			if( $query->num_rows() > 0 )
			{
				$this->_error[] = 'Feestdag is al toegevoegd. U kunt feestdagen slecht éénmaal per jaar toevoegen.';
				return false;
			}
			
			$input['user_id'] = $this->user->user_id;
			$input['jaar'] = substr( $input['datum'],0,4 );
			
			$this->db_user->insert( 'settings_feestdagen', $input );
		}
		//fouten aanwezig
		else
			$this->_error = $validator->errors();
		
	
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Delete feestdage
	 *
	 */
	public function delete( $id )
	{
		return $this->delete_row( 'settings_feestdagen', array( 'id' => $id ) );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * list all feestdagen
	 *
	 */
	public function getAll()
	{
		$sql = "SELECT * FROM settings_feestdagen WHERE deleted = 0 AND jaar >= YEAR(CURDATE())-1 AND jaar <= YEAR(CURDATE())+1 ORDER BY jaar DESC, datum ASC";
		$query = $this->db_user->query( $sql );
		
		return DBHelper::toArray( $query, 'jaar[]' );
	
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 *
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