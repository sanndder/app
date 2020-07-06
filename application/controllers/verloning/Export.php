<?php

use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\verloning\ExportEasylon;
use models\verloning\LoonstrokenZip;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Export extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//beveiligen
		if(	 $this->user->user_type != 'werkgever' )
			forbidden();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uploaden
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$export = new ExportEasylon();
	
		//go
		if( isset($_POST['go']) )
		{
			$export->export();
		}

		
		$this->smarty->display('verloning/export/overzicht.tpl');
	}
	

}
