<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Image extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//--------------------------------------------------------------------------
	// url naar logo uitzender
	//--------------------------------------------------------------------------
	public function logouitzender( $uitzender_id = '' )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );
		$file = $uitzender->logo();

		header('Content-type: image/jpeg');
		readfile($file);

		die();
	}

}
