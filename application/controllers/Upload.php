<?php

use models\Documenten\IDbewijs;
use models\File\File;
use models\File\Img;
use models\File\Pdf;
use models\utils\Carbagecollector;

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
		$this->uploadfiles->setAllowedFileTypes( 'jpg|png' );
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
		$this->uploadfiles->setAllowedFileTypes( 'jpg|png' );
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
	// upload werknemer ID bewijs
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadwerknemerid( $werknemer_id = NULL, $file_id = 1 )
	{
		header('Content-Type: application/json'); // set json response headers
		
		//opschonen
		Carbagecollector::clearTempFolder( 'werknemer/idbewijs' );
		
		//custom uplaod voor werknemer ID bewijs
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setAllowedFileTypes( 'jpg|jpeg|png|pdf|gif' );
		$this->uploadfiles->setUploadDir( 'werknemer/idbewijs' );
		$this->uploadfiles->setPrefix( 'id_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			$file_array = $this->uploadfiles->getFileArray();
			
			//wanneer pdf, dan omzetten naar jpg
			if( $file_array['file_ext'] == 'pdf' )
			{
				//load pdf
				$pdf = new Pdf( $file_array );
				
				//bij meer dan 2 pagina's stoppen
				if( $pdf->pageCount() > 2 )
				{
					$result['error'] = "Het PDF bestand bevat meer dan 1 pagina's. Dit kunnen wij niet automatisch verwerken";
					
					//ALWAYS delete files for AVG
					$pdf->deleteFromDisk();
					echo json_encode($result);
					die();
				}
				elseif( $pdf->pageCount() == 2 )
				{
					$pdf_1 = $pdf->splitPage(1);
					$pdf_2 = $pdf->splitPage(2);
					
					$img[1] = $pdf_1->toJpg();
					$img[2] = $pdf_2->toJpg();
					
					//pdf's weggooien
					$pdf_1->deleteFromDisk();
					$pdf_2->deleteFromDisk();
				}
				else
				{
					//pdf to jpg, 1 bestand is voorkant
					$img[1] = $pdf->toJpg();
				}
				
				//now we have the JPG, delete pdf
				$pdf->deleteFromDisk();
			}

			
			if( $file_array['file_ext'] == 'jpg' || $file_array['file_ext'] == 'png' || $file_array['file_ext'] == 'gif' )
			{
				$img[1] = new Img( $file_array );
				$img[1]->toJpg();
			}
			
			//alles resizen
			foreach( $img as &$image_object )
				$image_object->setMaxWidthHeight( 900, 900 )->setQuality(80)->resize();
			
			//nieuwe ID starten
			$idbewijs = new IDbewijs();
			$idbewijs->werknemer( $werknemer_id )->imgObjectToDatabase( 'voorkant', $img[1] );

			$result['url'] = $idbewijs->url('voorkant');
		}
		else
			$result['error'] = $this->uploadfiles->errors();
		
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
		$this->uploadfiles->setAllowedFileTypes( 'jpg|png' );
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
		$this->uploadfiles->setAllowedFileTypes( 'jpg|png' );
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
