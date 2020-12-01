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
		if( $this->user->user_type != 'werkgever' )forbidden();
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
		
		$data['omzet'] = array_values($omzetGroup->omzetverkoop());
		$data['omzetuitzenden'] = array_values($omzetGroup->omzetuitzenden());
		$data['loonkosten'] = array_values($omzetGroup->loonkosten());
		$data['winst'] = array_values($omzetGroup->winst());
		$data['winstcum'] = array_values($omzetGroup->winstCum());
		
		header('Content-Type: application/json');

		echo json_encode($data);
	}
}
