<?php

namespace models\File;

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

		$this->_setImageType();

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