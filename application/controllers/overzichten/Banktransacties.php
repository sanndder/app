<?php

use models\boekhouding\Transactie;
use models\boekhouding\TransactieGroup;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Banktransacties extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		
		$this->smarty->display('overzichten/bankbestanden/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemene opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function transactiedetails( $transactie_id )
	{
		$transactie = new Transactie( $transactie_id );		
		$response['details'] = $transactie->details();		
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemene opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function setverwerkt( $transactie_id, $val )
	{
		$transactie = new Transactie( $transactie_id );
		
		$response['status'] = 'error';
		if( $transactie->setVerwerkt( $val ) )
			$response['status'] = 'success';
		
		echo json_encode( $response );
	}

	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemen opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function settransactieopmerking()
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		if( $transactie->setOpmerking( $_POST['opmerking'] ))
			$response['status'] = 'success';
		else
		{
			$response['status'] = 'error';
			$response['error'] = $transactie->errors();
		}
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX lijst van inlener os uitzenders laden
	//-----------------------------------------------------------------------------------------------------------------
	public function listrelaties()
	{
		$response['relaties'] = NULL;
		
		if( $_POST['type'] == 'inlener' )
			$response['relaties'] = InlenerGroup::list();
		
		if( $_POST['type'] == 'uitzender' )
			$response['relaties'] = UitzenderGroup::list();
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX transacties laden
	//-----------------------------------------------------------------------------------------------------------------
	public function gettransacties()
	{
		$transactiesGroup = new TransactieGroup();
		$transactiesGroup->filter( $_POST );
		
		$result['transacties'] = $transactiesGroup->all();
		
		echo json_encode($result);
	}

	
	
}
