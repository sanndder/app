<?php

namespace models\file;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfReader;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Pdf class
 *
 * File handlig for pdf file types
 *
 */
class Pdf extends File{
	
	/*
	 * @var object fpdi library
	 */
	protected $_fpdi = NULL;
	
	/*
	 * @var int number of pages
	 */
	protected $_page_count = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 * @param file array
	 * @return object
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct( $input );
		
		//load fpdi
		$this->_fpdi = new Fpdi();
		$this->_setInfo();
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Plaatje van handtekening bijvoegen
	 *
	 * @return bool
	 */
	public function addSignature( $countSignatures = 0 )
	{
		$pageCount = $this->_fpdi->setSourceFile( $this->_file_path );
		
		//handtekening naar temp jpg
		$image_path = $this->signatureToTempJpg();
		
		//bestaande pagina's naar nieuwe pdf
		for($i = 1; $i <=  $pageCount; $i++){
			$tplidx = $this->_fpdi->importPage($i);
			$this->_fpdi->addPage('P');
			$this->_fpdi->useTemplate($tplidx);
		}
		
		// y as verschuiven
		$move = 50 * $countSignatures;
		
		//geen nieuwe pagina als die er al is
		if( $countSignatures == 0 )
			$this->_fpdi->addPage('P');
		
		$this->_fpdi->SetFont('Arial','',15);
		$this->_fpdi->SetMargins(12,8,15);
		
		$this->_fpdi->SetXY(15,14 + $move);
		$this->_fpdi->SetTextColor(0,46,101);
		$this->_fpdi->Cell(100,10,'Ondertekening:');
		
		$this->_fpdi->SetFont('Arial','',11);
		$this->_fpdi->SetTextColor(0,0,0);
		
		$this->_fpdi->SetXY(15,26 + $move);
		$this->_fpdi->Cell(25,10, 'User ID:');
		$this->_fpdi->Cell(50,10, $this->user->user_id);
		
		$this->_fpdi->SetXY(15,32 + $move);
		$this->_fpdi->Cell(25,10, 'Naam:');
		$this->_fpdi->Cell(50,10, $this->user->user_name);
		
		$this->_fpdi->SetXY(15,38 + $move);
		$this->_fpdi->Cell(25,10, 'IP Adres:');
		$this->_fpdi->Cell(50,10, $_SERVER['REMOTE_ADDR']);
		
		$this->_fpdi->SetXY(15,44 + $move);
		$this->_fpdi->Cell(25,10, 'Datum:');
		$this->_fpdi->Cell(50,10, date('d-m-Y \o\m\ H:i:s') );
		
		$this->_fpdi->SetXY(100, 18 + $move);
		$this->_fpdi->Image($image_path, NULL, NULL, NULL, 35 );

		//filename voor ondertekende pdf
		if( $countSignatures == 0 )
		{
			$new_path = str_replace( '.pdf', '_signed.pdf', $this->_file_path );
			$new_name = str_replace( '.pdf', '_signed.pdf', $this->_file_name );
		}
		else
		{
			$new_path = $this->_file_path;
			$new_name = $this->_file_name;
		}
		
		$file_info['signed_file_name'] = $new_name;
		$file_info['signed_file_name_display'] = $this->_file_name_display;
		$file_info['signed_file_dir'] = $this->_file_dir;
		$file_info['signed_file_path'] = $new_path;
		
		$this->_fpdi->Output( $new_path, 'F' );
		
		return $file_info;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Plaatje van handtekening maken
	 *
	 * @return string|bool
	 */
	public function signatureToTempJpg()
	{
		$dir = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/temp/handtekeningen/';

		if( !checkAndCreateDir($dir) )
			die('Upload map bestaat niet en kan niet worden aangemaakt.');
			
		$encoded_image = explode(",", $_POST['imageData'])[1];
		$decoded_image = base64_decode($encoded_image);
		
		$path = $dir . uniqid() . "_" . generateRandomString() . "_signature.jpg";
		
		file_put_contents( $path, $decoded_image );
		
		if( file_exists($path))
			return $path;
		
		return false;
	}


	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set current width height and ratio
	 *
	 * @return void
	 */
	private function _setInfo()
	{
		$this->_file_ext = 'pdf';
		
		//size
		$this->_file_size = filesize( $this->_file_path );
		
		//page count
		try
		{
			$this->_page_count = $this->_fpdi->setSourceFile( $this->_file_path );
		}
		catch ( CrossReferenceException $e )
		{
			if( $e->getCode() == 267 )
				$this->_error[] = 'Uw pdf is gecomprimeerd en deze kunnen wij niet verwerken. Neem contact met ons op.';
			else
				$this->_error[] = 'Uw pdf is kan niet worden verwerkt (code '. $e->getCode() .' . Neem contact met ons op.';
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get page count
	 * @return int
	 */
	public function pageCount()
	{
		return $this->_page_count;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get one page from the origional and make an object
	 *
	 * @return object Pdf
	 */
	public function splitPage( $page = 1, $new_name = NULL ) : Pdf
	{
		//Pagina moet wel bestaan
		if( $this->pageCount() < $page )
			return NULL;
		
		//nieuw PDF object aanmaken
		$new_pdf = new FPDI();
		$new_pdf->AddPage();
		
		//source is oorspronkelijke pdf
		$new_pdf->setSourceFile( $this->_file_path );
		$new_pdf->useTemplate( $new_pdf->importPage($page) );
		
		//nieuwe naam
		if( $new_name === NULL )
		{
			//random naam
			$microtime = str_replace( array(' ','.') , '', microtime());
			$new_name = 'pdf_' . $microtime . '_' . generateRandomString(8) . '.pdf';
		}
		
		$new_path = str_replace( $this->_file_name, $new_name, $this->_file_path);
		$new_pdf->Output($new_path, "F");
		
		//load object
		$pdf['file_name'] = $new_name;
		$pdf['file_dir'] = $this->_file_dir;
		
		return new Pdf( $pdf );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * PDF to JPG
	 * https://stackoverflow.com/questions/8624886/pdf-to-jpg-conversion-using-php
	 * @return object
	 */
	public function toJpg( $save_path = NULL ) : Img
	{
		/*
		$imagick = new \Imagick();
		//$imagick->setResolution(150,150);
		vshow($imagick->readImage( $open_path ));
		$imagick->setImageFormat( 'jpeg' );
		$imagick->writeFile( $save_path );*/
		
		//absoluut path for windows
		if( ENVIRONMENT == 'development' )
			$open_path = 'C:/xampp/htdocs/app/' . $this->_file_path;
		else
			$open_path = $this->_file_path;
		
		//new file
		if( $save_path === NULL )
			$save_path = str_replace( '.pdf', '.jpg', $open_path );
		
		//windows versie
		if( ENVIRONMENT == 'development' )
			exec("magick convert " . $open_path . " " . $save_path);
		//server
		else
		{
			exec("/usr/bin/convert " . $open_path . " " . $save_path);
			/*
			//https://stackoverflow.com/questions/9227014/convert-pdf-to-jpeg-with-php-and-imagemagick
			$imagick = new \Imagick();
			$imagick->setResolution(300,300);
			$imagick->setCompressionQuality(80);
			$imagick->readImage($open_path);
			$imagick->setImageFormat('jpeg');
			$imagick->writeImage($save_path);
			$imagick->clear();
			$imagick->destroy();
			
			echo($open_path);
			echo "\r\n";
			echo($save_path);
			die();
			*/
		}
		
		//JPG is created, return new object
		$jpg['file_dir'] = $this->_file_dir;
		$jpg['file_name'] = str_replace( '.pdf', '.jpg', $this->_file_name );
		
		return new Img( $jpg );
	}
	
}


?>