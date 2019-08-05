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
	// uplaod logo
	//--------------------------------------------------------------------------
	public function uploadlogo( $uitzender_id = NULL )
	{

		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'uitzender/logo' );
		$this->uploadfiles->setDatabaseTable( 'uitzenders_logo' );
		$this->uploadfiles->setFieldId( 'uitzender_id', $uitzender_id );
		$this->uploadfiles->setPrefix( 'logo_' );
		$this->uploadfiles->uploadfiles();

		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$this->uploadfiles->dataToDatabase( true );

			$preview[] = 'http://via.placeholder.com/150';
			$config[] = array('url' => '/test', 'caption' => 'test.jpg', 'key' => 101, 'size' => 100);
			$result = [ 'initialPreview' => $preview,'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
		}
		else
			$result['error'] = $this->uploadfiles->errors();

		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
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
