<?php

namespace models\pdf;

use models\utils\Tijdvak;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfFactuur extends PdfBuilder {
	
	protected $_factuurdatum = NULL;
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
			$config['margin_top'] = 0;
			$config['margin_header'] = 0;
			$config['margin_right'] = 0;
			$config['margin_bottom'] = 0;
			$config['format'] = 'L';
			$config['titel'] = 'Factuur';
		}

		parent::__construct($config);

		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/facturen.css');
		$this->mpdf->WriteHTML($stylesheet, 1);
		
		//default date er in
		$this->setFactuurdatum();

		return $this;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * relatie gegevens voor de factuur
	 *
	 */
	public function setRelatieGegevens( $bedrijfsgegevens, $factuurgegevens )
	{
		$this->smarty->assign( 'relatie_gegevens', $bedrijfsgegevens );
		$this->smarty->assign( 'relatie_factuurgegevens', $factuurgegevens );
		
		return $this;
	}
	


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * datum instellen
	 *
	 */
	public function setFactuurdatum( $datum = NULL ) :PdfFactuur
	{
		if( $datum === NULL )
			$this->_factuurdatum = date('Y-m-d');
		else
			$this->_factuurdatum = $datum;
		
		$this->smarty->assign( 'factuurdatum', $this->_factuurdatum );
		
		return $this;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer voor de factuur
	 *
	 */
	public function setFooter() :PdfFactuur
	{
		$footer = $this->smarty->fetch('application/views/pdf/footers/footer_full.tpl');
		$this->mpdf->SetHTMLFooter($footer);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Tijdvak info instellen
	 * TODO: controle op periodes
	 * @return void
	 */
	public function setTijdvak( $data )
	{
		if( isset($data['tijdvak']) ) $this->_tijdvak = $data['tijdvak'];
		if( isset($data['periode']) ) $this->_periode = intval($data['periode']);
		if( isset($data['jaar']) ) $this->_jaar = intval($data['jaar']);
		
		$tijdvak = new Tijdvak( $this->_tijdvak, $this->_jaar, $this->_periode  );
		
		$this->_periode_start = $tijdvak->startDatum();
		$this->_periode_einde = $tijdvak->eindDatum();
		$this->_periode_dagen = $tijdvak->dagen();
		
		$this->smarty->assign( 'jaar', $this->_jaar );
		$this->smarty->assign( 'periode', $this->_periode );
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