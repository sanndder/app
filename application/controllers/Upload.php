<?php

use models\File\Img;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Upload controller voor het afhandelen van alle uplaods vanuit ajax
 */

class Upload extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//$this->load->model('test2_model', 'test2');
		$this->load->model('upload_model', 'uploadfiles');

	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload handtekening uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadhantekeningwerkgever( $entiteit_id = NULL )
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setDatabaseTable( 'werkgever_handtekening' );
		$this->uploadfiles->setFieldId( 'entiteit_id', $entiteit_id );
		$this->uploadfiles->uploadfilesToDatabase();
		
		if( $this->uploadfiles->errors() === false)
		{
			/*$preview[] = 'http://via.placeholder.com/150';
			$config[] = array('url' => '/test', 'caption' => 'test.jpg', 'key' => 101, 'size' => 100);
			$result = [ 'initialPreview' => $preview,'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];*/
			$result = [];
		}
		else
			$result['error'] = $this->uploadfiles->errors();
		
		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}

	//-----------------------------------------------------------------------------------------------------------------
	// upload handtekening uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadhantekeninguitzender( $uitzender_id = NULL )
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setDatabaseTable( 'uitzenders_handtekening' );
		$this->uploadfiles->setFieldId( 'uitzender_id', $uitzender_id );
		$this->uploadfiles->uploadfilesToDatabase();

		if( $this->uploadfiles->errors() === false)
		{
			/*$preview[] = 'http://via.placeholder.com/150';
			$config[] = array('url' => '/test', 'caption' => 'test.jpg', 'key' => 101, 'size' => 100);
			$result = [ 'initialPreview' => $preview,'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];*/
			$result = [];
		}
		else
			$result['error'] = $this->uploadfiles->errors();

		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload logo werkgever
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadlogowerkgever( $entiteit_id = NULL )
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'werkgever/logo' );
		$this->uploadfiles->setDatabaseTable( 'werkgever_logo' );
		$this->uploadfiles->setFieldId( 'entiteit_id', $entiteit_id );
		$this->uploadfiles->setPrefix( 'logo_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$this->uploadfiles->dataToDatabase( true );
			
			$file_array = $this->uploadfiles->getFileArray();
			
			//img class aden om plaatje te resizen
			$image = new Img( $file_array );
			$image->setMaxWidthHeight( 700, 400 )->setQuality(80)->resize();
			
			/*$preview[] = 'http://via.placeholder.com/150';
			$config[] = array('url' => '/test', 'caption' => 'test.jpg', 'key' => 101, 'size' => 100);
			$result = [ 'initialPreview' => $preview,'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];*/
			$result = [];
		}
		else
			$result['error'] = $this->uploadfiles->errors();
		
		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}

	//-----------------------------------------------------------------------------------------------------------------
	// upload logo uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadlogouitzender( $uitzender_id = NULL )
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

			$file_array = $this->uploadfiles->getFileArray();

			//img class aden om plaatje te resizen
			$image = new Img( $file_array );
			$image->setMaxWidthHeight( 700, 400 )->setQuality(80)->resize();

			/*$preview[] = 'http://via.placeholder.com/150';
			$config[] = array('url' => '/test', 'caption' => 'test.jpg', 'key' => 101, 'size' => 100);
			$result = [ 'initialPreview' => $preview,'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];*/
			$result = [];
		}
		else
			$result['error'] = $this->uploadfiles->errors();

		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// AJAX upload for bootstrap file input || OVERBODIG??
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//init uitzender object
		$uitzender = new \models\uitzenders\Inlener( 256 );


		/*
		$this->uploadfiles->setUploadDir( 'files' );
		$this->uploadfiles->setPrefix( 'img_' );
		$this->uploadfiles->uploadfiles();

		//show($_GET);
		//show($_POST);
		//show($_FILES);


		header('Content-Type: application/json'); // set json response headers

		$result['error'] = 'Er gaat wat mis';
		echo json_encode($result);

		die();*/


	}

}
