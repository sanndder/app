<?php

use models\utils\VisitsLogger;
use models\zzp\ZzpGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Overzicht extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$log = new VisitsLogger();
		
		$zzpgroup = new ZzpGroup();
		$zzpers = $zzpgroup->all( $_GET );

		//show($werknemers);

		$this->smarty->assign('zzpers', $zzpers);
		$this->smarty->assign('last_visits', $log->getLastCRMVisits('zzp') );
		$this->smarty->display('crm/zzp/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Succelvol toegevoegd
	//-----------------------------------------------------------------------------------------------------------------
	public function success()
	{
		$this->smarty->display('crm/zzp/success.tpl');
	}
}
