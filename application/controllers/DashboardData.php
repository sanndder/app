<?php

use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\verloning\LoonstrokenGroup;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class DashboardData extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// data omzet laatste weken
	//-----------------------------------------------------------------------------------------------------------------
	public function omzetLaatsteWeken()
	{
		if( $this->user->user_type != 'werkgever' )	forbidden();
		
		header('Content-Type: application/json');
		
		echo '[
			  {
				"maand": "januari",
				"value": 1203
			  },
			  {
				"maand": "februari",
				"value": 480
			  },{
				"maand": "maart",
				"value": 790
			  },
			  {
				"maand": "april",
				"value": 1423
			  },
			  {
				"maand": "mei",
				"value": 1222
			  }
			  ]';
	}
	

}
