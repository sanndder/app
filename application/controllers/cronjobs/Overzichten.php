<?php

use models\email\Email;

/**
 * Account en userbeheer
 */

class Overzichten extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Index
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
	
		$email = new Email();
	
		$to['email'] = 'hsmeijering@home.nl';
		$to['name'] = 'Sander Meijering';
		
		if( $to['email'] == '' )
			return false;
		
		$email->to( $to );
		$email->setSubject('Cronjob test');
		$email->setTitel('Cronjob test');
		$email->setBody('Test <br /><br />' . $_SERVER['REMOTE_ADDR'] );
		$email->useHtmlTemplate( 'default' );
		$email->delay( 0 );
		
		$email->send();
		
		return true;
	}
	

}
