<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Users extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		//show( $this->user->user_type );
	}


	//--------------------------------------------------------------------------
	// test method
	//--------------------------------------------------------------------------
	public function index()
	{



		$usertype = 'werkgever';

		$this->smarty->assign('usertype', $usertype);
		$this->smarty->display('users/overzicht.tpl');
	}

}
