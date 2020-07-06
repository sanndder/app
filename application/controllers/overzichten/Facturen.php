<?php

use models\documenten\Document;
use models\facturatie\FacturenGroup;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Facturen extends MY_Controller
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
		$facturengroep = new FacturenGroup();
		
		$facturen = $facturengroep->filter($_GET)->facturenMatrix();
		
		$this->smarty->assign( 'inleners', InlenerGroup::list() );
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign( 'facturen', $facturen );
		
		$this->smarty->display('overzichten/facturen/overzicht.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX details
	//-----------------------------------------------------------------------------------------------------------------
	public function factuurdetails( $factuur_id )
	{
		$response['status'] = 'error';
		
		$factuur = new \models\facturatie\Factuur($factuur_id);
		
		$response['details'] = $factuur->details();
		$response['betalingen'] = $factuur->betalingen();
		
		$response['details']['factuur_datum'] = reverseDate($response['details']['factuur_datum']);
		$response['details']['verval_datum'] = reverseDate($response['details']['verval_datum']);
		
		if( $factuur->errors() === false )
			$response['status'] = 'success';
		
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX upload done
	//-----------------------------------------------------------------------------------------------------------------
	public function factuuruploaded( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur($factuur_id);
		
		if( $factuur->setUploaded() )
			$response['status'] = 'success';
		else
			$response['status'] = 'error';
		
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX betaling toevoegen
	//-----------------------------------------------------------------------------------------------------------------
	public function addbetaling( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur($factuur_id);
		
		if( $factuur->addBetaling( $_POST ) )
			$response['status'] = 'success';
		else
			$response['status'] = 'error';
		
		echo json_encode( $response );
	}

}
