<?php

use models\utils\history;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Ajax controller
 * Voor algemene ajax call
 */
class Ajax extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
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
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// accept algemene voorwaarden
	//-----------------------------------------------------------------------------------------------------------------
	public function acceptAV()
	{
		if( $this->user->user_type == 'uitzender' )
		{
			if( $this->uitzender->acceptAV() )
				echo true;
			else
				echo false;
		}
		if( $this->user->user_type == 'inlener' )
		{
			if( $this->inlener->acceptAV() )
				echo true;
			else
				echo false;
		}
	}

}
