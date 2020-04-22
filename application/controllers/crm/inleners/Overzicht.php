<?php

use models\inleners\Inlener;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
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
		
		//Deze pagina mag alleen bezocht worden door werkgever of uitzender
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )forbidden();
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
			$inlener = new Inlener( NULL );
			$inlener->del($_GET['del']);
		}
		
		//acties
		if( isset($_GET['action']) && $_GET['action'] == 'archief' )
		{
			$inlener = new Inlener( $_GET['inlener_id'] );
			$inlener->setArchief( true );
		}
		
		//kredietaanvragen ophalen
		$kredietgroup = new KredietaanvraagGroup();
		$kredietaanvragen = $kredietgroup->all();
		
		// alle gewone inleners ophalen
		$inlenergroup = new InlenerGroup();
		$inleners = $inlenergroup->all( $_GET );

		//combineren
		$inleners = $inleners + $kredietaanvragen;

		$this->smarty->assign('inleners', $inleners);
		$this->smarty->assign('last_visits', $log->getLastCRMVisits('inlener') );
		$this->smarty->display('crm/inleners/overzicht.tpl');
	}


}
