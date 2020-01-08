<?php

use models\uitzenders\Uitzender;
use models\uitzenders\UitzenderGroup;
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

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$log = new VisitsLogger();
		
		//alleen in devolpment TODO: remove
		if( isset($_GET['del']) )
		{
			$uitzender = new Uitzender( NULL );
			$uitzender->del($_GET['del']);
		}
		
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		
		$uitzendergroup = new UitzenderGroup();
		$uitzenders = $uitzendergroup->all( $_GET );

		//show($uitzenders);

		$this->smarty->assign('uitzenders', $uitzenders);
		$this->smarty->assign('last_visits', $log->getLastCRMVisits('uitzender') );
		$this->smarty->display('crm/uitzenders/overzicht.tpl');
	}


}
