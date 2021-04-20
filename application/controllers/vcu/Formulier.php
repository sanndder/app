<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Formulier extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// d08 werknemer
	//-----------------------------------------------------------------------------------------------------------------
	public function d08()
	{
		$werknemer = new \models\werknemers\Werknemer( $this->werknemer->id );
		
		$this->smarty->assign( 'werknemer', $werknemer->gegevens() );
		$this->smarty->display('vcu/formulier/d08.tpl');
	}

}
