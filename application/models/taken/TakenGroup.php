<?php

namespace models\taken;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class TakenGroup extends Connector
{
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $id = NULL  )
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Haal categorien lijst op
	 *
	 */
	public function categorien()
	{
		$query = $this->db_user->query( "SELECT categorie_id, categorie FROM taken_categorien WHERE deleted = 0 ORDER BY categorie" );
		return DBhelper::toList( $query, array('categorie_id'=>'categorie'), 'NULL' );
	}
	
}

?>