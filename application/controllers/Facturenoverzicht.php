<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Facturenoverzicht extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//beveiligen
		//if(	$this->uri->segment(2) != $this->user->user_type )
			//redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type  ,'location' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen en marge uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{
		
		
		$this->smarty->display('facturenoverzicht/uitzender.tpl');
	}

}
