<?php

namespace models\pdf;

use models\utils\Codering;
use models\utils\Tijdvak;
use models\werknemers\Werknemer;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfAanmelding extends PdfBuilder {

	
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
			$config['titel'] = 'Aanmelding';
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
	public function setBody( Werknemer $werknemer ) :PdfAanmelding
	{
		$this->smarty->assign('werknemer', $werknemer->gegevens() );

		$akkoord = $werknemer->akkoordLoonheffing();
		if( $akkoord === NULL  )
			$akkoord = $werknemer->startDienstverband() . ' ' . rand( 10, 18) . ':' . rand( 10, 59);

		$this->smarty->assign('indienst', $akkoord );
		$this->smarty->assign('landen',  Codering::listLanden() );
		
		
		$body = $this->smarty->fetch('application/views/pdf/aanmelding/body.tpl');
		$this->mpdf->WriteHTML($body);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer voor de urenbriefje
	 *
	 */
	public function setFooter() :PdfAanmelding
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
	public function setHeader() :PdfAanmelding
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