<?php

namespace models\zorgverzekering;

use models\Connector;
use models\file\Excel;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Voor evenuteel verschillende zorgverzekeringen
 *
 *
 */

class ZorgverzekeringFactory extends Connector
{
	
	static function initZorgverzekering()
	{
		return new Hollandzorg();
	}
}


?>