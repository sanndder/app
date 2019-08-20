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
		$this->smarty->assign( 'app_name' , APP_NAME );

		//logout
		$logout = false;
		if( isset($_GET['logout']) )
			$logout = true;

		//validate user
		$this->auth->check( $logout );

		//init user
		$this->load->model('user_model', 'user');

		//always load werkgever
		$this->load->model('werkgever_model', 'werkgever');
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