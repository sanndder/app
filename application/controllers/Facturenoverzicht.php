<?php

use models\facturatie\FacturenGroup;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Facturenoverzicht extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//beveiligen
		if(	$this->uri->segment(2) != $this->user->user_type )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type  ,'location' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen inlener
	//-----------------------------------------------------------------------------------------------------------------
	public function inlener()
	{
		
		
		$facturengroep = new FacturenGroup();
		$facturen = $facturengroep->setInlener( $this->inlener->id )->facturenMatrix();
		
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display('facturenoverzicht/inlener.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Facturen en marge uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{
		
		
		
		$facturengroep = new FacturenGroup();
		$facturen = $facturengroep->setUitzender( $this->uitzender->uitzender_id )->facturenMatrix();
		
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display('facturenoverzicht/uitzender.tpl');
	}

}
