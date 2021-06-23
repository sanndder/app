<?php

namespace models\documenten;
use models\file\Pdf;
use models\pdf\PdfBuilder;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Documenten maken
 *
 */
class DocumentOpdrachtbevestiging extends Document implements DocumentInterface {

	protected $_template_object = NULL;
	protected $_plaatsing = NULL;
	protected $_uurtarieven = NULL;
	
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
		
		$this->replacePlaatsingVars();
		
		$this->pdf->mpdf->WriteHTML( $this->_html );
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Plaatsing variabelen in de tekst vervangen
	 *
	 * @return void
	 */
	public function replacePlaatsingVars() :DocumentOpdrachtbevestiging
	{
		//show( $this->_plaatsing );

		$this->_html = str_replace( '{{plaatsing.cao}}', $this->_plaatsing['cao']['cao_name'], $this->_html );

		foreach( $this->_plaatsing as $field => $value )
		{
			if( $field == 'start_plaatsing' )
				$value = reverseDate( $value );
			
			if( !is_array( $value ) )
				$this->_html = str_replace( '{{plaatsing.' . $field . '}}', $value, $this->_html );
		}
		
		//tarieven
		if( isset($this->_uurtarieven) && is_array($this->_uurtarieven) )
		{
			$tarieven_html = '';
			foreach( $this->_uurtarieven as $tarief )
				$tarieven_html .=  $tarief['label'] . ': €' . $tarief['verkooptarief'] .'  <br />';
		
			$this->_html = str_replace( '{{plaatsing.tarieven}}', $tarieven_html, $this->_html );
		}
		
		$urentypes['overuren'] = array();
		$urentypes['toeslag'] = array();
		$urentypes['ploegentoeslag'] = array();
		
		//urentypes
		if( isset($this->_plaatsing['cao']['werksoort']) )
		{
			foreach( $this->_plaatsing['cao']['werksoort'] as $werksoort )
			{
				if( $werksoort['name'] == 'Toeslag' )
					$urentypes['toeslag'][] = $werksoort['amount'];
				
				if( $werksoort['name'] == 'Overuur' )
					$urentypes['overuren'][] = $werksoort['amount'];
				
				if( $werksoort['name'] == 'Ploegentoeslag' )
					$urentypes['ploegentoeslag'][] = $werksoort['amount'];
			}
		}
		
		$value = '';
		if( count($urentypes['overuren']) > 0  )
		{
			foreach( $urentypes['overuren'] as $amount )
				$value .=  $amount . '% <br />';
		}
		$this->_html = str_replace( '{{plaatsing.overuren}}', $value, $this->_html );
		
		$value = '';
		if( count($urentypes['toeslag']) > 0  )
		{
			foreach( $urentypes['toeslag'] as $amount )
				$value .=  $amount . '% <br />';
		}
		$this->_html = str_replace( '{{plaatsing.toeslag}}', $value, $this->_html );
		
		$value = '';
		if( count($urentypes['ploegentoeslag']) > 0  )
		{
			foreach( $urentypes['ploegentoeslag'] as $amount )
				$value .=  $amount . '% <br />';
		}
		$this->_html = str_replace( '{{plaatsing.toeslag_ploegen}}', $value, $this->_html );
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Plaatsing toevoegen
	 *
	 * @return void
	 */
	public function setPlaatsing( $plaatsing, $uurtarieven ) :DocumentOpdrachtbevestiging
	{
		$this->_plaatsing = $plaatsing;
		$this->_uurtarieven = $uurtarieven;
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