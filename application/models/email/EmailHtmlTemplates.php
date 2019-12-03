<?php
/**
 * Created by PhpStorm.
 * User: Sander
 * Date: 2-5-2019
 * Time: 14:48
 */

namespace models\Email;

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
	 * @param template string
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
	 * Template voor mails vanuit de app
	 *
	 * @return void
	 */
	public function _default()
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
							.text-container{ display: block; border:1px solid #DDDDDD; background-color: #FFFFFF; width: 700px; text-align: left }
							.text-container td{padding: 15px;}
						 </style>';

		//body start
		$this->_html .= '<body>';

		$this->_html .= '<table class="text-container">';
		$this->_html .= '<tr><td>';

		//var in de body
		$this->_html .= '{{body}}';

		$this->_html .= '</td></tr>';
		$this->_html .= '</table>';

		//close head
		$this->_html .= '</head>';

		//close body
		$this->_html .= '</body></html>';
	}

}