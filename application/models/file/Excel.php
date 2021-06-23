<?php

namespace models\file;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Excel class
 *
 *
 */
class Excel {
	
	/*
	 * @var object PHP Excel
	 */
	private $_reader = NULL;
	private $_spreadsheet = NULL;
	
	/*
	 *  internal pointers
	 */
	private $_current_row = 1;
	private $_current_col = 'A';
	
	/*
	* path voor bestaand excel bestand
	*/
	private $_file_path = NULL;
	
	/* voor inline download */
	private $_download_name = NULL;
	
	/*
	* error
	*/
	private $_error = NULL;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Constructor heeft voor lezen path nodig
	 *
	 */
	public function __construct( $path = NULL )
	{
		if( $path !== NULL )
			$this->file( $path );
		else
			$this->new();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set file path
	 *
	 */
	public function file( $path ) :Excel
	{
		$this->_file_path = $path;
		
		//check
		if( !is_file($this->_file_path) )
			$this->_error[] = 'Bestand niet gevonden';
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * excel file uitlezen
	 *
	 */
	public function read() :Excel
	{
		$inputFileType = IOFactory::identify($this->_file_path);
		$this->_reader = IOFactory::createReader($inputFileType);
		$this->_spreadsheet = $this->_reader->load($this->_file_path);
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * volledige spreadsheat naar array
	 *
	 */
	public function array() :array
	{
		return $this->_spreadsheet->getActiveSheet()->toArray(null, true, true, true);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * lege spreadsheet
	 *
	 */
	public function new() :Excel
	{
		$this->_spreadsheet = new Spreadsheet();
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Naam voor download
	 *
	 */
	public function setFileName( $name ) :Excel
	{
		$this->_download_name = $name;
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * array als row naar excel
	 *
	 */
	public function writeRow( Array $array ) :Excel
	{
		
		foreach( $array as $value )
		{
			$cell = $this->_current_col . $this->_current_row;
			$this->_spreadsheet->getActiveSheet()->setCellValue( $cell, $value );
			$this->_current_col++;
		}
		
		$this->_current_col = 'A';
		$this->_current_row++;
		
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 *  kolum auto width
	 *
	 */
	public function setAutoWidth() :Excel
	{
		$columns = ['A','B','C','D','E','F','G','H','I','J','K'];
		
		foreach( $columns as $columnID )
			$this->_spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		
		return $this;
	}
	

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * download zonder save
	 *
	 */
	public function inline( $name = 'excel' )
	{
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
		header('Cache-Control: max-age=0');
		
		$writer = new Xlsx( $this->_spreadsheet );
		$writer->save( 'php://output' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * download zonder save
	 *
	 */
	public function save( $path )
	{
		$writer = new Xlsx( $this->_spreadsheet );
		$writer->save( $path );
		
		if(file_exists($path))
			return true;
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_error === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}
		
		if ($this->_error === NULL)
			return false;
		
		return $this->_error;
	}
}


?>