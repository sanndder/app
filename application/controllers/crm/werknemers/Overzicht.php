<?php

use models\utils\VisitsLogger;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;

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
		
		$werknemergroup = new WerknemerGroup();
		$werknemers = $werknemergroup->all( $_GET );
		
		//alleen in devolpment TODO: remove
		if( isset($_GET['del']) )
		{
			$werknemer = new Werknemer( NULL );
			$werknemer->del($_GET['del']);
		}

		//show($werknemers);

		$this->smarty->assign('werknemers', $werknemers);
		$this->smarty->assign('last_visits', $log->getLastCRMVisits('werknemer') );
		$this->smarty->display('crm/werknemers/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Succelvol toegevoegd
	//-----------------------------------------------------------------------------------------------------------------
	public function success()
	{
		$this->smarty->display('crm/werknemers/success.tpl');
	}
}
