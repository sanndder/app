<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ureninvoer extends MY_Controller
{


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//--------------------------------------------------------------------------
	// hoofdpagina
	//--------------------------------------------------------------------------
	public function index()
	{

		$this->smarty->display('ureninvoer/main.tpl');
	}


}