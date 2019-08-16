<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Testex extends EX_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//$this->load->model('test2_model', 'test2');
		$this->load->model('emailcentrum_model', 'emailcentrum');

		//show( $this->user->user_type );


	}

	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{

		$data['id'] = 12;
		$data['timestamp'] = '2019-05-06 10:15:00';
		$data['bedrijfsnaam'] = 'Jan van Tongeren - De Groot';
		$data['bedrijfsnaam'] = '105518979';



		$this->smarty->display('index.tpl');
	}

}
