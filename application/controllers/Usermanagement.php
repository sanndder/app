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

}
