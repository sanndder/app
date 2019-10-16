<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Dashboard extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//beveiligen
		if(	$this->uri->segment(2) != $this->user->user_type )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type  ,'location' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werkgever
	//-----------------------------------------------------------------------------------------------------------------
	public function werkgever()
	{
		
		
		$this->smarty->display('dashboard/werkgever.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{
	
	
	}

}
