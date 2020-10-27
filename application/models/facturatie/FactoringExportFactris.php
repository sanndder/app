<?php

namespace models\facturatie;

use models\Connector;
use models\forms\Valid;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactoringExportFactris extends FactoringExport
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct( $facturen )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		$this->setFacturen($facturen);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * export to excel
	 *
	 */
	public function exportExcel()
	{
		$this->_file_name = 'export_factris_' . date('YmdHis') . '.xlsx';
		$this->_path = $this->_dir . '/' . $this->_file_name;
		
		$this->setHeader();
		$this->writeHeader();
		$this->setBody();
		$this->writeBody();
		
		$this->_excel->setAutoWidth();
		
		if( !$this->_excel->save( $this->_path ) )
		{
			$this->_error['status'] = 'error';
			$this->_error['error'] = 'Bestand kon niet worden gegenereerd';
			return false;
		}
		
		//naar database
		if( !$this->saveToDatabase() )
		{
			$this->_error['status'] = 'error';
			$this->_error['error'] = 'Fout bij schrijven naar database';
			return false;
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * header van export
	 *
	 */
	public function setHeader()
	{
		$this->_header = array( 'Invoice number', 'Invoice Amount',	'G-amount (if applicable)',	'Issue date (DD-MM-YYYY)',	'Due date (DD-MM-YYYY)', 'CoC number','VAT number',	'Name', 'Payment term (days)');
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * body van export
	 *
	 */
	public function setBody()
	{
		if($this->_facturen === NULL || !is_array($this->_facturen) || count($this->_facturen) == 0)
			die('Geen facturen voor export');
		
		foreach( $this->_facturen as $factuur )
		{
			$row[1] = $factuur['verkoop']['factuur_nr'];
			$row[2] = $factuur['verkoop']['bedrag_incl'];
			
			if( $factuur['verkoop']['bedrag_grekening'] > 0 )
				$row[3] = $factuur['verkoop']['bedrag_grekening'];
			else
				$row[3] = '';
			
			$row[4] = reverseDate($factuur['verkoop']['factuur_datum']);
			$row[5] = reverseDate($factuur['verkoop']['verval_datum']);
			$row[6] = $factuur['verkoop']['kvknr'];
			$row[7] = $factuur['verkoop']['btwnr'];
			$row[8] = $factuur['verkoop']['bedrijfsnaam'];
			$row[9] = $factuur['verkoop']['betaaltermijn'];
			
			$this->_body[] = $row;
		}
	}
	
}

?>