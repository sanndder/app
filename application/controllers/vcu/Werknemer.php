<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Werknemer extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonstroken
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{

		$this->smarty->display('vcu/werknemer/overzicht.tpl');
	}

}
