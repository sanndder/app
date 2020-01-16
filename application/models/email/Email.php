<?php

namespace models\email;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use models\Connector;
use PHPMailer\PHPMailer\PHPMailer;

/*
 * Email class
 *
 * Email object voor aanmaken en versturen van emails
 * Wrapper voor PHPMailer
 *
 */
class Email extends Connector {
	
	/*
	 * @var int
	 */
	private $_email_id = NULL;
	
	/*
	 * @var string
	 */
	private $_subject = NULL;
	
	/*
	 * default from
	 */
	private $_from_name = 'Abering Uitzenden';
	private $_from_email = 'info@aberinghr.nl';

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
	
	/**
	 * @var string
	 */
	private $_titel;
	
	/**
	 * @var array
	 */
	private $_to = array();
	private $_cc = array();
	private $_bcc = array();
	
	/**
	 * vertraging in minuten
	 * @var int
	 */
	private $_delay = 0;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * lege constructor maakt een nieuwe email aan, een ID meegegeven haalt een email uit de database op
	 *
	 *
	 * @param email ID
	 * @return void
	 */
	public function __construct( $email_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//op dev server altijd debug
		if( ENVIRONMENT == 'development' )
			$this->_debug = true;

		//load PHPMailer
		$this->_loadPHPMAiler();

		//init
		$this->_mail = new PHPMailer(true);

		//default niet html
		$this->_mail->IsHTML(false);

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set ID
	 *
	 * @return void
	 */
	private function _setID( $id )
	{
		$this->_email_id = intval( $id );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Titel instellen
	 *
	 * @return void
	 */
	public function setTitel( $titel = '' )
	{
		//geen HTML? Dan tags er uit
		if( $this->_html === false )
			$this->_titel = strip_tags( $titel );
		else
			$this->_titel = $titel;
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Onderwerp instellen
	 *
	 * @return void
	 */
	public function setSubject( $subject = '' )
	{
		$this->_subject = $subject;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * nieuwe ontvanger
	 *
	 * @return void
	 */
	public function to( $to )
	{
		$this->_to[] = $to;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
			$this->_body = str_replace( '{{titel}}', $this->_titel, $this->_body);
		}
		
		
		//echo $this->_body;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * hoe lang wachten
	 *
	 * @return void
	 */
	public function delay( $delay )
	{
		$this->_delay = intval( $delay );
	}




	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * deze functie verzend de email nog niet, maar slaat op in de database
	 *
	 * @return boolean
	 */
	public function send()
	{
		$date = new \DateTime();
		$date->add(new \DateInterval('PT' . $this->_delay . 'M'));
		
		$insert['subject'] = $this->_subject;
		$insert['body'] = $this->_body;
		$insert['from_name'] = $this->_from_name;
		$insert['from_email'] = $this->_from_email;
		$insert['send'] = 0;
		$insert['send_by'] = 0;
		$insert['send_on'] = $date->format('Y-m-d H:i:s');
		
		//alleen vanuit de applicatie
		if( isset($this->user->user_id) )
			$insert['created_by'] = $this->user->user_id;
		
		if( isset($this->db_user) )
		{
			$this->db_user->insert( 'emails', $insert );
			$dabase = $this->db_user;
		}
		else
		{
			$CI =& get_instance();
			$dabase = $CI->load->database('admin', TRUE);
			$dabase->insert( 'emails', $insert );
		}
		
		
		if( $dabase->insert_id() > 0 )
		{
			$this->_setID( $dabase->insert_id() );
			
			//ontvangers erbij
			foreach( $this->_to as $recipient )
			{
				$insert_to['email_id'] = $this->_email_id;
				$insert_to['type'] = 'to';
				$insert_to['name'] = $recipient['name'];
				$insert_to['email'] = $recipient['email'];
				
				$dabase->insert( 'emails_recipients', $insert_to );
			}
			
			if( isset($this->db_user) )
				$this->_log( 'email aangemaakt' );
		}
		
		//ook gelijk verzenden?
		if($this->_delay == 0 )
			$this->_sendEmail();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * logfunctie voor emails
	 *
	 * @return boolean
	 */
	private function _log( $actie )
	{
		$insert['email_id'] = $this->_email_id;
		$insert['action'] = $actie;
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'emails_log', $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Verzenden
	 *
	 * @return boolean
	 */
	public function _sendEmail()
	{
		$this->_mail->IsSMTP(); // enable SMTP
		$this->_mail->SMTPDebug = $this->_debug_level; // debugging: 1 = errors and messages, 2 = messages only
		$this->_mail->CharSet = 'UTF-8';
		
		
		$this->_mail->Subject = $this->_subject;
		$this->_mail->Body = $this->_body;
		
		foreach( $this->_to as $to )
			$this->_mail->AddAddress( $to['email'] );
		
		$this->_mail->addBCC('sander@aberinghr.nl', 'Sander Meijering');
		
		if( ENVIRONMENT == 'development' )
		{
			$this->_mail->SMTPAuth = true; // authentication enabled
			$this->_mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$this->_mail->Host = "smtp.gmail.com";
			$this->_mail->Port = 465; // or 587
			$this->_mail->SetFrom( "sander.m.app1@gmail.com" );
			
			$this->_mail->Username = "sander.m.app1@gmail.com";
			$this->_mail->Password = "Yutmoza86!";
	
		}
		if( ENVIRONMENT == 'production' )
		{
			$this->_mail->SMTPAuth = false; // authentication enabled
			$this->_mail->SetFrom( "info@aberinghr.nl" );
			$this->_mail->Host = "aberinghr-nl.mail.protection.outlook.com";
			$this->_mail->Port = 25; // or 587
		}
		
		
		if( !$this->_mail->Send() )
		{
			$this->_error = $this->_mail->ErrorInfo;
		}
		else
		{
			if( isset($this->db_user) )
				$dabase = $this->db_user;
			else
			{
				$CI =& get_instance();
				$dabase = $CI->load->database( 'admin', TRUE );
			}
			
			$update['send'] = 1;
			$update['send_on'] = date('Y-m-d H:i:s');
			
			if( isset($this->user->user_id) )
				$update['send_by'] = $this->user->user_id;
			
			$dabase->where( 'email_id', $this->_email_id );
			$dabase->update( 'emails', $update );
			
			if( isset($this->db_user) )
				$this->_log('Email verzonden');
		}
	
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Testfunctie
	 *
	 * @return boolean
	 */
	public function test()
	{

		if( ENVIRONMENT == 'development' )
		{
			$this->_mail->IsSMTP(); // enable SMTP
			$this->_mail->SMTPDebug = $this->_debug_level; // debugging: 1 = errors and messages, 2 = messages only
			$this->_mail->SMTPAuth = true; // authentication enabled
			$this->_mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$this->_mail->Host = "smtp.gmail.com";
			$this->_mail->Port = 465; // or 587
			$this->_mail->CharSet = 'UTF-8';
			
			$this->_mail->Username = "sander.m.app1@gmail.com";
			$this->_mail->Password = "Yutmoza86!";
			$this->_mail->SetFrom( "sander.m.app1@gmail.com" );
			$this->_mail->Subject = $this->_subject;
			
			$this->_mail->Body = $this->_body;
			$this->_mail->AddAddress( "hsmeijering@home.nl" );
			$this->_mail->AddAddress( "sanndder@hotmail.com" );
			
			if( !$this->_mail->Send() )
			{
				$this->_error = $this->_mail->ErrorInfo;
			} else
			{
				show( "Message has been sent" );
			}
		}
		if( ENVIRONMENT == 'production' )
		{
			$this->_mail->IsSMTP(); // enable SMTP
			$this->_mail->SMTPDebug = $this->_debug_level; // debugging: 1 = errors and messages, 2 = messages only
			$this->_mail->SMTPAuth = false; // authentication enabled
			$this->_mail->Host = "aberinghr-nl.mail.protection.outlook.com";
			$this->_mail->Port = 25; // or 587
			
			$this->_mail->SetFrom( "info@aberinghr.nl" );
			$this->_mail->Subject = $this->_subject;
			
			$this->_mail->Body = $this->_body;
			$this->_mail->AddAddress( "hsmeijering@home.nl" );
			
			if( !$this->_mail->Send() )
			{
				$this->_error = $this->_mail->ErrorInfo;
			}
			else
			{
				show( "Message has been sent" );
			}
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Debug aanzetten op live server
	 *
	 * @return void
	 */
	public function debug( $level = 0 )
	{
		//set debug
		$this->_debug = true;
		$this->_debug_level = $level;

		// melding footer laten zien
		//echo '<div class="email-debug-message">EMAIL DEBUG ACTIVE!</div>';

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|boolean
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