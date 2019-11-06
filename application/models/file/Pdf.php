<?php

namespace models\File;

use setasign\Fpdi\Fpdi;

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
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 * @param file array
	 * @return $this
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct( $input );
		
		//laod fpdi
		$this->_fpdi = new Fpdi();

		$this->_setInfo();
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set current width height and ratio
	 *
	 * @return void
	 */
	private function _setInfo()
	{
		$this->_file_ext = 'pdf';
		
		//size
		$this->_file_size = filesize($this->_file_path);
		
		//page count
		$this->_page_count = $this->_fpdi->setSourceFile($this->_file_path);
		
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get page count
	 * @return int
	 */
	public function pageCount()
	{
		return $this->_page_count;
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get one page from the origional and make an object
	 *
	 * @return object|void
	 */
	public function splitPage( $page = 1, $new_name = NULL )
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
		
		
		show($this->_file_path);
		show($new_path);
		
		$new_pdf->Output($new_path, "F");
		
		//load object
		$pdf['file_name'] = $new_name;
		$pdf['file_dir'] = $this->_file_dir;
		
		return new Pdf( $pdf );
	}
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * PDF to JPG
	 * https://stackoverflow.com/questions/8624886/pdf-to-jpg-conversion-using-php
	 * @return int
	 */
	public function toJpg( $save_path = NULL )
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
		exec("magick convert " . $open_path . " " . $save_path);
		
		//JPG is created, return new object
		$jpg['file_dir'] = $this->_file_dir;
		$jpg['file_name'] = str_replace( '.pdf', '.jpg', $this->_file_name );
		
		return new Img( $jpg );
	}


	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get the type of image from file name
	 * Only jpg, gif, png
	 *
	 * @return void
	 */
	private function _setImageType()
	{

	}
}


?>