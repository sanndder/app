<?php

use models\Inleners\Inlener;
use models\inleners\InlenerGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ajax extends MY_Controller
{
	private $_uitzender_id = NULL;
	
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//uitzender instellen
		if( $this->user->user_type == 'uitzender' )
			$this->_uitzender_id = $this->uitzender->id;
		
		if( $this->user->user_type == 'werkgever' && isset($_POST['uitzender_id']) )
			$this->_uitzender_id = intval($_POST['uitzender_id']);
		
		//set header voor hele controller
		//header( 'Content-Type: application/json' );
	}

	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de juiste tijdvakken op
	//-----------------------------------------------------------------------------------------------------------------
	public function listTijdvak()
	{
		$inlener = new Inlener( $_POST['inlener_id'] );
		$factuurgegevens = $inlener->factuurgegevens();
		
		if( $factuurgegevens['frequentie'] == 'w')
		{
			$array['tijdvak'] = 'w';
			$array['titel'] = 'week';
			$array['jaren'] = array( 2020 );
			$array['periodes'] = array( 2 => '02',  3=> '03', 4 => '04');
		}
		
		if( $factuurgegevens['frequentie'] == '4w')
		{
			$array['tijdvak'] = '4w';
			$array['titel'] = 'periode';
			$array['jaren'] = array( 2020 );
			$array['periodes'] = array( 1 => '01', 2=> '02');
		}
		
		if( $factuurgegevens['frequentie'] == 'm')
		{
			$array['tijdvak'] = 'm';
			$array['titel'] = 'maand';
			$array['jaren'] = array( 2020 );
			$array['periodes'] = array( 1 => 'januari', 2 => 'februari' );
		}
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de openstaande periodes op
	//-----------------------------------------------------------------------------------------------------------------
	public function listPeriodes()
	{
		
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de inleners op
	//-----------------------------------------------------------------------------------------------------------------
	public function listInleners()
	{
		$inlenerGroup = new InlenerGroup();
		$array['inleners'] = $inlenerGroup->uitzender( $this->_uitzender_id )->listForUreninvoer();
		
		echo json_encode( $array );
	}
}
