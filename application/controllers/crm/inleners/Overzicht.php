<?php

use models\inleners\InlenerGroup;
use models\utils\VisitsLogger;

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
		
		//log visit
		$log = new VisitsLogger();
		$log->logCRMVisit( 'inlener', $this->uri->segment(5) );
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$log = new VisitsLogger();
		
		$inlenergroup = new InlenerGroup();
		$inleners = $inlenergroup->all( $_GET );

		//show($inleners);

		$this->smarty->assign('inleners', $inleners);
		$this->smarty->assign('last_visits', $log->getLastCRMVisits('inlener') );
		$this->smarty->display('crm/inleners/overzicht.tpl');
	}


}
