<?php

namespace models\zorgverzekering;

use models\Connector;
use models\file\Excel;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Parent voor zorgverzekering
 *
 *
 */

class Zorgverzekering extends Connector
{

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
	 * get werknemers met zorgverzekering
	 *
	 */
	public function werknemers()
	{
		$werknemergroup = new WerknemerGroup();
		return $werknemergroup->zorgverzekering();
	}
	
}


?>