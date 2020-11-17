<?php

use models\documenten\IDbewijs;
use models\verloning\Urentypes;
use models\werknemers\Plaatsing;
use models\werknemers\Werknemer;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ajax extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )forbidden();

	}


	//-----------------------------------------------------------------------------------------------------------------
	// verkooptarief wijzigen
	//-----------------------------------------------------------------------------------------------------------------
	public function setverkooptarief()
	{
	
	}

	
}
