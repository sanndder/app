<?php

namespace models\file;
use claviska\SimpleImage;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Image class
 *
 * File handlig for image file types
 *
 */
class Img extends File{

	/*
	 * @var info file
	 */
	protected $_file_type = NULL;

	/*
	 * @var ratio
	 */
	protected $_ratio = NULL;

	/*
	 * @var current width
	 */
	protected $_current_width = NULL;

	/*
	 * @var current height
	 */
	protected $_current_height = NULL;

	/*
	 * @var new width
	 */
	protected $_new_width = NULL;

	/*
	 * @var new height
	 */
	protected $_new_height = NULL;

	/*
	 * @var new height
	 */
	protected $_quality = 100;

	/*
	 * @var max width
	 */
	protected $_max_width = NULL;

	/*
	 * @var max height
	 */
	protected $_max_height = NULL;


	/*
	 * @var image object
	 */
	protected $_simpleimage = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param file array
	 * @return $this
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct( $input );

		//laod image lib
		$this->_simpleimage = new SimpleImage();
		$this->_simpleimage->fromFile( $this->_file_path );

		//set typ, jpg gif or png
		$this->_setImageType();

		//set info
		$this->_setInfo();

		return $this;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set max width and height for resize
	 *
	 * @return object
	 */
	public function trimWhiteSpace()
	{
		//absoluut path for windows
		if( ENVIRONMENT == 'development' )
			$open_path = 'C:/xampp/htdocs/app/' . $this->_file_path;
		else
			$open_path = $this->_file_path;
		
		$im = new \Imagick( $open_path );
		
		/* Trim the image. */
		$im->trimImage(0.2);
		
		/* Ouput the image */
		header("Content-Type: image/" . $im->getImageFormat());
		echo $im;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set max width and height for resize
	 *
	 * @return object
	 */
	public function setQuality( int $q )
	{
		$this->_quality = $q;
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set max width and height for resize
	 *
	 * @return object */
	public function setMaxWidthHeight( int $w, int $h )
	{
		$this->_max_width = $w;
		$this->_max_height = $h;

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Resize, keep ratio default
	 *
	 * @return object
	 */
	public function resize( $keep_ratio = true )
	{
		if( $keep_ratio )
			$this->_simpleimage->bestFit( $this->_max_width, $this->_max_height )->toFile( $this->_file_path, NULL, $this->_quality );
		else
			$this->_simpleimage->resize( $this->_max_width, $this->_max_height )->toFile( $this->_file_path, NULL, $this->_quality  );
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * png en gif naar jpg
	 * https://www.invoiceberry.com/blog/convert-png-and-gif-pictures-to-jpeg-pictures-with-php/
	 * @return object
	 */
	public function toJpg( $keep_origional = false )
	{
		if( $this->_file_ext == 'jpg' )
			return $this;
		
		//new file path
		$new_path = str_replace( '.'.$this->_file_ext, '.jpg', $this->_file_path );
		
		if ( $this->_file_ext == 'png' ) $new_pic = imagecreatefrompng($this->_file_path);
		if ( $this->_file_ext == 'gif' ) $new_pic = imagecreatefromgif($this->_file_path);
		
		// Create a new true color image with the same size
		$w = imagesx($new_pic);
		$h = imagesy($new_pic);
		$white = imagecreatetruecolor($w, $h);
		
		// Fill the new image with white background
		$bg = imagecolorallocate($white, 255, 255, 255);
		imagefill($white, 0, 0, $bg);
		
		// Copy original transparent image onto the new image
		imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);
		
		$new_pic = $white;
		
		imagejpeg($new_pic, $new_path);
		imagedestroy($new_pic);
		
		if( !$keep_origional )
		{
			unlink($this->_file_path);
			$this->_file_path = $new_path;
			$this->_file_name = str_replace( '.'.$this->_file_ext, '.jpg', $this->_file_name );
			
			$this->_setImageType();
			$this->_setInfo();
		}
		else
		{
			$img['file_name'] = str_replace( '.'.$this->_file_ext, '.jpg', $this->_file_name );
			$img['file_dir'] = $this->_file_dir;
			return new Img( $img );
		}
		
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set current width height and ratio
	 *
	 * @return void
	 */
	private function _setInfo()
	{
		$this->_current_width = $this->_simpleimage->getWidth();
		$this->_current_height = $this->_simpleimage->getHeight();

		$this->_ratio = $this->_simpleimage->getAspectRatio();
		
		//extensie
		$this->_file_ext = getFileExtension($this->_file_name);
		
		//size
		$this->_file_size = filesize($this->_file_path);

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get the type of image from file name
	 * Only jpg, gif, png
	 *
	 * @return void
	 */
	private function _setImageType()
	{
		$ext = strtolower(substr(strrchr($this->_file_name, '.'), 1));

		if( $ext == 'jpg' ||  $ext == 'jpeg' )
			$this->_file_type = 'jpg';
		if( $ext == 'png' )
			$this->_file_type = 'png';
		if( $ext == 'gif' )
			$this->_file_type = 'gif';

		if( $this->_file_type == NULL )
			$this->_error[] = 'Bestand is geen geldige afbeelding';

	}
}


?>