<?php

use models\documenten\Document;
use models\users\User;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Teken document
 */

class Klantkaart extends EX_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// main
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		
		$this->smarty->display('klantkaart.tpl');
	}
	
	
}
