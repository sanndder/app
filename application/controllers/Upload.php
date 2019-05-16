<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Upload extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//$this->load->model('test2_model', 'test2');
		$this->load->model('upload_model', 'uploadfiles');

	}


	//--------------------------------------------------------------------------
	// AJAX upload for bootstrap file input
	//--------------------------------------------------------------------------
	public function index()
	{
		$this->uploadfiles->setUploadDir( 'files' );
		$this->uploadfiles->setPrefix( 'img_' );
		$this->uploadfiles->uploadfiles();

		//show($_GET);
		//show($_POST);
		//show($_FILES);


		header('Content-Type: application/json'); // set json response headers

		$result['error'] = 'Er gaat wat mis';

		echo json_encode($result);

		die();


	}

}
