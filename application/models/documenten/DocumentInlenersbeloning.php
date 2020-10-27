<?php

namespace models\documenten;
use models\file\Pdf;
use models\pdf\PdfBuilder;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Documenten maken
 *
 */
class DocumentInlenersbeloning extends Document implements DocumentInterface {

	protected $_template_object = NULL;
	protected $_inlenersbeloning = NULL;
	
	/*
	 * @var array
	 */
	protected $_error = NULL;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @return object
	 */
	public function __construct( Template $template_object )
	{
		parent::__construct();
		
		$this->_template_object = $template_object;
		
		$config['margin_left'] = 0;
		$config['margin_right'] = 0;
		
		$config['margin_header'] = 0;
		$config['margin_footer'] = 0;
		
		$config['margin_top'] = 30;
		$config['margin_bottom'] = 30;
		
		$config['format'] = 'P';
		$config['default_font'] = 'Helvetica';
		$config['titel'] = $this->_template_object->naam();
		
		//start mPDF library
		$this->pdf = new PdfBuilder( $config );
		
		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/default.css');
		$this->pdf->mpdf->WriteHTML($stylesheet, 1);
		
		//juiste map en tabel
		$this->setPDFInfo();
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * opbouwen
	 *
	 * @return object
	 */
	public function build( $preview = '' )
	{
		$this->setHeader();
		$this->setFooter();
		$this->setBody();

		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * PDF file instellen
	 *
	 * @return void
	 */
	public function setPDFInfo()
	{
		$settings = $this->_template_object->settings();

		$this->pdf->setFileDir($settings['dir']);
		$this->pdf->setFileName( str_replace( ' ', '_', $settings['template_name']) . '_' . uniqid() . '.pdf' );
		$this->pdf->setFileDisplayName( str_replace( ' ', '_', $settings['template_name']) . '.pdf' );
		$this->pdf->setTable( 'documenten' );
		
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
		
		$bedrijfsgegevens = $CI->werkgever->bedrijfsgegevens();
		$this->pdf->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		
		$header = $this->pdf->smarty->fetch('application/views/pdf/headers/header_full.tpl');
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
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		
		$this->pdf->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		
		$footer = $this->pdf->smarty->fetch('application/views/pdf/footers/footer_sign.tpl');
		$this->pdf->mpdf->SetHTMLFooter($footer);
		
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
		//altijd in container
		$this->_html .= '<div class="container">';
		
		//titel er in
		$this->_html .= '<span style="font-size: 20px;">'.$this->_template_object->titel().'</span><br /><br />';
		
		//template html
		$this->_html .= $this->_template_object->body();
		
		//sluit container
		$this->_html .= '</div>';
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
		$this->replaceVars();
		
		$this->replaceInlenersbeloningVars();
		
		$this->pdf->mpdf->WriteHTML( $this->_html );
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Plaatsing variabelen in de tekst vervangen
	 *
	 * @return void
	 */
	public function replaceInlenersbeloningVars() :DocumentInlenersbeloning
	{
		//cao ja nee
		if( !isset($this->_inlenersbeloning['cao_ja_nee']) || $this->_inlenersbeloning['cao_ja_nee'] == '' || $this->_inlenersbeloning['cao_ja_nee'] === NULL )
			$this->_html = str_replace( '{{inlenersbeloning.cao_ja_nee}}', 'ja / nee', $this->_html );
		else
			$this->_html = str_replace( '{{inlenersbeloning.cao_ja_nee}}', $this->_inlenersbeloning['cao_ja_nee'], $this->_html );
		
		if( isset($this->_inlenersbeloning['cao']) && $this->_inlenersbeloning['cao_ja_nee'] == 'ja' )
			$this->_html = str_replace( '{{inlenersbeloning.cao}}', '<b>Indien ja, welke CAO?</b> ' . $this->_inlenersbeloning['cao'], $this->_html );
		
		if( isset($this->_inlenersbeloning['cao']) && $this->_inlenersbeloning['cao_ja_nee'] == 'nee' )
			$this->_html = str_replace( '{{inlenersbeloning.cao}}', '', $this->_html );
		
		
		if( !isset($this->_inlenersbeloning['cao']) || $this->_inlenersbeloning['cao'] == '' || $this->_inlenersbeloning['cao'] === NULL )
			$this->_html = str_replace( '{{inlenersbeloning.cao}}', '<b>Indien ja, welke CAO?</b> __________________________________________', $this->_html );
		
		//branche
		if( !isset($this->_inlenersbeloning['branche']) || $this->_inlenersbeloning['branche'] == '' || $this->_inlenersbeloning['branche'] === NULL )
			$this->_html = str_replace( '{{inlenersbeloning.branche}}', '<br />__________________________________________', $this->_html );
		else
			$this->_html = str_replace( '{{inlenersbeloning.branche}}', $this->_inlenersbeloning['branche'], $this->_html );
		
		//werkweek
		if( !isset($this->_inlenersbeloning['werkweek']) || $this->_inlenersbeloning['werkweek'] == '' || $this->_inlenersbeloning['werkweek'] === NULL )
			$this->_html = str_replace( '{{inlenersbeloning.werkweek}}', '<br />____________', $this->_html );
		else
			$this->_html = str_replace( '{{inlenersbeloning.werkweek}}', $this->_inlenersbeloning['werkweek'], $this->_html );
		
		//loonschalen_ja_nee
		if( !isset($this->_inlenersbeloning['loonschalen_ja_nee']) || $this->_inlenersbeloning['loonschalen_ja_nee'] == '' || $this->_inlenersbeloning['loonschalen_ja_nee'] === NULL )
			$this->_html = str_replace( '{{inlenersbeloning.loonschalen_ja_nee}}', 'ja / nee', $this->_html );
		else
			$this->_html = str_replace( '{{inlenersbeloning.loonschalen_ja_nee}}', $this->_inlenersbeloning['loonschalen_ja_nee'], $this->_html );
		
		//adv ja nee
		if( !isset($this->_inlenersbeloning['adv_ja_nee']) || $this->_inlenersbeloning['adv_ja_nee'] == '' || $this->_inlenersbeloning['adv_ja_nee'] === NULL )
			$this->_html = str_replace( '{{inlenersbeloning.adv_ja_nee}}', 'ja / nee', $this->_html );
		else
			$this->_html = str_replace( '{{inlenersbeloning.adv_ja_nee}}', $this->_inlenersbeloning['adv_ja_nee'], $this->_html );
		
		//extra veld
		if( isset($this->_inlenersbeloning['adv_ja_nee']) && $this->_inlenersbeloning['adv_ja_nee'] == 'ja' && isset($this->_inlenersbeloning['adv_uren']))
			$this->_html = str_replace( '{{inlenersbeloning.adv_uren}}', '<b>Indien ja, hoeveel uur per week?</b> ' . $this->_inlenersbeloning['adv_uren'] . ' uur', $this->_html );
		
		if( !isset($this->_inlenersbeloning['adv_ja_nee']) || $this->_inlenersbeloning['adv_ja_nee'] == '' || $this->_inlenersbeloning['adv_ja_nee'] === NULL || !isset($this->_inlenersbeloning['adv_uren']) )
			$this->_html = str_replace( '{{inlenersbeloning.adv_uren}}', '<b>Indien ja, hoeveel uur?</b> ____________ uur', $this->_html );
		
		if( isset($this->_inlenersbeloning['adv_ja_nee']) && $this->_inlenersbeloning['adv_ja_nee'] == 'nee' )
			$this->_html = str_replace( '{{inlenersbeloning.adv_uren}}', '', $this->_html );

		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Plaatsing toevoegen
	 *
	 * @return void
	 */
	public function setPlaatsing( $plaatsing ) :DocumentInlenersbeloning
	{
		$this->_plaatsing = $plaatsing;
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * pdf voorbeeld maken
	 *
	 * output naar browser
	 * @return object|boolean
	 */
	public function preview()
	{
		$this->pdf->preview();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * pdf maken
	 *
	 * Geeft PDF object terug
	 * @return object|boolean
	 */
	public function pdf( $origineel = false )
	{
		$pdfObject = $this->pdf->generate();
		
		if( $pdfObject !== false )
		{
			//opslaan in tabel
			$insert['template_id'] = $this->_template_object->id();
			$insert['uitzender_id'] = $this->_uitzender_id;
			$insert['inlener_id'] = $this->_inlener_id;
			$insert['werknemer_id'] = $this->_werknemer_id;
			$insert['zzp_id'] = $this->_zzp_id;
			$insert['html'] = $this->_html;
			$insert['file_name'] = $this->pdf->getFileName();
			$insert['file_dir'] = $this->pdf->getFileDir();
			$insert['file_name_display'] = $this->pdf->getFileDisplayName();
			
			$this->db_user->insert( $this->pdf->getTable(), $insert );
			
			//TODO betere fout afhandeling
			if( $this->db_user->insert_id() < 1 )
				die('Document kon niet worden opgeslagen in de database');
				
			//set ID
			$this->setDocumentId( $this->db_user->insert_id() );
			
			//handtekeningen in de wacht
			if ($this->user->user_type == 'uitzender' )$this->addEmptySignature( 'uitzender', $this->_uitzender_id );
			if ($this->user->user_type == 'inlener' )$this->addEmptySignature( 'inlener', $this->_inlener_id );
			if ($this->user->user_type == 'werknemer' )$this->addEmptySignature( 'werknemer', $this->_werknemer_id );
			if ($this->user->user_type == 'zzp' )$this->addEmptySignature( 'zzp', $this->_zzp_id );
			
			return $pdfObject;
		}
		else
		{
			$this->_error = $this->pdf->errors();
			return false;
		}
	}
}


?>