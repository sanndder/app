<?php

use models\forms\Formbuilder;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Mijnaccount extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//load form builder
		$formbuidler = new Formbuilder();

		$usermanagement = new models\Usermanagement();
		$usermanagement->setUserId( $this->user->user_id );

		//wijzig wachtwoord
		if( isset($_POST['setpassword'] ))
		{
			if( $usermanagement->updatePassword() === true )
				$this->smarty->assign('msg_password', msg('success', 'Wachtwoord gewijzigd!'));
			else
				$this->smarty->assign('msg_password', msg('warning', $usermanagement->errors() ));
		}

		//set user data
		if( isset($_POST['set'] ))
		{
			$user = $usermanagement->setUser();
			$errors = $usermanagement->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			//get user from global user model
			$user['username'] = $this->user->username;
			$user['naam'] = $this->user->user_name;

			$errors = false; //no errors
		}

		//formulier opbouwen
		$formdata = $formbuidler->table( 'user' )->data( $user )->errors( $errors )->build();

		$this->smarty->assign('formdata', $formdata);
		$this->smarty->display('mijnaccount/overzicht.tpl');
	}



}
