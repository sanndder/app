<?php

use models\documenten\Document;
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
		//if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )forbidden();
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// handtekening toevoegen aan doc
	//-----------------------------------------------------------------------------------------------------------------
	public function signdocument( $document_id )
	{
		$document = new Document( $document_id );
		
		//check rights
		if( !$document->userHasAccess() )
			die('Geen toegang');
		
		if( $document->sign() === true )
			$result['status'] = 'success';
		else
		{
			$result['status'] = 'error';
			$result['error'] = 'Ondertekening is mislukt, neem contact met ons op';
		}
		
		echo json_encode($result);
	}
	

	//-----------------------------------------------------------------------------------------------------------------
	// new template
	//-----------------------------------------------------------------------------------------------------------------
	public function validatetemplate()
	{
		if( $this->user->user_type != 'werkgever' ) forbidden();
		
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
