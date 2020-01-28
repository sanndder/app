<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Bing extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		header( 'Content-Type: application/json' );
	}

	//-----------------------------------------------------------------------------------------------------------------
	// get cao data
	//-----------------------------------------------------------------------------------------------------------------
	public function suggestlocations()
	{
		$return = array();
		$bing = new \models\api\Bing();
		$locations = $bing->suggestLocations( $_GET['term'] );
		echo  json_encode( $locations );
	}
	
}
