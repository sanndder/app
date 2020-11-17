<?php

use models\uitzenders\UitzenderGroup;
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
		//alleen in devolpment TODO: remove
		if( isset($_GET['del']) )
		{
			$werknemer = new Werknemer( NULL );
			$werknemer->del($_GET['del']);
		}

		//show($werknemers);
		/*
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('werknemers', $werknemers);
		$this->smarty->assign('last_visits', $log->getLastCRMVisits('werknemer') );*/
		$this->smarty->display('crm/prospects/overzicht.tpl');
	}
	
}