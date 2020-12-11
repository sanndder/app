<?php

use models\documenten\IDbewijs;
use models\prospects\Prospect;
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
	// veld opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function set( $id )
	{
		$prospect = new Prospect($id);
		if( $prospect->set($_POST['name'], $_POST['value']) )
			$response['status'] = 'success';
		else
			$response['status'] = 'error';
		
		echo json_encode($response);
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// taak aanpassen
	//-----------------------------------------------------------------------------------------------------------------
	public function toggletaak($id)
	{
		$prospect = new Prospect($id);
		
		if( $prospect->toggleTaak($_POST['taak_id'], $_POST['state']) )
			$response['status'] = 'success';
		else
			$response['status'] = 'error';
		
		echo json_encode($response);
	}
}
