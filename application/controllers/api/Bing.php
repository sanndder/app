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
	// distance api
	//-----------------------------------------------------------------------------------------------------------------
	public function distance()
	{
		
		$bing = new \models\api\Bing();
		
		//time call
		$return['time'] = $bing->distance( $_POST['location1'], $_POST['location2'], 'time');
		//$return['distance'] = $bing->distance( $_POST['location1'], $_POST['location2'], 'distance');
		
		if( $return['time']  === false )
		{
			$return['status'] = 'error';
			$return['error'] = $bing->errors();
		}
		
		$return['status'] = 'success';
		
		echo  json_encode( $return );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// autocomplete
	//-----------------------------------------------------------------------------------------------------------------
	public function suggestlocations()
	{
		$return = array();
		$bing = new \models\api\Bing();
		$locations = $bing->suggestLocations( $_GET['term'] );
		echo  json_encode( $locations );
	}
	
}
