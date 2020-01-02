<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Concepten extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//alleen voor werkever
		if(	$this->user->user_type != 'werkgever' )forbidden();
		
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Afgesprokenwerkfactuur maken
	//-----------------------------------------------------------------------------------------------------------------
	public function afgesprokenwerk()
	{
		
		
		
		$this->smarty->display('facturatie/concepten/afgesprokenwerk.tpl');
	}

	
}
