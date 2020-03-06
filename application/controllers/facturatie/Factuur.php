<?php

use models\documenten\Document;


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Factuur extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// view
	//-----------------------------------------------------------------------------------------------------------------
	public function viewkosten( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		$factuur->kosten()->view();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// view
	//-----------------------------------------------------------------------------------------------------------------
	public function view( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		$factuur->view();
	}

	
}
