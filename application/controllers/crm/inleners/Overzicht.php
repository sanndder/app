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
		$inlenergroup = new \models\Inleners\InlenerGroup();
		$inleners = $inlenergroup->all( $_GET );

		//show($inleners);

		$this->smarty->assign('inleners', $inleners);
		$this->smarty->display('crm/inleners/overzicht.tpl');
	}


}
