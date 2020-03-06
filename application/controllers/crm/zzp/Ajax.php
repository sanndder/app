<?php

use models\documenten\IDbewijs;
use models\verloning\Urentypes;
use models\werknemers\Plaatsing;
use models\werknemers\Zzp;

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
	// evrkooptarief wijzigen
	//-----------------------------------------------------------------------------------------------------------------
	public function setverkooptarief()
	{
		$urentype = new Urentypes();
		$urentype->setZzpUrentypeID( $_POST['id'] );
		
		if( $urentype->setVerkooptarief( $_POST['tarief'] ))
			$response = array( 'status' => 'success' );
		else
			$response = array( 'status' => 'error' );
		
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uurtarief wijzigen
	//-----------------------------------------------------------------------------------------------------------------
	public function setuurtarief()
	{
		$urentype = new Urentypes();
		$urentype->setZzpUrentypeID( $_POST['id'] );
		
		if( $urentype->setUurtarief( $_POST['tarief'] ))
			$response = array( 'status' => 'success' );
		else
			$response = array( 'status' => 'error' );
		
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// marge wijzigen
	//-----------------------------------------------------------------------------------------------------------------
	public function setmarge()
	{
		$urentype = new Urentypes();
		$urentype->setZzpUrentypeID( $_POST['id'] );
		
		if( $urentype->setMarge( $_POST['tarief'] ))
			$response = array( 'status' => 'success' );
		else
			$response = array( 'status' => 'error' );
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// toggle uren type
	//-----------------------------------------------------------------------------------------------------------------
	public function toggleurentype()
	{
		//init werknemer object
		$urentypes = new Urentypes();
		
		$urentypes->setZzpUrentypeID( $_GET['id'] )->setActiveStatus( $_GET['state'] );
		
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
	// delete ID bewijs
	//-----------------------------------------------------------------------------------------------------------------
	public function deleteidbewijs( $werknemer_id, $side )
	{
		
		//init werknemer object
		$idbewijs = new IDbewijs();
		$idbewijs->werknemer($werknemer_id)->deleteID( $side );
		
		//init response
		$response = array( 'status' => 'error' );
		
		//msg
		if( $idbewijs->errors() === false )
			$response['status'] = 'success';
		else
			$response['error'] = $idbewijs->errors();
		
		echo json_encode( $response );
		
	}
	
}
