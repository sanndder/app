<?php
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
	// get contactpersoon JSON
	//-----------------------------------------------------------------------------------------------------------------
	public function getcontactpersoon( $inlener_id = NULL, $contact_id = 0 )
	{
		//init inlener object
		$inlener = new \models\Inleners\Inlener( $inlener_id );

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
		$inlener = new \models\Inleners\Inlener( $inlener_id );

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
