<?php

use models\users\User;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Usermanagement extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//uitloggen om fouten te voorkomen
		if( isset($_SESSION['logindata']['main']['user_id']) )
		{
			$this->auth->logout( $_SESSION['logindata']['main']['user_id'], $_SESSION['logindata']['main']['sid'], 'usermanagement', false);
			redirect(  current_url() .'?'. $_SERVER['QUERY_STRING']  ,'location' );
		}
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// activate new user
	//-----------------------------------------------------------------------------------------------------------------
	public function newuser()
	{
		if( !isset($_GET['user']) )forbidden();
		
		$user = new User();
		$user->getByNewHash( $_GET['user'] );
		
		if( $user->newKeyExpired() )
		{
			$this->smarty->assign( 'expired', true );
			$this->smarty->assign( 'msg', $user->errors() );
		}
		
		//wijzig wachtwoord
		if( isset($_POST['setpassword'] ))
		{
			if( $user->updatePassword( true ) === true )
				$this->smarty->assign( 'success', true );
			else
				$this->smarty->assign('msg', msg('warning', $user->errors() ));
		}
		
		$this->smarty->display('usermanagement/newuser.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Reset user
	//-----------------------------------------------------------------------------------------------------------------
	public function resetuser()
	{
		if( !isset($_GET['user']) )forbidden();
		
		$user = new User();
		$user->getByResetHash( $_GET['user'] );
		
		if( $user->resetKeyExpired() )
		{
			$this->smarty->assign( 'expired', true );
			$this->smarty->assign( 'msg', $user->errors() );
		}
		
		//wijzig wachtwoord
		if( isset($_POST['setpassword'] ))
		{
			if( $user->updatePassword( true ) === true )
				$this->smarty->assign( 'success', true );
			else
				$this->smarty->assign('msg', msg('warning', $user->errors() ));
		}
		
		
		$this->smarty->assign( 'reset', true );
		$this->smarty->display('usermanagement/newuser.tpl');
	}
	
	
}
