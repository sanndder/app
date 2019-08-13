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
	public function getcontactpersoon( $uitzender_id = NULL, $contact_id = 0 )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		//allemaal ophalen
		$contactpersoon = $uitzender->contactpersoon($contact_id);

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//contactpersoon is bekend
		if(isset($contactpersoon))
		{
			$formdata = $formbuidler->table( 'uitzenders_contactpersonen' )->data( $contactpersoon )->build();
			echo json_encode($formdata);
		}

		//nieuwe toevoegen
		if( $contact_id == 0 )
		{
			$formdata = $formbuidler->table( 'uitzenders_contactpersonen' )->build();
			echo json_encode($formdata);
		}
	}


	//--------------------------------------------------------------------------
	// set contactpersoon
	//--------------------------------------------------------------------------
	public function setcontactpersoon( $uitzender_id = NULL, $contact_id = 0 )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		$contactpersoon = $uitzender->setContactpersoon( $contact_id );
		$errors = $uitzender->errors();

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
