<?php

namespace models\pdf;

use models\utils\Tijdvak;
use models\werknemers\Werknemer;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfUrenbriefje extends PdfBuilder {
	
	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param array
	 * @return $this
	 */
	public function __construct( $config = NULL )
	{
		if( $config === NULL )
		{
			$config['margin_left'] = 0;
			$config['margin_top'] = 18;
			$config['margin_header'] = 0;
			$config['margin_right'] = 0;
			$config['margin_bottom'] = 45;
			
			$config['margin_header'] = 0;
			$config['margin_footer'] = 0;
			
			$config['format'] = 'P';
			$config['titel'] = 'Urenbriefje';
		}

		parent::__construct($config);

		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/default.css');
		$this->mpdf->WriteHTML($stylesheet, 1);
		
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		$this->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * body voor urenbriefje
	 * @return object
	 */
	public function setBody( $titel, Werknemer $werknemer, array $data ) :PdfUrenbriefje
	{
		$this->smarty->assign('titel', $titel);
		$this->smarty->assign('werknemer', $werknemer->gegevens() );
		$this->smarty->assign('data', $data );

		
		$body = $this->smarty->fetch('application/views/pdf/urenbriefje/body.tpl');
		$this->mpdf->WriteHTML($body);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer voor de urenbriefje
	 *
	 */
	public function setFooter() :PdfUrenbriefje
	{
		$footer = $this->smarty->fetch('application/views/pdf/footers/footer_full.tpl');
		$this->mpdf->SetHTMLFooter($footer);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * header voor de urenbriefje
	 * @return object
	 */
	public function setHeader() :PdfUrenbriefje
	{
		$header = $this->smarty->fetch('application/views/pdf/urenbriefje/header.tpl');
		$this->mpdf->SetHTMLHeader($header);
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