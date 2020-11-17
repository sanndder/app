<?php

use models\documenten\Document;
use models\users\User;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Teken document
 */

class Sign extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//uitloggen om fouten te voorkomen
		if( isset($_SESSION['logindata']['main']['user_id']) )
		{
			$this->auth->logout( $_SESSION['logindata']['main']['user_id'], $_SESSION['logindata']['main']['sid'], 'sign', false);
			redirect(  current_url() .'?'. $_SERVER['QUERY_STRING']  ,'location' );
		}
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// activate new user
	//-----------------------------------------------------------------------------------------------------------------
	public function document()
	{
		if( !isset($_GET['document']) || !isset($_GET['werknemer']))forbidden();
		
		$document = new Document();
		
		
		if( $document->getByHash($_GET['document']) )
		{
			
			$details = $document->details();
			if( $details['werknemer_id'] != $_GET['werknemer'] )
				die('Geen toegang');
		}
		
		if( isset($details) )
		{
			$this->smarty->assign( 'document', $details );
			$this->smarty->display( 'sign/document.tpl' );
		}
	}
	
	
}
