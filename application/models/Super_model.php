<?php

use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Werkgever class
 * Wordt altijd geladen en is overal beschikbaar
 * Haalt alles uit de user databse,  gegevens uit de admin database komen in user_model
 *
 */

class Super_model extends MY_Model
{
	var $db_admin = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * construct
	 *
	 */
	public function __construct()
	{
		//connect to admin database
		$this->db_admin = $this->load->database('admin', TRUE);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * construct
	 *
	 */
	public function defaultBedrijf()
	{
		$sql = "SELECT werkgever_id, name, type, wid, wg_hash FROM werkgevers ORDER BY werkgever_id ASC LIMIT 1";
		$query = $this->db_admin->query( $sql );
		
		return DBhelper::toRow( $query, 'NULL' );
	}

}


?>