<?php

use models\api\CreditSafe;
use models\cao\CAO;
use models\cao\CAOGroup;
use models\database\Update;
use models\documenten\IDbewijs;
use models\email\Email;
use models\file\File;
use models\file\Img;
use models\file\Pdf;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurDefault;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingGroup;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Database extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		if( $this->user->user_id != 2 )
			die('Geen toegang');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// test ondertekenen
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		
		if( isset($_POST['go']) )
		{
			$database = new Update();
			$database->query();
		}
		
		$this->smarty->display('database.tpl');
	}
	
}
