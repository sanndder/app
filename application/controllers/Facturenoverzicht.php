<?php

use models\boekhouding\MargeGroup;
use models\facturatie\FacturenGroup;
use models\pdf\PdfMargeWeekoverzicht;

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Account en userbeheer
 */
class Facturenoverzicht extends MY_Controller
{
	
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen inlener
	//-----------------------------------------------------------------------------------------------------------------
	public function inlener()
	{
		//beveiligen
		if( $this->uri->segment( 2 ) != $this->user->user_type )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type, 'location' );
		
		$facturengroep = new FacturenGroup();
		$facturen = $facturengroep->setInlener( $this->inlener->id )->facturenMatrix();
		
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display( 'facturenoverzicht/inlener.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen en marge uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{
		//beveiligen
		if( $this->uri->segment( 2 ) != $this->user->user_type )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type, 'location' );
		
		$facturengroep = new FacturenGroup();
		$facturen = $facturengroep->setUitzender( $this->uitzender->uitzender_id )->facturenMatrix();
		
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display( 'facturenoverzicht/uitzender.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen en marge uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function weekoverzicht( $tijdvak = NULL, $jaar = NULL, $periode = NULL )
	{
		//beveiligen
		if( $this->user->user_type != 'uitzender' )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type, 'location' );
		
		$margeGroup = new MargeGroup();
		$margeGroup->uitzender( $this->uitzender->id )->tijdvak( $tijdvak )->jaar( $jaar )->periode( $periode );
		
		$weekoverzicht = $margeGroup->weekoverzicht();
		if( $weekoverzicht !== NULL )
		{
			$pdf = new PdfMargeWeekoverzicht();
			$pdf->setHeader( $tijdvak, $jaar, $periode )->setFooter()->setBody( $weekoverzicht );
			
			$pdf->preview();
		}
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen in de wachtrij
	//-----------------------------------------------------------------------------------------------------------------
	public function wachtrij( $factuur_id = NULL )
	{
		//beveiligen
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )
			forbidden();
		
		$facturengroep = new FacturenGroup();
		
		//bij uitzender
		if( $this->user->user_type == 'uitzender' )
			$facturengroep->setUitzender( $this->uitzender->uitzender_id );
		
		$facturen = $facturengroep->setWachtrij( 1 )->facturenMatrix();
		//factuurdetails laden
		if( $factuur_id !== NULL )
		{
			$factuur = new \models\facturatie\Factuur( $factuur_id );
			$details = $factuur->details();
			
			$this->smarty->assign( 'details', $details );
			$this->smarty->assign( 'invoer_bijlages', $factuur->getInvoerBijlages() );
			$this->smarty->assign( 'extra_bijlages', $factuur->getExtraBijlages() );
			
			//beveilig factuur
			if( $this->user->user_type == 'uitzender' && $details['uitzender_id'] != $this->uitzender->uitzender_id )
				forbidden();
			
			//status aanpassen
			if( isset( $_GET['status'] ) )
			{
				if( $factuur->setWachtrijstatus( $_GET['status'] ) )
				{
					$this->session->set_flashdata( 'update', true );
					redirect( $this->config->item( 'base_url' ) . 'facturenoverzicht/wachtrij/' . $factuur_id . '/#' . $factuur_id, 'location' );
				}
			}
			
			//project aanpassen
			if( isset( $_POST['project'] ) )
			{
				if( $factuur->setWachtrijProject( $_POST['project'] ) )
				{
					$this->session->set_flashdata( 'project', true );
					redirect( $this->config->item( 'base_url' ) . 'facturenoverzicht/wachtrij/' . $factuur_id . '/#' . $factuur_id, 'location' );
				}
			}
			
			//update gedaan?
			if( $this->session->flashdata( 'update' ) !== NULL )
			{
				if( $details['wachtrij_akkoord'] == 1 )
					$this->smarty->assign( 'msg', msg( 'success', 'Factuur staat klaar voor verzenden' ) );
				else
					$this->smarty->assign( 'msg', msg( 'warning', 'Verzenden geannuleerd' ) );
			}
			
			//project aangepast
			if( $this->session->flashdata( 'project' ) !== NULL )
			{
				if(  $this->session->flashdata( 'project' ) === true )
					$this->smarty->assign( 'msg', msg( 'success', 'Projectnummer/-naam toegevoegd aan factuur' ) );
				else
					$this->smarty->assign( 'msg', msg( 'warning', 'Fout bij toevoegen projectnummer/-naam' ) );
			}
			
		}
		
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->assign( 'factuur_id', $factuur_id );
		$this->smarty->display( 'facturenoverzicht/wachtrij.tpl' );
	}
	
}
