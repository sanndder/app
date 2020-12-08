<?php

use models\debiteurbeheer\OpenstaandeFacturen;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Facturen extends MY_Controller
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
		$openstaandeFacturen = new OpenstaandeFacturen();
		$facturen = $openstaandeFacturen->facturen( $_POST );

		//show($facturen);
		
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display('debiteurbeheer/facturen.tpl');
	}
	
}
