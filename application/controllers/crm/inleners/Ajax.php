<?php

use models\api\CreditSafe;
use models\api\Kvk;
use models\cao\CAOGroup;
use models\inleners\Inlener;
use models\verloning\Urentypes;
use models\verloning\Vergoeding;

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
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )forbidden();

	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// credit check
	//-----------------------------------------------------------------------------------------------------------------
	public function creditinfo( $kvknr )
	{
		header( 'Content-Type: application/json' );
		
		$kvk = new Kvk( $kvknr );
		$info = $kvk->companyAddress();
		/*
		$creditsafe = new CreditSafe();
		$info = $creditsafe->searchCompany( $kvknr );
		*/
		if( $kvk->errors() !== false )
		{
			$response['status'] = 'error';
			$response['error'] = $kvk->errors();
		}
		else
		{
			$response['status'] = 'success';
			$response['result'] = $info;
		}
		
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// CAO's voor inlener ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function caos()
	{
		$CAOgroup = new CAOGroup();
		$caos = $CAOgroup->inlener( $_POST['inlener_id'] );
		
		$response['status'] = 'success';
		$response['caos'] = $caos;
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// sbi codes ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function sbi( $kvknr )
	{
		$kvk = new Kvk( $kvknr );
		$sbi = $kvk->getSbiCodes();
		
		//msg
		if( $kvk->errors() === false )
		{
			$response['status'] = 'success';
			$response['sbi'] = $sbi;
		}
		else
			$response['error'] = $kvk->errors();
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// validate input urentype
	//-----------------------------------------------------------------------------------------------------------------
	public function setverkooptarief()
	{
		$urentypes = new Urentypes();
		
		//update
		if( $urentypes->updateVerkooptarief( $_POST ) !== false )
			$response = array( 'status' => 'success' );
		else
			$response = array( 'status' => 'error' );
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// validate input urentype
	//-----------------------------------------------------------------------------------------------------------------
	public function seturentypelabel()
	{
		$urentypes = new Urentypes();
		
		//update
		if( $urentypes->updateLabel( $_POST ) !== false )
			$response = array( 'status' => 'success' );
		else
			$response = array( 'status' => 'error' );
		
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
	// validate input vergoeding
	//-----------------------------------------------------------------------------------------------------------------
	public function validatevergoeding()
	{
		$vergoeding = new Vergoeding();
		
		//valideren
		$vergoeding->validateInlenerVergoeding();
		
		//init response
		$response = array( 'status' => 'error' );
		
		//msg
		if( $vergoeding->errors() === false )
			$response['status'] = 'success';
		else
			$response['error'] = $vergoeding->errors();
		
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
