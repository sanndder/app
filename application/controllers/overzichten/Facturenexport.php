<?php

use models\documenten\Document;
use models\facturatie\FactoringExport;
use models\facturatie\FactoringExportFactory;
use models\facturatie\FacturenGroup;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Facturenexport extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		if( $this->user->user_type != 'werkgever' )forbidden();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$facturengroep = new FacturenGroup();

		$_GET['factuur_aangekocht'] = 0;
		$_GET['datum_van'] = '2021-01-01';
		$facturen = $facturengroep->filter($_GET)->facturenMatrix();
		
		//exportbestanden
		$factoringExport = FactoringExportFactory::init();
		$exportbestanden = $factoringExport->bestanden( 20 );
		
		
		$this->smarty->assign( 'inleners', InlenerGroup::list() );
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->assign( 'exportbestanden', $exportbestanden );
		
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
	// AJAX upload to factoring done
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
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX export maken
	//-----------------------------------------------------------------------------------------------------------------
	public function export()
	{
		if( !isset($_POST['facturen']) || strlen($_POST['facturen']) == 0 )
			return false;
		
		//explode from comma seperated list
		$factuur_ids = explode(',', substr( $_POST['facturen'], 0, -1 ));
		
		//info van geselecteerde facturen ophalen
		$facturengroep = new FacturenGroup();
		$facturen = $facturengroep->getIDS( $factuur_ids )->facturenMatrix();

		$factoringExport = FactoringExportFactory::init( $facturen );
		if( $factoringExport->exportExcel() )
		{
			$result['status'] = 'success';
			$result['export_id'] = $factoringExport->exportID();
		}	
		else
		{
			$result['status'] = 'error'; json_encode($factoringExport->errors());
			$result['error'] = $factoringExport->errors();
		}
		
		echo json_encode( $result );
		
		return true;
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// download file
	//-----------------------------------------------------------------------------------------------------------------
	public function downloadexport( $file_id = NULL )
	{
		$factoringExport = FactoringExportFactory::init();
		$factoringExport->download( $file_id );
	}
}
