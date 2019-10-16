<?php

use models\forms\Formbuilder;
use models\utils\history;

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
		if( $this->user->user_type != 'werkgever' )forbidden();


	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// get history
	//-----------------------------------------------------------------------------------------------------------------
	public function gethistory( $table, $field, $index )
	{
		$log = new History();
		$data = $log->table( $table )->index( array($field => $index ) )->data();
		
		if( is_array($data) )
			echo json_encode($data);
	}

}
