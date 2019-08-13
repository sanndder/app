<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ajax extends MY_Controller
{

	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();


	}

	//--------------------------------------------------------------------------
	// get contactpersoon JSON
	//--------------------------------------------------------------------------
	public function getcontactpersoon( $werknemer_id = NULL, $contact_id = 0 )
	{
		//init werknemer object
		$werknemer = new \models\Werknemers\Werknemer( $werknemer_id );

		//allemaal ophalen
		$contactpersoon = $werknemer->contactpersoon($contact_id);

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//contactpersoon is bekend
		if(isset($contactpersoon))
		{
			$formdata = $formbuidler->table( 'werknemers_contactpersonen' )->data( $contactpersoon )->build();
			echo json_encode($formdata);
		}

		//nieuwe toevoegen
		if( $contact_id == 0 )
		{
			$formdata = $formbuidler->table( 'werknemers_contactpersonen' )->build();
			echo json_encode($formdata);
		}
	}


	//--------------------------------------------------------------------------
	// set contactpersoon
	//--------------------------------------------------------------------------
	public function setcontactpersoon( $werknemer_id = NULL, $contact_id = 0 )
	{
		//init werknemer object
		$werknemer = new \models\Werknemers\Werknemer( $werknemer_id );

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		$contactpersoon = $werknemer->setContactpersoon( $contact_id );
		$errors = $werknemer->errors();

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
