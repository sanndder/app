<?php

use models\uitzenders\UitzenderGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ureninvoer extends MY_Controller
{


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// hoofdpagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$this->smarty->assign('uitzenders', UitzenderGroup::list()); //uitzenders voor werkgever ophalen
		
		if( isset($_GET['dummy']))
			$this->smarty->display('ureninvoer/main_dummy.tpl');
		else
			$this->smarty->display('ureninvoer/main.tpl');
		
	}


}
