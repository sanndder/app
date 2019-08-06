<?php

namespace models\File;

use claviska\SimpleImage;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * File class
 *
 * File handlig for all file types
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


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
		require_once('application/third_party/vendor/autoload.php');
		$this->_simpleimage = new SimpleImage();
		$this->_simpleimage->fromFile( $this->_file_path );

		//set typ, jpg gif or png
		$this->_setImageType();

		//set info
		$this->_setInfo();

	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set max width and height for resize
	 *
	 * @return object
	 */
	public function setMaxWidthHeight( int $w, int $h )
	{
		$this->_max_width = $w;
		$this->_max_height = $h;

		return $this;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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