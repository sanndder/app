<?php

use models\facturatie\FactuurBetaling;

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

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
		if( strlen( $this->agent->referrer() ) == 0 )
			$this->session->set_userdata( 'ref', NULL );
		
		//ref opslaan
		if( strlen( $this->agent->referrer() ) > 0 && strpos( $this->agent->referrer(), uri_string() ) === false )
			$this->session->set_userdata( 'ref', $this->agent->referrer() );
		
		$factuur = new \models\facturatie\Factuur( $factuur_id );
		
		$betaling = new FactuurBetaling();
		
		//betaling toevoegen
		if( isset( $_POST['add_betaling'] ) )
		{
			$betaling->bedrag( $_POST['bedrag'] )->categorie( $_POST['categorie_id'] )->datum( $_POST['datum'] );
			
			if( $betaling->valid() )
			{
				if( $factuur->addBetaling( $betaling ) )
				{
					$this->session->set_flashdata( 'msg', 'Betaling is toegevoegd' );
					redirect( $this->config->item( 'base_url' ) . 'facturatie/factuur/details/' . $factuur_id, 'location' );
				}
				else
					$this->smarty->assign( 'msg', msg( 'warning', $factuur->errors() ) );
			} else
				$this->smarty->assign( 'msg', msg( 'warning', $betaling->errors() ) );
		}
		
		//betaling verwijderen
		if( isset( $_GET['delbetaling'] ) )
		{
			if( $factuur->delBetaling( $_GET['delbetaling'] ) )
			{
				$this->session->set_flashdata( 'msg', 'Betaling verwijderd' );
				redirect( $this->config->item( 'base_url' ) . 'facturatie/factuur/details/' . $factuur_id, 'location' );
			} else
				$this->smarty->assign( 'msg', msg( 'warning', $factuur->errors() ) );
			
		}
		
		if( isset( $_GET['s'] ) )
			show( $factuur->details() );
		
		//msg
		if( $this->session->flashdata( 'msg' ) !== NULL )
			$this->smarty->assign( 'msg', msg( 'success', $this->session->flashdata( 'msg' ) ) );
		
		$this->smarty->assign( 'factuur_id', $factuur_id );
		$this->smarty->assign( 'factuur', $factuur->details() );
		$this->smarty->assign( 'betalingen', $factuur->betalingen( true ) );
		$this->smarty->assign( 'betaald_vrij', $factuur->betaaldVrij() );
		$this->smarty->assign( 'betaald_g', $factuur->betaaldG() );
		$this->smarty->assign( 'betaling_categorien', $betaling->categorien() );
		$this->smarty->assign( 'marge_id', $factuur->getMargeFactuurID() );
		$this->smarty->assign( 'invoer_bijlages', $factuur->getInvoerBijlages() );
		$this->smarty->assign( 'extra_bijlages', $factuur->getExtraBijlages() );
		$this->smarty->assign( 'ref', $this->session->ref );
		$this->smarty->display( 'facturatie/factuurdetails.tpl' );
		
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
