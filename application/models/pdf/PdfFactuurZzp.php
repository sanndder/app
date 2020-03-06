<?php

namespace models\pdf;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class voor uren verkoop factuur en kostenoverzicht
 *
 *
 */
class PdfFactuurZzp extends PdfFactuur {
	
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
		$config['margin_top'] = 18;
		$config['margin_header'] = 0;
		$config['margin_right'] = 0;
		$config['margin_bottom'] = 45;
		
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
		$this->setRelatieGegevens( $bedrijfsgegevens, array() );
		
		//legen
		$this->smarty->assign( 'iban_factoring', '');
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cessie tekst voor de factuur
	 * @return object
	 */
	public function setBedrijfsgegevens( $bedrijfsgegevens ) :PdfFactuurZzp
	{
		$this->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		return $this;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cessie tekst voor de factuur
	 * @return object
	 */
	public function setGRekening( $bedrag, $percentage ) :PdfFactuurZzp
	{
		$this->smarty->assign( 'grekening_bedrag', $bedrag);
		$this->smarty->assign( 'grekening_percentage', $percentage);
		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cessie tekst voor de factuur
	 * @return object
	 */
	public function setCessieTekst( $cessie_tekst ) :PdfFactuurZzp
	{
		$this->smarty->assign( 'cessie_tekst', $cessie_tekst);
		return $this;
	}
	

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * body voor de factuur
	 * @return object
	 */
	public function setBody( $factuur, $regels ) :PdfFactuurZzp
	{
		$this->smarty->assign( 'factuur', $factuur);
		$this->smarty->assign( 'regels', $regels);
		
		$body = $this->smarty->fetch('application/views/pdf/facturen/factuur_verkoop_uren.tpl');
		$this->mpdf->WriteHTML($body);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * header voor de factuur
	 * @return object
	 */
	public function setHeader() :PdfFactuurZzp
	{
		$header = $this->smarty->fetch('application/views/pdf/facturen/factuur_header.tpl');
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