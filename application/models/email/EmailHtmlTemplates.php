<?php
/**
 * Created by PhpStorm.
 * User: Sander
 * Date: 2-5-2019
 * Time: 14:48
 */

namespace models\email;

/*
 * Emailtemplate class
 *
 * Hier worden alle html styles voor email bewaard
 *
 */
class EmailHtmlTemplates
{
	private $_html = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor stelt juiste template in
	 *
	 *
	 * @param string
	 * @return object
	 */
	public function __construct( $template = '' )
	{
		$method = '_' . $template;

		if( method_exists( $this, $method ) )
		{
			$this->$method();
		}

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * geeft de juiste html terug
	 *
	 *
	 * @return string
	 */
	public function html()
	{
		return $this->_html;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Template voor mails buiten de app, vanuit devis
	 *
	 * @return void
	 */
	public function _devis()
	{
		
		//head
		$this->_html = '<!doctype html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		
		//css
		$this->_html .= '<style type="text/css">
							#outlook a { padding: 0; }
						 	.ReadMsgBody { width: 100%; }
							.ExternalClass { width: 100%; }
							.ExternalClass * { line-height:100%; }
       
							body{background-color: #F2F2F2; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; text-align: center; font-family:Verdana, Arial, sans-serif; font-size: 12px }
							.header{background-color: #002E65; width: 685px; color:#fff; text-align: left; font-size: 20px; padding-top:15px;  padding-bottom:15px; display: block; padding-left: 25px}
							.footer{background-color: #002E65; width: 685px; height: 25px; color:#fff; text-align: center; font-size: 11px;}
							.footer a, footer a:visited{ color:#fff !important;}
							.text-container{ display: block;  background-color: #FFFFFF; width: 700px; text-align: left; border-width: 0px; border-collapse: collapse}
							.text-container td.titel{padding:25px 25px 0 25px; font-size: 15px}
							.text-container td.text{padding: 25px; line-height: 24px}
						 </style>';
		
		//close head
		$this->_html .= '</head>';
		
		//body start
		$this->_html .= '<body>';
		
		//blauw header
		$this->_html .= '';
		
		$this->_html .= '<table class="text-container">';
		$this->_html .= '<tr><td class="header">Devis Online</td></tr>';
		
		//titel var
		$this->_html .= '<tr><td class="titel">';
		$this->_html .= '{{titel}}';
		$this->_html .= '</td></tr>';
		
		//var in de body
		$this->_html .= '<tr><td class="text">';
		$this->_html .= '{{body}}';
		$this->_html .= '</td></tr>';
		
		$this->_html .= '<tr><td class="footer">Devils Online</td></tr>';
		
		$this->_html .= '</table>';
		
		
		//close body
		$this->_html .= '</body></html>';
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Template voor mails vanuit de app
	 *
	 * @return void
	 */
	public function _default()
	{
		$CI =& get_instance();
		$bedrijfsgegevens = $CI->werkgever->bedrijfsgegevens();
		
		//head
		$this->_html = '<!doctype html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';

		//css
		$this->_html .= '<style type="text/css">
							#outlook a { padding: 0; }
						 	.ReadMsgBody { width: 100%; }
							.ExternalClass { width: 100%; }
							.ExternalClass * { line-height:100%; }
        
							body{background-color: #F2F2F2; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; text-align: center; font-family:Verdana, Arial, sans-serif; font-size: 12px }
							.header{background-color: #002E65; width: 685px; color:#fff; text-align: left; font-size: 20px; padding-top:15px;  padding-bottom:15px; display: block; padding-left: 25px}
							.footer{background-color: #002E65; width: 685px; height: 25px; color:#fff; text-align: center; font-size: 11px;}
							.footer a, footer a:visited{ color:#fff !important;}
							.text-container{ display: block;  background-color: #FFFFFF; width: 700px; text-align: left; border-width: 0px; border-collapse: collapse}
							.text-container td.titel{padding:25px 25px 0 25px; font-size: 15px}
							.text-container td.text{padding: 25px; line-height: 24px}
						 </style>';
		
		//close head
		$this->_html .= '</head>';

		//body start
		$this->_html .= '<body>';
		
		//blauw header
		$this->_html .= '';
		
		$this->_html .= '<table class="text-container">';
		$this->_html .= '<tr><td class="header">'.$bedrijfsgegevens['bedrijfsnaam'].'</td></tr>';
		
		//titel var
		$this->_html .= '<tr><td class="titel">';
		$this->_html .= '{{titel}}';
		$this->_html .= '</td></tr>';
		
		//var in de body
		$this->_html .= '<tr><td class="text">';
		$this->_html .= '{{body}}';
		$this->_html .= '</td></tr>';
		
		$this->_html .= '<tr><td class="footer">'.$bedrijfsgegevens['straat'].' '.$bedrijfsgegevens['huisnummer'].' | '.$bedrijfsgegevens['postcode'].' '.$bedrijfsgegevens['plaats'].'|
		'.$bedrijfsgegevens['telefoon'].' | '.$bedrijfsgegevens['email'].'</td></tr>';
		
		$this->_html .= '</table>';
		

		//close body
		$this->_html .= '</body></html>';
	}

}