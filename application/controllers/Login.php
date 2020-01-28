<?php

use models\users\User;

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
			{
				if( $this->session->ref_url !== NULL && strpos( $this->session->ref_url, 'usermanagement' ) === false && strpos($this->session->ref_url, 'login' ) === false )
					redirect( $this->session->ref_url, 'location' );
				else
					redirect( $this->config->item( 'base_url' ) . 'dashboard/werkgever', 'location' );
			}
			
			//display errors when login fails
			if( $this->auth->errors() != false )
				$this->smarty->assign('msg', msg('danger', $this->auth->errors() ));
		}
		
		$this->smarty->display('login.tpl');
		
		/*
		if( $_SERVER['REMOTE_ADDR'] != '82.74.254.28' )
			$this->smarty->display('onderhoud.tpl');
		else
			$this->smarty->display('login.tpl');*/
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// recover
	//-----------------------------------------------------------------------------------------------------------------
	public function wachtwoordvergeten()
	{
		//stuur reset email
		if( isset($_POST['email']) )
		{
			$user = new User();
			if( !$user->resetPasswordEmail( $_POST['email'] ) )
				$this->smarty->assign('msg', msg('danger', $user->errors() ));
			else
				$this->smarty->assign('success', true);
		}
	
		
		$this->smarty->display('wachtwoordvergeten.tpl');
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
