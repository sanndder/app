<?php


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Factuur extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// detailpagina
	//-----------------------------------------------------------------------------------------------------------------
	public function details( $factuur_id )
	{
		//reset ref
		if( strlen($this->agent->referrer()) == 0 )
			$this->session->set_userdata('ref', NULL);
		
		//ref opslaan
		if( strlen($this->agent->referrer()) > 0 && strpos( $this->agent->referrer() , uri_string()) === false )
			$this->session->set_userdata('ref', $this->agent->referrer());
		
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		
		if(isset($_GET['s']))
			show($factuur->details());
		
		$this->smarty->assign( 'factuur_id', $factuur_id );
		$this->smarty->assign( 'factuur', $factuur->details() );
		$this->smarty->assign( 'marge_id', $factuur->getMargeFactuurID() );
		$this->smarty->assign( 'invoer_bijlages', $factuur->getInvoerBijlages() );
		$this->smarty->assign( 'extra_bijlages', $factuur->getExtraBijlages() );
		$this->smarty->assign( 'ref', $this->session->ref );
		$this->smarty->display('facturatie/factuurdetails.tpl');
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// view
	//-----------------------------------------------------------------------------------------------------------------
	public function viewkosten( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		$factuur->kosten()->view();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// view
	//-----------------------------------------------------------------------------------------------------------------
	public function view( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		$factuur->view();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// view
	//-----------------------------------------------------------------------------------------------------------------
	public function download( $factuur_id )
	{
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		$factuur->download();
	}

	
}
