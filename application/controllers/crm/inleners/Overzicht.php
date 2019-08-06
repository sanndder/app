<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Overzicht extends MY_Controller
{

	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

	}


	//--------------------------------------------------------------------------
	// Overzicht pagina
	//--------------------------------------------------------------------------
	public function index()
	{
		$inlenerlist = new \models\Inleners\InlenerLists();
		$inleners = $inlenerlist->all( $_GET );

		//show($inleners);

		$this->smarty->assign('inleners', $inleners);
		$this->smarty->display('crm/inleners/overzicht.tpl');
	}


}
