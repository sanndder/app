<?php

namespace models\verloning;

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

class Loonstrook extends Connector
{

	private $_loonstrook_id = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $id != NULL )
			$this->setID( $id );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set ID
	 *
	 */
	public function setID( $id )
	{
		$this->_loonstrook_id = intval($id);
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * details
	 *
	 */
	public function details( )
	{
		$query = $this->db_user->query( "SELECT * FROM loonstroken_pdf WHERE loonstrook_id = $this->_loonstrook_id LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL' );
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	*
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