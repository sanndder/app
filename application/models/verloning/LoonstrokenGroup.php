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

class LoonstrokenGroup extends Connector
{

	private $_werknemer_id = NULL;
	
	
	
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
	 *
	 * alle loonstroken
	 *
	 */
	public function all()
	{
		$sql = "SELECT * FROM loonstroken_pdf WHERE deleted = 0 ";
		
		if( $this->_werknemer_id !== NULL )
			$sql .= " AND werknemer_id = $this->_werknemer_id ";

		$sql .= " ORDER BY jaaropgave DESC, jaar DESC, periode DESC";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'loonstrook_id', 'NULL' );
		
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set werknemer_id
	 *
	 */
	public function werknemer( $id ) :LoonstrokenGroup
	{
		$this->_werknemer_id = intval($id);
		
		return $this;
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