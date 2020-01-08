<?php
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
		
		
		//logout wanneer user klikt
		$logout = false; if( isset($_GET['logout']) )$logout = true;
		
		//controllers die benaderd mogen worden zonder login
		$no_login = array('aanmelden', 'crm', 'usermanagement');
		
		
		//validate user, wanneer ingelogd daan nooit no access
		if( !in_array($this->uri->segment(1),$no_login) || isset($_SESSION['logindata']['main']) )
		{
			//eventueel switchen VOOR de check ivm connect database
			if( isset($_GET['switchto']) )
				$this->auth->switchAccount( $_GET['switchto'] );
			
			//nu check
			$this->auth->check( $logout );
		}
		else
			$this->auth->validate_nologin();
	

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
		
		//deze classes niet redirecten
		$no_redirect[] = 'welkom';
		$no_redirect[] = 'ajax';
		$no_redirect[] = 'documenten';
		
		//usertype model laden
		if( $this->user->user_type == 'uitzender' )
		{
			$this->load->model('uitzender_model', 'uitzender');
			if( $this->uitzender->blockAccess() && !in_array( $this->uri->segment(1), $no_redirect) )
				redirect( $this->config->item( 'base_url' ) . $this->uitzender->redirectUrl() ,'location' );
		}
		
		//usertype model laden
		if( $this->user->user_type == 'inlener' )
		{
			$this->load->model('inlener_model', 'inlener');
			if( $this->inlener->blockAccess() && !in_array( $this->uri->segment(1), $no_redirect) )
				redirect( $this->config->item( 'base_url' ) . $this->inlener->redirectUrl() ,'location' );
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