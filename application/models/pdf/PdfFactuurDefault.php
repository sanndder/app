<?php

namespace models\pdf;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfFactuurDefault extends PdfBuilder {

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param array
	 * @return $this
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

		$config['format'] = 'P';
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
	 * header voor de factuur
	 * @return object
	 */
	public function setBody()
	{
		$body = $this->smarty->fetch('application/views/pdf/facturen/factuur_default.tpl');
		$this->mpdf->WriteHTML($body);
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * header voor de factuur
	 * @return object
	 */
	public function setHeader()
	{
		$header = $this->smarty->fetch('application/views/pdf/facturen/factuur_header.tpl');
		$this->mpdf->SetHTMLHeader($header);
		return $this;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer voor de factuur
	 * @return object
	 */
	public function setFooter()
	{
		$footer = $this->smarty->fetch('application/views/pdf/footers/footer_full.tpl');
		$this->mpdf->SetHTMLFooter($footer);
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