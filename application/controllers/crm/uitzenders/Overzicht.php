<?php

use models\Uitzenders\UitzenderLists;

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
		$uitzenderlist = new UitzenderLists();
		$uitzenders = $uitzenderlist->all( $_GET );

		//show($uitzenders);

		$this->smarty->assign('uitzenders', $uitzenders);
		$this->smarty->display('crm/uitzenders/overzicht.tpl');
	}


}
