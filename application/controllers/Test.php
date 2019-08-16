<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Test extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//$this->load->model('test2_model', 'test2');
		//$this->load->model('emailcentrum_model', 'emailcentrum');

		//show( $this->user->user_type );


	}

	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function vue()
	{
		$this->smarty->assign('t', time());
		$this->smarty->display('test/vue.tpl');
	}


		//-----------------------------------------------------------------------------------------------------------------
	// Run a series of validation tests
	//-----------------------------------------------------------------------------------------------------------------
	public function validation()
	{

		$validator = new models\Forms\Validator();
		$validator->table( 'test' )->input( $data )->run();

		$this->smarty->display('test/validation.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$pdf = new models\pdf\PdfFactuur();
		$pdf->setHeader()->setFooter()->view();

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

		//$this->smarty->display('index.tpl');
	}

}
