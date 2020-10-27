<?php

namespace models\facturatie;

use models\Connector;
use models\file\Excel;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactoringExport extends Connector
{
	protected $_facturen = NULL;
	protected $_export_id = NULL;
	protected $_header = array();
	protected $_body = array();
	protected $_excel = NULL;
	protected $_dir = NULL;
	protected $_path = NULL;
	protected $_file_name = NULL;
	protected $_error = NULL;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//start new excel
		$this->_excel = new Excel();
		
		$this->_dir = UPLOAD_DIR . '/werkgever_dir_' . $this->user->werkgever_id . '/factoring/export';
		
		if( !checkAndCreateDir( $this->_dir ) )
			die( 'Map kon niet worden aangemaakt' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * excel write header
	 *
	 */
	public function writeHeader()
	{
		$this->_excel->writeRow( $this->_header );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * excel write body
	 *
	 */
	public function writeBody()
	{
		foreach( $this->_body as $row )
			$this->_excel->writeRow( $row );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen die geslecteerd zijn
	 *
	 */
	public function setFacturen( $facturen ) :FactoringExport
	{
		$this->_facturen = $facturen;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * return file ID
	 *
	 */
	public function exportID() :?int
	{
		return $this->_export_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * save file and info to database
	 *
	 */
	public function saveToDatabase() :bool
	{
		$insert['file_dir'] = $this->_dir;
		$insert['file_name'] = $this->_file_name;
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'factoring_export', $insert );
		
		$this->_export_id = $this->db_user->insert_id();
		
		if( $this->_export_id > 0 )
		{
			foreach( $this->_facturen as $factuur )
			{
				$row['file_id'] = $this->_export_id;
				$row['factuur_id'] = $factuur['verkoop']['factuur_id'];
				
				$insert_batch[] = $row;
			}
			
			$this->db_user->insert_batch( 'factoring_export_facturen', $insert_batch );
			
			return true;
		}
		
		return false;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
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