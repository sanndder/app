<?php

use models\zorgverzekering\ZorgverzekeringFactory;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Zorgverzekering extends MY_Controller
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
		$zorgverzekering = ZorgverzekeringFactory::initZorgverzekering();
		
		
		
		$this->smarty->assign( 'werknemers', $zorgverzekering->werknemers() );
		$this->smarty->display('overzichten/zorgverzekering/overzicht.tpl');
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// download lijst debiteuren of crediteuren
	//-----------------------------------------------------------------------------------------------------------------
	public function lijst( $type )
	{
		$boekhouding = new \models\boekhouding\Snelstart();
		
		$boekhouding->downloadRelaties( $type );
	}
	
	
}
