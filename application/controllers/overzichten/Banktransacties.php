<?php

use models\boekhouding\Transactie;
use models\boekhouding\TransactieGroup;
use models\facturatie\FacturenGroup;
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
		if( $this->user->user_type != 'werkgever' )forbidden();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$transactiesGroup = new TransactieGroup();
		$transactiesGroup->verwerken(); //onverwerkte betalingen verwerken
		
		$this->smarty->assign( 'inleners', inlenerGroup::list() );
		$this->smarty->assign( 'categorien', $transactiesGroup->listCategorien() );
		$this->smarty->display('overzichten/bankbestanden/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemene opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function transactiedetails( $transactie_id )
	{
		$transactie = new Transactie( $transactie_id );
		$response['details'] = $transactie->details();
		$response['facturen'] = NULL;
		$response['facturen'] = $transactie->facturen();
		
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX negeren
	//-----------------------------------------------------------------------------------------------------------------
	public function ignore()
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		$response = $transactie->ignore( $_POST['all'] );
		echo json_encode( $response );
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX link iban aan inlener
	//-----------------------------------------------------------------------------------------------------------------
	public function linkiban()
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		$response = $transactie->linkIban( $_POST['inlener_id'] );
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX link verwijderen
	//-----------------------------------------------------------------------------------------------------------------
	public function unlinkiban()
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		$response = $transactie->unlinkIban( $_POST['inlener_id'] );
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX facturen koppelen
	//-----------------------------------------------------------------------------------------------------------------
	public function koppelfactuur()
	{
		if( !isset($_POST['factuur_id']))
		{
			$response['status'] = 'error';
			$response['error'] = 'Geen facturen om te koppelen';
			echo json_encode( $response );
			die();
		}
		
		$transactie = new Transactie( $_POST['transactie_id'] );
		$response = $transactie->koppelFactuur( $_POST['factuur_id'], $_POST['screentype'], $_POST['bedrag'] );
		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX facturen ontkoppelen
	//-----------------------------------------------------------------------------------------------------------------
	public function ontkoppelfactuur()
	{
		if( !isset($_POST['factuur_id']))
		{
			$response['status'] = 'error';
			$response['error'] = 'Geen facturen om te ontkoppelen';
			echo json_encode( $response );
			die();
		}
		
		$transactie = new Transactie( $_POST['transactie_id'] );
		$response = $transactie->ontkoppelFactuur( $_POST['factuur_id'] );
		echo json_encode( $response );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemene opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function transactiefactoringfactuur( $transactie_id )
	{
		$transactie = new Transactie( $transactie_id );
		$response = $transactie->getFactoringFactuur();
		echo json_encode( $response );
	}

	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemene opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function searchfacturen( )
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		$details = $transactie->details();
		
		$facturen = $transactie->facturen();
		
		$facturenGroup = new FacturenGroup();
		$facturen = $facturenGroup->searchForBankTransacties( $_POST, $facturen );
		
		$response['status'] = 'error';
		
		if( $facturen !== NULL )
		{
			$response['facturen'] = $facturen;
			$response['status'] = 'success';
		}
		
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
	// AJAX algemen opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function setcategorie()
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		if( $transactie->setCategorie( $_POST['categorie_id'] ))
			$response['status'] = 'success';

		else
		{
			$response['status'] = 'error';
			$response['error'] = $transactie->errors();
		}

		echo json_encode( $response );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// AJAX algemen opmerking
	//-----------------------------------------------------------------------------------------------------------------
	public function setrelatie()
	{
		$transactie = new Transactie( $_POST['transactie_id'] );
		if( $transactie->setRelatie( $_POST['type'], $_POST['id'] ))
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
