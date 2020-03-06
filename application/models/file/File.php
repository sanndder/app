<?php

namespace models\file;
use models\Connector;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * File class
 *
 * File handlig for all file types
 *
 */
class File extends Connector {

	/*
	 * @var string file info
	 */
	protected $_file_name = NULL; //file name on disk
	protected $_file_name_display = NULL; //file name to show when download
	protected $_file_dir = NULL; //dir
	protected $_file_path = NULL; // full path ( dir + file_name)
	protected $_file_ext = NULL;
	protected $_file_size = NULL;
	protected $_file_table = NULL; //table where file is stored
	protected $_file_id = NULL; //id in table
	protected $_file_id_field = NULL; //id fild in table

	/*
	 * @var array
	 */
	protected $_error = NULL;



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param array
	 * @return object
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct();
		//if array, get info from array fields
		if( $input !== NULL && is_array($input) )
			$this->_getFileFromArray( $input );
		elseif( $input !== NULL && is_object($input) )
			$this->_getFileFromObject( $input );
		elseif( $input !== NULL && is_string ($input) )
			$this->_getFileFromString( $input );
		else
			$this->_error[] = 'Ongeldige input';

		//afbreken als er nu al errors zijn
		if( $this->_error !== NULL )
			return $this;

		//check if file exists
		if( !file_exists($this->_file_path) )
			$this->_error[] = 'Bestand niet gevonden op de server';

		if( is_dir($this->_file_path) )
			$this->_error[] = 'Path is een map, geen bestand';
		
		//stop
		if( $this->_error !== NULL )
			return $this;
		
		//set file info
		$this->_setInfo();

		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set info
	 * @return void
	 */
	private function _setInfo()
	{
		//extensie
		$this->_file_ext = getFileExtension($this->_file_name);
		
		//size
		$this->_file_size = filesize($this->_file_path);
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * String to info to vars
	 * @return void
	 */
	private function _getFileFromString( $input )
	{
		//split for dir and name
		$parts = explode('/', $input );

		if( count($parts) > 1 )
		{
			$this->_file_name = end($parts);
			$this->_file_dir = str_replace( $this->_file_name, '', $input );

			$this->_file_path = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $this->_file_dir . $this->_file_name;
		}
		else
		{
			$this->_error[] = 'Ongeldige input bestand (string)';
		}

		//display name instellen
		$this->_file_name_display = $this->_file_name;

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Object to vars
	 * @return void
	 */
	private function _getFileFromObject( $input )
	{
		$array['file_name'] = $input->name();
		$array['file_dir'] = $input->dir();
		
		$this->_getFileFromArray( $array );
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Array info to vars
	 * @return void
	 */
	private function _getFileFromArray( $input )
	{
		//check for file_name
		if( isset($input['file_name']) )
			$this->_file_name = $input['file_name'];
		else
			$this->_error[] = 'Geen bestandsnaam opgegeven';

		//check for dir
		if( isset($input['file_dir']) )
		{
			$this->_file_dir = $input['file_dir'];

			//remove trailing slahs if there is one
			if(substr($this->_file_dir, -1) == '/')
				$this->_file_dir = substr($this->_file_dir, 0, -1);

		}
		else
			$this->_error[] = 'Geen bestandsmap opgegeven';

		//set full path
		if( $this->_file_name !== NULL && $this->_file_dir !== NULL )
			$this->_file_path = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $this->_file_dir . '/' . $this->_file_name;

		//if display name is not set, take file_name
		if( isset($input['file_name_display']) )
			$this->_file_name_display = $input['file_name_display'];
		else
			$this->_file_name_display = $input['file_name'];

		//set id if available
		if( isset($input['id_field']) )
		{
			if( isset($input[$input['id_field']]) )
			{
				$this->_file_id = $input[$input['id_field']];
				$this->_file_id_field = $input['id_field'];
			}
		}

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * extension
	 * @return string
	 */
	public function ext()
	{
		return $this->_file_ext;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * file path
	 * @return string
	 */
	public function path()
	{
		return $this->_file_path;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * file path
	 * @return string
	 */
	public function dir()
	{
		return $this->_file_dir;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * file name
	 * @return string
	 */
	public function name()
	{
		return $this->_file_name;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Force download
	 * @return void
	 */
	public function download()
	{
		$this->_download('attachment');
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * unlink file
	 * @return void
	 */
	public function deleteFromDisk()
	{
		if( file_exists($this->_file_path) && !is_dir($this->_file_path) )
			unlink($this->_file_path);
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Force download
	 * @return void
	 */
	public function inline()
	{
		$this->_download('inline');
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Force download
	 * @return void
	 */
	private function _download( $method = 'attachment' )
	{
		//debug prevent readfile
		if( isset($_GET['debug']) )
		{
			show( 'Method: ' . $method);
			show( 'Path: ' . $this->_file_path);
			show( 'File exists: ' );
			show(var_dump(file_exists($this->_file_path)));
			die();
		}

		//check for file
		if( !file_exists($this->_file_path) || is_dir($this->_file_path) )
			die('Bestand kan niet worden gevonden');

		//try to get mime type
		$mime_type = mime_content_type($this->_file_path);
		if( $mime_type === false )
			$mime_type = get_mime_by_extension($this->_file_name);

		//still false, then abort
		if( $mime_type === false )
			die('Mime type unknown, cloud not download file');

		//downloaden
		header("Content-disposition: $method; filename=\"$this->_file_name_display\"");
		header("Content-type:" . $mime_type);

		//prevent caching
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		readfile($this->_file_path);
		die();
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|bool
	 */
	public function errors()
	{
		//output for debug
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}
}


?>