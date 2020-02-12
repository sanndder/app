<?php

namespace models\pdf;

use models\verloning\Invoer;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfFactuurUren extends PdfFactuur {

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param array
	 * @return
	 */
	public function __construct()
	{
		$config['margin_left'] = 0;
		$config['margin_top'] = 23;
		$config['margin_header'] = 0;
		$config['margin_right'] = 0;
		$config['margin_bottom'] = 0;
		
		$config['margin_header'] = 0;
		$config['margin_footer'] = 0;

		$config['format'] = 'L';
		$config['titel'] = 'Factuur';
		
		parent::__construct($config);
		
		$this->mpdf->AliasNbPages('[pagetotal]');
		
		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/facturen.css');
		$this->mpdf->WriteHTML($stylesheet, 1);
		
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		$this->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array| boolean
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