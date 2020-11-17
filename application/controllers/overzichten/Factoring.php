<?php

use models\facturatie\FactoringFactuur;
use models\facturatie\FactoringGroup;
use models\facturatie\FacturenGroup;
use models\inleners\Inlener;
use Smalot\PdfParser\Parser;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Factoring extends MY_Controller
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
	public function index( $factuur_id = NULL )
	{
		$factoringGroup = new FactoringGroup();
		$factoringGroup->filter( $_GET );
		
		$factuurdetails = NULL;
		$factuurregels = NULL;
		$regeltotaal = 0;
		
		if( $factuur_id !== NULL )
		{
			$factuur = new FactoringFactuur( $factuur_id );
			
			$factuur->_checkFactuurComplete();
			
			$factuurdetails = $factuur->details();
			$factuur->regels();
			$regeltotaal = $factuur->regelTotaal();
			
			if( isset($_GET['delete']) )
			{
				$factuur->delete();
				redirect( $this->config->item( 'base_url' ) . 'overzichten/factoring/index?' . $factoringGroup->filterQuery() ,'location' );
			}
		}
		
		//TEMP betalingen koppelen
		//$factoringGroup->koppelBetalingen();
		
		//show( $factoringGroup->filterQuery() );
		//show($factuur->details());
		
		$this->smarty->assign( 'factuur_id', $factuur_id );
		$this->smarty->assign( 'factuur', $factuurdetails );
		$this->smarty->assign( 'regeltotaal', $regeltotaal );
		$this->smarty->assign( 'filter', $factoringGroup->filterQuery() );
		$this->smarty->assign( 'bestanden', $factoringGroup->all() );
		$this->smarty->assign( 'aantal', $factoringGroup->count() );
		$this->smarty->display('overzichten/factoring/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX regels ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function getregels( $factuur_id )
	{
		$factuur = new FactoringFactuur( $factuur_id );
		$result['regels'] = $factuur->regels();
		$result['regeltotaal'] = $factuur->regelTotaal();
		
		echo json_encode( $result );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX add regel
	//-----------------------------------------------------------------------------------------------------------------
	public function addregel()
	{
		$factuur = new FactoringFactuur( $_POST['factuur_id'] );
		if( $factuur->addRegel($_POST) )
		{
			$result['status'] = 'success';
			$result['regel_id'] = $factuur->insert_id();
			$result['factuur_compleet'] = $factuur->factuurCompleet();
		}
		else
		{
			$result['status'] = 'error';
			$result['error'] = $factuur->errors();
		}
		
		echo json_encode( $result );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX del regel
	//-----------------------------------------------------------------------------------------------------------------
	public function delregel()
	{
		$factuur = new FactoringFactuur( $_POST['factuur_id'] );
		if( $factuur->delRegel($_POST['regel_id']) )
			$result['status'] = 'success';
		else
			$result['status'] = 'error';
		
		echo json_encode( $result );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX set datum
	//-----------------------------------------------------------------------------------------------------------------
	public function searchinlener()
	{
		$factuur = new \models\facturatie\Factuur();
		$factuur->setFactuurNr( $_POST['nr'] ) ;
		
		if( $factuur->factuurID() === NULL )
		{
			echo json_encode( array( 'omschrijving' => '' ) );
			return false;
		}
		
		$details = $factuur->details();
		
		//factor bedrag uitrekenen
		$response['bedrag'] = FactoringFactuur::calcFactoringBedrag( $details );
		$response['eind'] = FactoringFactuur::calcFactoringEindafrekening( $details );
		
		$response['omschrijving'] = Inlener::bedrijfsnaam( $details['inlener_id']);
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX set datum
	//-----------------------------------------------------------------------------------------------------------------
	public function setdatum( $factuur_id = NULL )
	{
		$factuur = new FactoringFactuur( $factuur_id );
		
		$factuur->setDatum( $_POST['datum'] );
		
		if( $factuur->errors() !== false )
		{
			$result['status'] = 'error';
			$result['error'] = $factuur->errors();
		}
		else
			$result['status'] = 'success';
		
		echo json_encode( $result );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX set type
	//-----------------------------------------------------------------------------------------------------------------
	public function settype( $factuur_id = NULL )
	{
		$factuur = new FactoringFactuur( $factuur_id );
		
		$factuur->setType( $_POST['type'] );
		
		if( $factuur->errors() !== false )
		{
			$result['status'] = 'error';
			$result['error'] = $factuur->errors();
		}
		else
			$result['status'] = 'success';
		
		echo json_encode( $result );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX set datum
	//-----------------------------------------------------------------------------------------------------------------
	public function settotaalbedrag( $factuur_id = NULL )
	{
		$factuur = new FactoringFactuur( $factuur_id );
		
		$factuur->setTotaalbedrag( $_POST['totaal'] );
		
		if( $factuur->errors() !== false )
		{
			$result['status'] = 'error';
			$result['error'] = $factuur->errors();
		}
		else
			$result['status'] = 'success';
		
		echo json_encode( $result );
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// download inline
	//-----------------------------------------------------------------------------------------------------------------
	public function view( $factuur_id = NULL )
	{
		$factuur = new FactoringFactuur( $factuur_id );
		
		$pdf = new \models\file\Pdf( $factuur->details() );
		$pdf->inline();
	}
	
}
