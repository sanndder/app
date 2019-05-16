<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor pagina's met auth controlle
 **/
class MY_Controller extends CI_Controller
{
	//----------------------------------------------------------------------------------
	// Constructor voor MY_Controller
	//----------------------------------------------------------------------------------
	public function __construct()
	{
		//call parent constructor
		parent::__construct();

		//base_url naar smarty
		$this->smarty->assign( 'base_url' , BASE_URL );
		$this->smarty->assign( 'app_name' , 'App' );

		//logout
		$logout = false;
		if( isset($_GET['logout']) )
			$logout = true;

		//validate user
		$this->auth->check( $logout );

		//init user
		$this->load->model('user_model', 'user');
	}

}

/**
 * Controller voor pagina's zonder auth controlle
 **/
class EX_Controller extends CI_Controller
{
	//----------------------------------------------------------------------------------
	// Constructor voor EX_Controller
	//----------------------------------------------------------------------------------
	public function __construct()
	{
		//call parent constructor
		parent::__construct();

		//base_url naar smarty
		$this->smarty->assign( 'base_url' , BASE_URL );
		$this->smarty->assign( 'app_name' , 'App' );
	}

}

?>