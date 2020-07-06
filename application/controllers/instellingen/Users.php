<?php

use models\forms\Formbuilder;
use models\users\User;
use models\users\UserGroup;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Users extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//usertype naa smarty
		$this->smarty->assign('usertype', $this->user->user_type);
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$usersgroup = new UserGroup();
		$users = $usersgroup->all();
	
		$this->smarty->assign('users', $users);
		$this->smarty->display('instellingen/users/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// details gebruiker
	//-----------------------------------------------------------------------------------------------------------------
	public function view( $user_id )
	{
		//init
		$user = new User( $user_id );
		
		//backup
		if(isset($_GET['backup']))
		{
			if( $user->backupPassword() )
				redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/users/view/' . $user_id ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Wachtwoord kon niet worden gekopieerd' ) );
		}
		
		//backup
		if(isset($_GET['reset']))
		{
			if( $user->resetPassword() )
				redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/users/view/' . $user_id ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Wachtwoord kon niet worden hersteld' ) );
		}
		
		//default
		if(isset($_GET['default']))
		{
			if( $user->defaultPassword() )
				redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/users/view/' . $user_id ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Wachtwoord kon niet worden ingesteld' ) );
		}
		
		//default
		if(isset($_GET['resend']))
		{
			if( $user->resendWelkomsmail() )
				redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/users/view/' . $user_id ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Welkomsmail kon niet opnieuw worden verstuurd' ) );
		}
		
		$this->smarty->assign('user', $user->data());
		$this->smarty->display('instellingen/users/view.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// details gebruiker
	//-----------------------------------------------------------------------------------------------------------------
	public function edit( $user_id )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init
		$user = new User( $user_id );

		$this->smarty->assign('user', $user->data() );
		$this->smarty->display('instellingen/users/edit.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Nieuwe gebruiker
	//-----------------------------------------------------------------------------------------------------------------
	public function add()
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init
		$user = new User();
		$user_data = array();
		
		//is er een user_type ingesteld
		if( isset($_GET['user_type']) && isset($_GET['id']) )
			$user_data =  $user->initNewUser();

		//set bedrijfsgegevens
		if( isset($_POST['set']) )
		{
			$data = $user->add();
			$errors = $user->errors();

			//msg
			if( $errors === false )
				redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/users/view/' . $user->id() ."?new=true"  ,'location' );
			else
			{
				if( key($errors) == 0 )
					$this->smarty->assign( 'msg', msg( 'warning', $errors ) );
				else
					$this->smarty->assign( 'msg', msg( 'warning', 'Gegevens konden niet worden opgeslagen, controleer uw invoer!' ) );
			}
		}
		else
		{
			$data = array();
			$errors = false;
		}

		$formdata = $formbuidler->table( 'user' )->data( $data )->errors( $errors )->build();
		
		$this->smarty->assign('formdata', $formdata);
		$this->smarty->assign('user_data', $user_data);
		$this->smarty->assign('bedrijfsnaam', $this->werkgever->bedrijfsnaam());

		$this->smarty->display('instellingen/users/toevoegen.tpl');
	}

}
