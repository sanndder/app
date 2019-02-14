<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 **/

class Test extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		$this->load->model('test2_model', 'test2');
		$this->load->model('test_model', 'test');
	}


	//--------------------------------------------------------------------------
	// test method
	//--------------------------------------------------------------------------
	public function index()
	{
		$this->test2->connect();
		$this->test->go();

	}

}
