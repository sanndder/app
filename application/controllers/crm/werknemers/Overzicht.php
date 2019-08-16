<?php
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
		$werknemerlist = new \models\Werknemers\WerknemerLists();
		$werknemers = $werknemerlist->all( $_GET );

		//show($werknemers);

		$this->smarty->assign('werknemers', $werknemers);
		$this->smarty->display('crm/werknemers/overzicht.tpl');
	}


}
