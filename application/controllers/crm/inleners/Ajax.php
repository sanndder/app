<?php

use models\forms\Formbuilder;
use models\inleners\Inlener;
use models\verloning\Urentypes;

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

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();


	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// credit check
	//-----------------------------------------------------------------------------------------------------------------
	public function creditinfo($kvknr)
	{
		header( 'Content-Type: application/json' );
		
		$response['status'] = 'error';
		
		echo json_encode( $response );
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// validate input urentype
	//-----------------------------------------------------------------------------------------------------------------
	public function validateurentype()
	{
		$urentypes = new Urentypes();
		
		//valideren
		$urentypes->validateInlenerUrentype();
		
		//init response
		$response = array( 'status' => 'error' );
		
		//msg
		if( $urentypes->errors() === false )
			$response['status'] = 'success';
		else
			$response['error'] = $urentypes->errors();
		
		echo json_encode( $response );
	}
	

	//-----------------------------------------------------------------------------------------------------------------
	// get contactpersoon JSON
	//-----------------------------------------------------------------------------------------------------------------
	public function getcontactpersoon( $inlener_id = NULL, $contact_id = 0 )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		//allemaal ophalen
		$contactpersoon = $inlener->contactpersoon($contact_id);

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//contactpersoon is bekend
		if(isset($contactpersoon))
		{
			$formdata = $formbuidler->table( 'inleners_contactpersonen' )->data( $contactpersoon )->build();
			echo json_encode($formdata);
		}

		//nieuwe toevoegen
		if( $contact_id == 0 )
		{
			$formdata = $formbuidler->table( 'inleners_contactpersonen' )->build();
			echo json_encode($formdata);
		}
	}


	//-----------------------------------------------------------------------------------------------------------------
	// set contactpersoon
	//-----------------------------------------------------------------------------------------------------------------
	public function setcontactpersoon( $inlener_id = NULL, $contact_id = 0 )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		$contactpersoon = $inlener->setContactpersoon( $contact_id );
		$errors = $inlener->errors();

		//init response
		$response = array( 'status' => 'error' );

		//msg
		if( $errors === false )
			$response['status'] = 'success';
		else
			$response['error'] = $errors;

		echo json_encode( $response );
	}
}
