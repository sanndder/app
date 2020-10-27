<?php

namespace models\zorgverzekering;

use models\Connector;
use models\file\Excel;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Hollandzorg extends Zorgverzekering
{
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
	}
	
}


?>