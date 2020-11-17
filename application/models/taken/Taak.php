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

class Taak extends Connector
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
	 * Details
	 *
	 */
	public function details()
	{
	
	}


}

?>