<?php

namespace models\Documenten;
use models\pdf\PdfBuilder;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Documenten maken
 *
 */
class DocumentAlgemeneVoorwaarden extends Document implements DocumentInterface {

		/*
	 * @var array
	 */
	protected $_error = NULL;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @return object
	 */
	public function __construct()
	{
		parent::__construct();
		
		$config['margin_left'] = 10;
		$config['margin_top'] = 30;
		$config['margin_header'] = 10;
		$config['margin_right'] = 15;
		$config['margin_bottom'] = 15;
		
		$config['format'] = 'P';
		//$param['default_font'] = 'arial';
		
		//start mPDF library
		$this->pdf = new PdfBuilder( $config );
		
		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/algemenevoorwaarden.css');
		$this->pdf->mpdf->WriteHTML($stylesheet, 1);
		
		//juiste map en tabel
		$this->setPDFInfo();
		
		//algemene voorwaarden mag gelijk opbouwen
		$this->build();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * opbouwen
	 *
	 * @return object
	 */
	public function build()
	{
		$this->setHeader();
		$this->setFooter();
		$this->setBody();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * PDF file instellen
	 *
	 * @return void
	 */
	public function setPDFInfo()
	{
		$this->pdf->setFileDir('werkgever/algemenevoorwaarden');
		$this->pdf->setFileName('algemenevoorwaarden_' . uniqid() . '.pdf' );
		$this->pdf->setFileDisplayName( 'algemenevoorwaarden.pdf' );
		$this->pdf->setTable( 'werkgever_av' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * header instellen
	 *
	 * @return object
	 */
	public function setHeader()
	{
		$CI = &get_instance();// Grab the super object
		$logo = $CI->werkgever->logo();
		
		$this->pdf->smarty->assign('logo', $logo);
		
		$header = $this->pdf->smarty->fetch('application/views/pdf/headers/header_simple.tpl');
		$this->pdf->mpdf->SetHTMLHeader($header);
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * footer instellen
	 *
	 * @return object
	 */
	public function setFooter()
	{
		
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * htm voor body ophalen
	 *
	 * @return object
	 */
	public function setHTML()
	{
		$CI = &get_instance();// Grab the super object
		
		$this->_html .= '<span style="font-size: 20px;">Algemene voorwaarden ' . $this->_werkgever_info['bedrijfsnaam'] . '</span><br /><br />';
		$this->_html .= $CI->werkgever->AVhtml();
		
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * body instellen
	 *
	 * @return object
	 */
	public function setBody()
	{
		$this->setHTML();
		
		$this->pdf->mpdf->WriteHTML( $this->_html );
		
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * pdf maken
	 *
	 * Geeft PDF object terug
	 * @return object|boolean
	 */
	public function pdf()
	{
		if( $this->pdf->generate() !== false )
		{
			//opslaan bij algemene voorwaarden
			$update['file_name'] = $this->pdf->getFileName();
			$update['file_dir'] = $this->pdf->getFileDir();
			$update['file_name_display'] = $this->pdf->getFileDisplayName();
			
			$this->db_user->where( 'deleted', 0 );
			$this->db_user->update( $this->pdf->getTable(), $update );
		}
		else
		{
			$this->_error = $this->pdf->errors();
			return false;
		}
	}
}


?>