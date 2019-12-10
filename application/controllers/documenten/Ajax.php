<?php

use models\documenten\Template;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ajax extends MY_Controller
{
	
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//Deze pagina mag alleen bezocht worden door werkgever en uitzender
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )forbidden();
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// new template
	//-----------------------------------------------------------------------------------------------------------------
	public function validatetemplate()
	{
		$template = new Template();
		
		//valideren
		$template->validateNewTemplateInput();
		
		//init response
		$response = array( 'status' => 'error' );
		
		//msg
		if( $template->errors() === false )
			$response['status'] = 'success';
		else
			$response['error'] = $template->errors();
		
		echo json_encode( $response );
	}
	
}
