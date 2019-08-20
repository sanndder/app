<?php
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
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de juiste tijdvakken op
	//-----------------------------------------------------------------------------------------------------------------
	public function listTijdvakken()
	{
		$array = array( 'w' =>'week', '4w' => '4 weken', 'm' => 'maand' );
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de openstaande periodes op
	//-----------------------------------------------------------------------------------------------------------------
	public function listPeriodes()
	{
		if( $_POST['tijdvak'] == 'w' )
			$array = array( 30 => 30, 29 => 28, 28 => 28, 27 => 27, 26 => 26 );
		
		if( $_POST['tijdvak'] == '4w' )
			$array = array( 6 => 'Periode 6', 5 => 'Periode 5', 4 => 'Periode 4' );
		
		if( $_POST['tijdvak'] == 'm' )
			$array = array( 8 => 'augustus', 7 => 'Juli', 6 => 'Juni' );
		
		echo json_encode( $array );
	}
}
