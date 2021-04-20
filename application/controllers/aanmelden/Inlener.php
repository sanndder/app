<?php

use models\boekhouding\MargeGroup;
use models\boekhouding\OmzetGroup;
use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\Tijdvak;
use models\verloning\LoonstrokenGroup;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;
use models\zzp\Zzp;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Inlener extends EX_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werkgever
	//-----------------------------------------------------------------------------------------------------------------
	public function nieuweklant()
	{
		
		$this->smarty->display('aanmelden/nieuweinlener.tpl');
	}
}
