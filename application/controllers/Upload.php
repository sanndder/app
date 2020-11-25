<?php

use models\boekhouding\TransactieBestandenFactory;
use models\documenten\Document;
use models\documenten\IDbewijs;
use models\file\File;
use models\file\Img;
use models\file\Pdf;
use models\utils\Carbagecollector;
use models\verloning\LoonstrokenZip;
use models\verloning\ReserveringenExcel;

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
	// upload ondertekend document
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadondertekening( $document_id = NULL )
	{
		$document = new Document( $document_id );
		$details = $document->details();
		
		if( $details === NULL )
			$result['error'][] = 'Document niet gevonden';
		else
		{
			
			$this->load->model( 'upload_model', 'uploadfiles' );
			$this->uploadfiles->setUploadDir( $details['file_dir'] );
			$this->uploadfiles->setAllowedFileTypes( 'pdf|PDF' );
			$this->uploadfiles->setPrefix();
			$this->uploadfiles->uploadfiles();
			
			if( $this->uploadfiles->errors() === false )
			{
			
				$document->uploadSignedFile( $this->uploadfiles->getFileArray() );
				
				$result = [];
				
			} else
				$result['error'] = $this->uploadfiles->errors();
			
			header( 'Content-Type: application/json' ); // set json response headers
			echo json_encode( $result );
			die();
		}
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload excel met stand reserveringen
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadreserveringen()
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'verloning/reserveringen' );
		$this->uploadfiles->setAllowedFileTypes( 'xls|XLS|xlsx|XLSX' );
		$this->uploadfiles->setPrefix( 'xls_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//get file path
			$path = $this->uploadfiles->getFilePath();
			
			$reserveringenExcel = new ReserveringenExcel( $path );
			$reserveringenExcel->setType( $_POST['type'] );
			$reserveringenExcel->extractData();
			$result = [];
			
		}
		else
			$result['error'] = $this->uploadfiles->errors();
		
		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload bestand bij factuur
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadfactuurbijlage( $factuur_id )
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'facturen/bijlages' );
		$this->uploadfiles->setAllowedFileTypes( 'pdf|PDF|jpg|JPG|jpeg|JPEG|png|PNG' );
		$this->uploadfiles->setPrefix( 'bijlage_' );
		$this->uploadfiles->setDatabaseTable( 'factuur_bijlages' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			$factuur = new \models\facturatie\Factuur( $factuur_id );
			
			$file_array = $this->uploadfiles->getFileArray();
			if( $factuur->addBijlage( $file_array) )
			{
				$result['status'] = 'success';
			}
			else
			{
				$result['status'] = 'error';
				$result['error'] = $this->invoer->errors();
			}
		}
		else
		{
			$result['status'] = 'error';
			$result['error'] = $this->uploadfiles->errors();
		}
		
		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}




	//-----------------------------------------------------------------------------------------------------------------
	// factorings pdf's
	//-----------------------------------------------------------------------------------------------------------------
	public function factoringsbestanden()
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'factoring/facturen' );
		$this->uploadfiles->setAllowedFileTypes( 'pdf|PDF' );
		$this->uploadfiles->setDatabaseTable( 'factoring_facturen' );
		$this->uploadfiles->setPrefix( 'factris_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$file_id = $this->uploadfiles->dataToDatabase();
			
			$file_array = $this->uploadfiles->getFileArray();
			
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
	// factorings pdf's
	//-----------------------------------------------------------------------------------------------------------------
	public function bankbestanden()
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'bank/transacties' );
		$this->uploadfiles->setAllowedFileTypes( 'xml|XML' );
		$this->uploadfiles->setDatabaseTable( 'bank_transactiebestanden' );
		$this->uploadfiles->setPrefix( 'bank_' );
		$this->uploadfiles->setcheckUnique( true );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$file_id = $this->uploadfiles->dataToDatabase();
			if( $file_id === false )
			{
				$result['error'] = current($this->uploadfiles->errors());
				header( 'Content-Type: application/json' ); // set json response headers
				echo json_encode( $result );
				die();
			}
			
			$file_array = $this->uploadfiles->getFileArray();
			
			//zip uitlezen
			if( $file_id > 0 )
			{
				$transactiebestandFactory = new TransactieBestandenFactory( $file_id );
				$transactiebestand = $transactiebestandFactory->getBestandByBankType();
				
				$transactiebestand->loadTransacties();
			}
			
			$result = [];
		}
		else
			$result['error'] = $this->uploadfiles->errors();
		
		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload zip met loonstroken
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadloonstroken()
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'loonstroken/zip' );
		$this->uploadfiles->setAllowedFileTypes( 'zip|ZIP' );
		$this->uploadfiles->setDatabaseTable( 'loonstroken_zip' );
		$this->uploadfiles->setPrefix( 'zip_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$zip_id = $this->uploadfiles->dataToDatabase();
			
			//zip uitlezen
			if( $zip_id > 0 )
			{
				$zip = new LoonstrokenZip( $zip_id );
				$zip->updateZipInfo();
			}
			
			
			$file_array = $this->uploadfiles->getFileArray();
			
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
				if( $pdf->errors() !== false )
				{
					$errors = $pdf->errors();
					$result['error'] = $errors[0];
					
					//ALWAYS delete files for AVG
					$pdf->deleteFromDisk();
					echo json_encode($result);
					die();
				}
				
				//bij meer dan 2 pagina's stoppen
				if( $pdf->pageCount() > 2 )
				{
					$result['error'] = "Het PDF bestand bevat meer dan 2 pagina's. Dit kunnen wij niet automatisch verwerken";
					
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

			//png en gif ook naar jpg, altijd alleen [1] want vanaf pdf wordt het al jpg
			if( $file_array['file_ext'] == 'jpg' || $file_array['file_ext'] == 'png' || $file_array['file_ext'] == 'gif' )
			{
				$img[1] = new Img( $file_array );
				if(  $file_array['file_ext'] != 'jpg' )
					$img[1]->toJpg();
			}
			
			//alles resizen
			foreach( $img as &$image_object )
				$image_object->setMaxWidthHeight( 900, 900 )->setQuality(80)->resize();
			
			//nieuwe ID starten
			$idbewijs = new IDbewijs();
			
			//slechts 1 file
			if( count($img) == 1)
			{
				if( $file_id == 1 )
					$idbewijs->werknemer( $werknemer_id )->imgObjectToDatabase( 'voorkant', $img[1] );
				else
					$idbewijs->werknemer( $werknemer_id )->imgObjectToDatabase( 'achterkant', $img[1] );
			}
			
			//2 bestanden
			if( count($img) == 2 )
			{
				$idbewijs->werknemer( $werknemer_id )->imgObjectToDatabase( 'voorkant', $img[1] )->imgObjectToDatabase( 'achterkant', $img[2] );
			}

			$result['url'] = $idbewijs->url('voorkant');
		}
		else
			$result['error'] = $this->uploadfiles->errors();
		
		echo json_encode($result);
		die();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload uittreksel kvk
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadkvk( $zzp_id = NULL )
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'zzp/uittrekselkvk' );
		$this->uploadfiles->setAllowedFileTypes( 'jpg|pdf|JPG|PDF' );
		$this->uploadfiles->setDatabaseTable( 'zzp_kvk_inschrijving' );
		$this->uploadfiles->setFieldId( 'zzp_id', $zzp_id );
		$this->uploadfiles->setPrefix( 'kvk_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$this->uploadfiles->dataToDatabase( true );
			
			$file_array = $this->uploadfiles->getFileArray();
			
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
	// upload bestand met BSN van ET werknemer
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadbsnet( $werknemer_id = NULL )
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'werknemer/et/bsn' );
		$this->uploadfiles->setAllowedFileTypes( 'jpg|pdf|JPG|PDF' );
		$this->uploadfiles->setDatabaseTable( 'werknemer_et_bsn' );
		$this->uploadfiles->setFieldId( 'werknemer_id', $werknemer_id );
		$this->uploadfiles->setPrefix( 'bsn_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			//save to database
			$this->uploadfiles->dataToDatabase( true );
			
			$file_array = $this->uploadfiles->getFileArray();
			
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
		show($_POST);
		
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
