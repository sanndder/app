<?php

use models\utils\history;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Ajax controller
 * Voor algemene ajax call
 */
class Ajax extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// get history
	//-----------------------------------------------------------------------------------------------------------------
	public function gethistory( $table, $field, $index )
	{
		$log = new History();
		$data = $log->table( $table )->index( array($field => $index ) )->data();
		
		if( is_array($data) )
			echo json_encode($data);
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// accept algemene voorwaarden
	//-----------------------------------------------------------------------------------------------------------------
	public function acceptAV()
	{
		if( $this->user->user_type == 'uitzender' )
		{
			if( $this->uitzender->acceptAV() )
				echo true;
			else
				echo false;
		}
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// handtekening toevoegen aan doc
	//-----------------------------------------------------------------------------------------------------------------
	public function signdocument()
	{
		//show($_POST);
		
		$encoded_image = explode(",", $_POST['imageData'])[1];
		$decoded_image = base64_decode($encoded_image);
		
		$dir = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id . '/';
		//file_put_contents( $dir . "signature.png", $decoded_image);
		
		file_put_contents( $dir . "signature.jpg", $decoded_image);
		
		
	}

}
