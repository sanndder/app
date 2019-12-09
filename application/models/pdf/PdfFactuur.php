<?php

namespace models\pdf;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfFactuur extends PdfBuilder {

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
		$config['margin_top'] = 0;
		$config['margin_header'] = 0;
		$config['margin_right'] = 0;
		$config['margin_bottom'] = 0;

		$config['format'] = 'L';
		//$param['default_font'] = 'arial';

		parent::__construct($config);

		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/facturen/facturen.css');
		$this->mpdf->WriteHTML($stylesheet, 1);

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
		//$this->mpdf->SetHTMLHeader($header);

		$body = $this->smarty->fetch('application/views/pdf/facturen/factuur_verkoop.tpl');

		$this->mpdf->WriteHTML($body);

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer voor de factuur
	 * @return object
	 */
	public function setFooter()
	{
		//header margin moet ook op 0
		$this->mpdf->margin_footer = 0;

		$footer = $this->smarty->fetch('application/views/pdf/facturen/factuur_footer.tpl');
		$this->mpdf->SetHTMLFooter($footer);

		$this->mpdf->WriteHTML('');
		$this->mpdf->AddPage();
		$this->mpdf->WriteHTML('');

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