<?php

use models\utils\Ondernemingen;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor pagina's met auth controlle
 **/
class MY_Controller extends CI_Controller
{
	
	//-------------------------------------------------------------------------------------------------------------------------
	// Constructor voor MY_Controller
	//-------------------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		//call parent constructor
		parent::__construct();

		//base_url naar smarty
		$this->smarty->assign( 'time' , time() );
		$this->smarty->assign( 'base_url' , BASE_URL );
		$this->smarty->assign( 'current_url' , current_url() );
		$this->smarty->assign( 'qs' , $_SERVER['QUERY_STRING'] );
		$this->smarty->assign( 'ENV' , ENVIRONMENT );
		
		if( ENVIRONMENT == 'development' )
			$this->smarty->assign( 'app_name' , '*Dev* ' . APP_NAME);
		else
			$this->smarty->assign( 'app_name' , APP_NAME);

		//clear
		/*
		if( ENVIRONMENT == 'development')
		{
			foreach (new DirectoryIterator('application/views/_compile') as $fileInfo) {
				if(!$fileInfo->isDot()) {
					unlink($fileInfo->getPathname());
				}
			}
		}*/
		
		//logout wanneer user klikt
		$logout = false; if( isset($_GET['logout']) )$logout = true;
		
		//controllers die benaderd mogen worden zonder login
		$no_login = array('aanmelden', 'crm', 'usermanagement', 'sign', 'documenten', 'cronjobs');
		
		//validate user, wanneer ingelogd dan nooit no-access
		if( !in_array($this->uri->segment(1),$no_login) || isset($_SESSION['logindata']['main']) )
		{
			//inloggen als iemand anders
			if( isset($_GET['loginals']) && $_SESSION['logindata']['user_type'] == 'werkgever' )
			{
				$redirect_url = $this->auth->loginOverrideByUsertype( $_GET['loginals'],  $_GET['id'] );
				redirect( $redirect_url  ,'location' );
			}
			
			//eventueel switchen VOOR de check ivm connect database
			if( isset($_GET['switchto']) )
			{
				$this->auth->switchAccount( $_GET['switchto'] );
				$redirect_url = Ondernemingen::switchUrl();
				
				//link wijzigen
				if( $redirect_url !== false )
					redirect( $redirect_url  ,'location' );
			}
			
			//nu check
			$this->auth->check( $logout );
		}
		else
		{
			if( $this->uri->segment(1) != 'cronjobs' )
				$this->auth->validate_nologin();
		}
		//init user
		$this->load->model('user_model', 'user');
		$this->smarty->assign( 'account_id' , $this->user->account_id );
		$this->smarty->assign( 'user_id' , $this->user->user_id );
		$this->smarty->assign( 'user_type' , $this->user->user_type );
		$this->smarty->assign( 'user_accounts' , $this->user->user_accounts );
		$this->smarty->assign( 'werkgever_naam' , $this->user->werkgever_naam );
		$this->smarty->assign( 'werkgever_type' , $this->user->werkgever_type );
		
		//always load werkgever
		$this->load->model('werkgever_model', 'werkgever');
		
		//juiste termen aanmaken
		if( $this->user->werkgever_type == 'uitzenden' )
		{
			$this->smarty->assign( '_werknemers' , 'werknemers' );
			$this->smarty->assign( '_werknemer' , 'werknemer' );
		}
		if( $this->user->werkgever_type == 'bemiddeling' )
		{
			$this->smarty->assign( '_werknemers' , "ZZP'ers" );
			$this->smarty->assign( '_werknemer' , "ZZP'er" );
		}
		
		//deze classes niet redirecten
		$no_redirect[] = 'welkom';
		$no_redirect[] = 'ajax';
		$no_redirect[] = 'documenten';
		$no_redirect[] = 'sign';
		$no_redirect[] = 'cronjobs';
		
		//usertype model laden
		if( $this->user->user_type == 'uitzender' )
		{
			$this->load->model('uitzender_model', 'uitzender');
			$this->smarty->assign( 'uitzender_id', $this->user->uitzender_id );
			if( $this->uitzender->blockAccess() && !in_array( $this->uri->segment(1), $no_redirect) )
				redirect( $this->config->item( 'base_url' ) . $this->uitzender->redirectUrl() ,'location' );
		}
		
		//usertype model laden
		if( $this->user->user_type == 'inlener' )
		{
			$this->load->model('inlener_model', 'inlener');
			$this->smarty->assign( 'inlener_id', $this->user->inlener_id );
			if( $this->inlener->blockAccess() && !in_array( $this->uri->segment(1), $no_redirect) )
				redirect( $this->config->item( 'base_url' ) . $this->inlener->redirectUrl() ,'location' );
		}
		
		//usertype model laden
		if( $this->user->user_type == 'werknemer' )
		{
			$this->load->model('werknemer_model', 'werknemer');
			$this->smarty->assign( 'werknemer_id', $this->user->werknemer_id );
		}
		
	}

}

/**
 * Controller voor pagina's zonder auth controlle
 **/
class EX_Controller extends CI_Controller
{
	//-------------------------------------------------------------------------------------------------------------------------
	// Constructor voor EX_Controller
	//-------------------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		//call parent constructor
		parent::__construct();
		
		//uitloggen
		if( isset($_GET['logout']) )
			$this->auth->check( true );

		//set EXTERNAL constant to true
		defined('EXTERNAL_CONN') OR define('EXTERNAL_CONN', true);

		//base_url naar smarty
		$this->smarty->assign( 'base_url' , BASE_URL );
		$this->smarty->assign( 'app_name' , APP_NAME);
	}

}

?>