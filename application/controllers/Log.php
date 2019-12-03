<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Log class
 */

class Log extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// test ajax method
	//-----------------------------------------------------------------------------------------------------------------
	public function ajaxerror()
	{
		$log = new \models\Log\Log();
		$log->setDir( 'ajax' )->openFile()->writeData( $_POST )->saveFile();
		
		//show($_POST);
	}
}
