<?php

use models\debiteurbeheer\OpenstaandeFacturen;
use models\uitzenders\UitzenderGroup;

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
		
		
		$eigenfacturen = $openstaandeFacturen->eigenvermogen();

		//show($facturen);
		
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign( 'eigenfacturen', $eigenfacturen );
		$this->smarty->assign( 'totaal', $openstaandeFacturen->totaaleigenvermogen() );
		$this->smarty->assign( 'totaal_uitzenders', $openstaandeFacturen->totaaluitzenders() );
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display('debiteurbeheer/facturen.tpl');
	}
	
}
