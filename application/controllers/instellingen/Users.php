<?php

use models\Users\UserGroup;

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
		//show( $this->user->user_type );
	}


	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//show($this->user);
		
		$usersgroup = new UserGroup();
		$users = $usersgroup->all();
	
		$this->smarty->assign('users', $users);
		$this->smarty->assign('usertype', $this->user->user_type);
		$this->smarty->display('instellingen/users/overzicht.tpl');
	}

}
