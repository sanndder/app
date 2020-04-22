<?php

use models\boekhouding\OmzetGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Omzet extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		
		$this->smarty->display('overzichten/omzet/overzicht.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// json data
	//-----------------------------------------------------------------------------------------------------------------
	public function json()
	{
		$omzetGroup = new OmzetGroup();
		
		$data['omzet'] = array_values($omzetGroup->omzet());
		$data['kosten'] = array_values($omzetGroup->kosten());
		
		header('Content-Type: application/json');

		echo json_encode($data);
	}
}
