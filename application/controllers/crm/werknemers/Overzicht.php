<?php

use models\Werknemers\WerknemerGroup;

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
		$werknemergroup = new WerknemerGroup();
		$werknemers = $werknemergroup->all( $_GET );

		//show($werknemers);

		$this->smarty->assign('werknemers', $werknemers);
		$this->smarty->display('crm/werknemers/overzicht.tpl');
	}


}
