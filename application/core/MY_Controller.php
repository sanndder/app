<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @property Auth $auth auth class
 * @property Translation_model $translation class
 * @property Smarty_class $smarty class
 *
**/


/*
 * Controller voor pagina's met auth controlle
 */
class MY_Controller extends CI_Controller
{
	public $logindata = NULL;

	//----------------------------------------------------------------------------------
	// Constructor voor MY_Controller
	//----------------------------------------------------------------------------------
	public function __construct()
	{

		//call parent constructor
		parent::__construct();

		//base_url naar smarty
		$this->smarty->assign( 'base_url' , BASE_URL );

	}

}


/**
 *
 * @property Translation_model $translation class
 * @property Smarty_class $smarty class
 *
 **/

/*
 * Controller voor pagina's zonder auth controlle
 */
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
	}

}

?>