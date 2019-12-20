<?php

use models\email\EmailGroup;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Emailcentrum extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//alleen voor werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht emails
	//-----------------------------------------------------------------------------------------------------------------
	public function index( $folder = '' )
	{
		$emailgroup = new EmailGroup();
		$emails = $emailgroup->get( $folder );
		
		$this->smarty->assign( 'folder', $folder );
		$this->smarty->assign( 'emails', $emails );
		$this->smarty->display('emailcentrum/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// email details
	//-----------------------------------------------------------------------------------------------------------------
	public function view()
	{
		
		
		$this->smarty->display('emailcentrum/view.tpl');
	}
	
	
}
