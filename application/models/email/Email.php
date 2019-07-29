<?php

namespace models\Email;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/*
 * Email class
 *
 * Email object voor aanmaken en versturen van emails
 * Wrapper voor PHPMailer
 *
 */
class Email{

	/*
	 * @var string
	 */
	private $_subject = NULL;

	/*
	 * @var int
	 */
	private $_body = NULL;

	/*
	 * @var boolean
	 */
	private $_html = true;

	/*
	 * @var array
	 */
	private $_error = NULL;

	/*
	 * PHPMailer object
	 * @var array
	 */
	private $_mail = NULL;

	/*
	 * Debug on/off
	 * @var boolean
	 */
	private $_debug = false;

	/*
	 * Debug voor PHPMailer
	 * @var int
	 */
	private $_debug_level = 0;



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * lege constructor maakt een nieuwe email aan, een ID meegegeven haalt een email uit de database op
	 *
	 *
	 * @param email ID
	 * @return $this
	 */
	public function __construct( $email_id = '' )
	{
		//op dev server altijd debug
		if( ENVIRONMENT == 'development' )
			$this->_debug = true;

		//load PHPMailer
		$this->_loadPHPMAiler();

		//init
		$this->_mail = new PHPMailer(true);

		//default niet html
		$this->_mail->IsHTML(false);

		//return object
		return $this;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Body instellen
	 *
	 * @return void
	 */
	public function setBody( $body = '' )
	{
		//geen HTML? Dan tags er uit
		if( $this->_html === false )
			$this->_body = strip_tags( $body );
		else
			$this->_body = $body;

	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Onderwerp instellen
	 *
	 * @return void
	 */
	public function setSubject( $subject = '' )
	{
		$this->_subject = $subject;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Maakt een html mail van de email en gebruikt een template
	 *
	 * @return void
	 */
	public function useHtmlTemplate( $template = 'default' )
	{
		//template uitzetten
		if ($template == false)
		{
			$this->_html = false;
			$this->_mail->IsHTML(false);
			//body tags strippen
			$this->_body = strip_tags( $this->_body );
		}
		else
		{
			//Bij template html genruiken
			$this->_html = true;
			$this->_mail->IsHTML(true);

			//template laden
			$template = new EmailHtmlTemplates( $template );
			$html = $template->html();

			//body inladen in template
			$this->_body = str_replace( '{{body}}', $this->_body, $html);

		}

	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Testfunctie
	 *
	 * @return boolean
	 */
	public function test()
	{


		$this->_mail->IsSMTP(); // enable SMTP
		$this->_mail->SMTPDebug = $this->_debug_level; // debugging: 1 = errors and messages, 2 = messages only
		$this->_mail->SMTPAuth = true; // authentication enabled
		$this->_mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$this->_mail->Host = "smtp.gmail.com";
		$this->_mail->Port = 465; // or 587


		$this->_mail->Username = "sander.m.app1@gmail.com";
		$this->_mail->Password = "Yutmoza86!";
		$this->_mail->SetFrom("sander.m.app1@gmail.com");
		$this->_mail->Subject = $this->_subject;

		$this->_mail->Body = $this->_body;
		$this->_mail->AddAddress("hsmeijering@home.nl");


		if(!$this->_mail->Send()) {
			$this->_error = $this->_mail->ErrorInfo;
		} else {
			show("Message has been sent");
		}
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * PHPMailer inladen
	 *
	 * @return void
	 */
	private function _loadPHPMAiler()
	{
		require_once('application/third_party/PHPMailer/PHPMailer.php');
		require_once('application/third_party/PHPMailer/SMTP.php');
		require_once('application/third_party/PHPMailer/Exception.php');
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Debug aanzetten op live server
	 *
	 * @return void
	 */
	public function debug( $level = 0 )
	{
		//connect to admin database
		$CI =& get_instance();
		$db_admin = $CI->load->database('admin', TRUE);

		$insert['subject'] = 'test';
		$db_admin->insert('emails', $insert);

		//set debug
		$this->_debug = true;
		$this->_debug_level = $level;

		// melding footer laten zien
		echo '<div class="email-debug-message">EMAIL DEBUG ACTIVE!</div>';

	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
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