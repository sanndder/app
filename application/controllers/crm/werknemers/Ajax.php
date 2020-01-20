<?php

use models\documenten\IDbewijs;
use models\verloning\Urentypes;
use models\werknemers\Plaatsing;
use models\werknemers\Werknemer;

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
	// plaatsing voor werknemer toevoegen
	//-----------------------------------------------------------------------------------------------------------------
	public function addplaatsing()
	{
		$response = array( 'status' => 'error' );
		
		$plaatsing = new Plaatsing();
		$plaatsing->add( $_POST );
		
		if( $plaatsing->errors() === false )
			$response = array( 'status' => 'success' );
		else
			$response['error'] = $plaatsing->errors();
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// get contactpersoon JSON
	//-----------------------------------------------------------------------------------------------------------------
	public function toggleurentype()
	{
		//init werknemer object
		$urentypes = new Urentypes();
		
		$urentypes->setWerknemerUrentypeID( $_GET['id'] )->setActiveStatus( $_GET['state'] );
		
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
