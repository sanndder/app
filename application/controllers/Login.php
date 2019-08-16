<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Auth_model $auth class
 */

class Login extends EX_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Login scherm
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//login knop
		if( isset($_POST['login']) )
		{
			//try login
			if( $this->auth->login() )
				redirect( $this->config->item('base_url') . 'crm/uitzenders' ,'location');

			//display errors when login fails
			if( $this->auth->errors() != false )
				$this->smarty->assign('msg', msg('danger', $this->auth->errors() ));
		}


		$this->smarty->display('login.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Hash pass, alleen lokaal beschikbaar
	//-----------------------------------------------------------------------------------------------------------------
	public function hash( $val = '' )
	{
		if( ENVIRONMENT == 'development' )
		{
			$pass =  $this->auth->hashPassword( $val );
			show($pass);
		}
	}



}
