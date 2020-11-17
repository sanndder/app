<?php

use models\users\User;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Teken document
 */

class Klantenkaart extends EX_Controller {

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//uitloggen om fouten te voorkomen
		if( isset($_SESSION['logindata']['main']['user_id']) )
		{
			$this->auth->logout( $_SESSION['logindata']['main']['user_id'], $_SESSION['logindata']['main']['sid'], 'klantenkaart', false);
			redirect(  current_url() .'?'. $_SERVER['QUERY_STRING']  ,'location' );
		}
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// activate new user
	//-----------------------------------------------------------------------------------------------------------------
	public function index( $werkgever = NULL, $uitzender_id = NULL, $uitzender = NULL )
	{
		
		
	
	}
	
	
}
