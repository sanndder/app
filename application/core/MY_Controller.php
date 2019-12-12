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
		$this->smarty->assign( 'app_name' , APP_NAME );
		
		//logout wanneer user klikt
		$logout = false; if( isset($_GET['logout']) )$logout = true;
		
		//controllers die benaderd mogen worden zonder login
		$no_login = array('aanmelden', 'crm');
		
		//validate user, wanneer ingelogd daan nooit no access
		if( !in_array($this->uri->segment(1),$no_login) || isset($_SESSION['logindata']['main']) )
			$this->auth->check( $logout );
		else
			$this->auth->validate_nologin();

		//init user
		$this->load->model('user_model', 'user');

		//always load werkgever
		$this->load->model('werkgever_model', 'werkgever');
		
		//deze classes niet redirecten
		$no_redirect[] = 'welkom';
		$no_redirect[] = 'ajax';
		
		//usertype model laden
		if( $this->user->user_type == 'uitzender' )
		{
			$this->load->model('uitzender_model', 'uitzender');
			if( $this->uitzender->blockAccess() && !in_array( $this->uri->segment(1), $no_redirect) )
			{
				redirect( $this->config->item( 'base_url' ) . $this->uitzender->redirectUrl() ,'location' );
			}
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

		//set EXTERNAL constant to true
		defined('EXTERNAL_CONN') OR define('EXTERNAL_CONN', true);

		//base_url naar smarty
		$this->smarty->assign( 'base_url' , BASE_URL );
		$this->smarty->assign( 'app_name' , APP_NAME);
	}

}

?>