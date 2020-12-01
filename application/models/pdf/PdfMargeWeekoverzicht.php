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
class PdfMargeWeekoverzicht extends PdfBuilder {
	
	protected $_data = NULL;
	
	
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
			$config['margin_bottom'] = 25;
			
			$config['margin_header'] = 0;
			$config['margin_footer'] = 0;
			
			$config['format'] = 'L';
			$config['titel'] = 'Marge Weekoverzicht';
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
	public function setBody( $data ) :PdfMargeWeekoverzicht
	{
		$this->smarty->assign('data', $data );

		$body = $this->smarty->fetch('application/views/pdf/margeoverzicht/weekoverzicht.tpl');
		$this->mpdf->WriteHTML($body);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer voor de urenbriefje
	 *
	 */
	public function setFooter() :PdfMargeWeekoverzicht
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
	public function setHeader( $tijdvak, $jaar, $periode ) :PdfMargeWeekoverzicht
	{
		$titel = $jaar . ' - ';
		
		if( $tijdvak == 'w' ) $titel .= 'week ' . $periode;
		if( $tijdvak == '4w' ) $titel .= 'periode ' . $periode;
		if( $tijdvak == 'm' ) $titel .= getMaandNaam($periode);
		
		$this->smarty->assign('titel', $titel );
		
		$header = $this->smarty->fetch('application/views/pdf/margeoverzicht/header.tpl');
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