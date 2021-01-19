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
	public function json( $jaar = 2020 )
	{
		$omzetGroup = new OmzetGroup();
		$omzetGroup->jaar( $jaar );
		
		$data[$jaar]['omzet'] = array_values($omzetGroup->omzetverkoop() );
		$data[$jaar]['omzetuitzenden'] = array_values($omzetGroup->omzetuitzenden() );
		$data[$jaar]['loonkosten'] = array_values($omzetGroup->loonkosten() );
		$data[$jaar]['winst'] = array_values($omzetGroup->winst() );
		$data[$jaar]['winstcum'] = array_values($omzetGroup->winstCum() );
		
		header('Content-Type: application/json');

		echo json_encode($data);
	}
}
