<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Mijnaccount extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

	}


	//--------------------------------------------------------------------------
	// Overzicht
	//--------------------------------------------------------------------------
	public function index()
	{

		$this->smarty->display('mijnaccount/overzicht.tpl');
	}



}
