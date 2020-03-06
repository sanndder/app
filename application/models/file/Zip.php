<?php

namespace models\file;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Zip class
 *
 * File handlig for zip files
 *
 */
class Zip extends File{

	/*
	 * @var info file
	 */
	protected $_zip = NULL;
	protected $_res = NULL;

	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Init new file from array
	 *
	 *
	 * @param file array
	 * @return $this
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct( $input );
		
		if( $input != NULL && file_exists($this->_file_path) )
			$this->loadZip();
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * 1 bestand er uit halen
	 *
	 */
	public function extract( $dir )
	{
		if( $this->_res === true )
		$this->_zip->extractTo( $dir );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * zip laden
	 *
	 */
	public function loadZip()
	{
		$this->_zip = new \ZipArchive();
		$this->_res = $this->_zip->open( $this->_file_path);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bestand verwijderen
	 *
	 */
	public function deleteFile( $file )
	{
		$this->_zip->deleteName($file);
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * aantal bestanden
	 * @return int
	 */
	public function numFiles()
	{
		//relaod for update
		$this->loadZip();
		return $this->_zip->numFiles;
	}


}


?>