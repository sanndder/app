<?php

namespace models\pdf;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class voor uren verkoop factuur en kostenoverzicht
 *
 *
 */
class PdfFactuurVerkoopUren extends PdfFactuur {

	//verkoop of kostenoverzicht
	private $_type = 'verkoop';
	private $_factuur_nr = '[CONCEPT]';
	
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
	 * welk type
	 * @return object
	 */
	public function setType( $type = 'verkoop' ) :PdfFactuurVerkoopUren
	{
		$this->_type = $type;
		$this->smarty->assign( 'type', $type );
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factuur nr
	 * @return object
	 */
	public function setFactuurNr( $nr ) :PdfFactuurVerkoopUren
	{
		$this->_factuur_nr = $nr;
		$this->smarty->assign( 'factuur_nr', $nr );
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * body voor de factuur
	 * @return object
	 */
	public function setBody( $factuur, $regels ) :PdfFactuurVerkoopUren
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
	public function setHeader() :PdfFactuurVerkoopUren
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