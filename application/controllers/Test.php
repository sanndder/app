<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Test extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//$this->load->model('test2_model', 'test2');
		$this->load->model('emailcentrum_model', 'emailcentrum');

		//show( $this->user->user_type );


	}


	//--------------------------------------------------------------------------
	// Run a series of validation tests
	//--------------------------------------------------------------------------
	public function validation()
	{



		$validator = new models\Forms\Validator();
		$validator->table( 'test' )->input( $data )->run();

		$this->smarty->display('test/validation.tpl');
	}


	//--------------------------------------------------------------------------
	// test method
	//--------------------------------------------------------------------------
	public function index()
	{

		$data['id'] = 12;
		$data['timestamp'] = '2019-05-06 10:15:00';
		$data['bedrijfsnaam'] = 'Jan van Tongeren - De Groot';
		$data['bedrijfsnaam'] = '105518979';


		$validator = new models\Forms\Validator();
		$validator->table( 'werkgever_bedrijfsgegevens' )->input( $data )->run();
		if( $validator->success() )
		{

		}
		else
		{

		}


		/*
		$row['file_name'] = 'image.jpg';
		$row['file_dir'] = 'test';
		$row['logo_id'] = 133;
		$row['id_field'] = 'logo_id';

		//$row = 'test/image.jpg';

		$img = new models\File\Img($row);


		//$img->download();

		//$file->info();

		/*
		$email = new models\Email\Email();


		//$email->test();
		$email->debug();
		$email->setSubject('Testmail');
		$email->setBody('Test met <b>HTML</b>');
		$email->useHtmlTemplate( 'default' );
		//$email->test();
		*/

		$this->smarty->display('index.tpl');
	}

}
