<?php

use models\boekhouding\VoorfinancieringOverzicht;
use models\debiteurbeheer\OpenstaandeFacturen;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Voorfinanciering extends MY_Controller
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
		$voorfinanciering = new VoorfinancieringOverzicht();
		
		
		$this->smarty->assign( 'openstaand', $voorfinanciering->openstaandefinanciering() );
		$this->smarty->assign( 'terug', $voorfinanciering->terugtebetalen() );
		$this->smarty->assign( 'facturen', $voorfinanciering->facturen() );
		$this->smarty->display('debiteurbeheer/voorfinanciering.tpl');
	}
	
}
