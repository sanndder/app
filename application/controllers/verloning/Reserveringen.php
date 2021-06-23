<?php

use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\verloning\LoonstrokenZip;
use models\verloning\ReserveringenExcel;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Reserveringen extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//beveiligen
		if(	 $this->user->user_type != 'werkgever' )forbidden();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uploaden
	//-----------------------------------------------------------------------------------------------------------------
	public function uploaden()
	{
		$this->smarty->assign( 'last_update', ReserveringenExcel::lastUpdate() );
		$this->smarty->assign( 'stand', ReserveringenExcel::stand() );
		$this->smarty->display('verloning/reserveringen/uploaden.tpl');
	}
	

}
