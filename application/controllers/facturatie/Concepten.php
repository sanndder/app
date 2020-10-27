<?php

use models\facturatie\FactuurCorrectie;
use models\facturatie\FactuurFactory;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Concepten extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//alleen voor werkever
		if(	$this->user->user_type != 'werkgever' )forbidden();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// TEMP
	//-----------------------------------------------------------------------------------------------------------------
	public function database( $factuur_id = NULL )
	{
		$factuur = new FactuurFactory();
		$factuur->runCustom($factuur_id);
	}


	//-----------------------------------------------------------------------------------------------------------------
	// marge factuur maken
	//-----------------------------------------------------------------------------------------------------------------
	public function marge( $factuur_id = NULL )
	{
		$concept = new FactuurCorrectie( $factuur_id );
		
		//opslaan
		if( isset($_POST['opslaan']) )
		{
			if( $concept->set( $_POST ) )
			{
				$this->session->set_flashdata('msg', 'Gegevens zijn opgeslagen' );
				redirect( $this->config->item( 'base_url' ) . 'facturatie/concepten/marge/' . $concept->ID()  ,'location' );
			}
			else
				$this->smarty->assign( 'msg', msg( 'danger', $concept->errors() ));
		}
		
		//regel verwijderen
		if( isset($_GET['delregel']) )
		{
			if( $concept->deleteRegel( $_GET['delregel'] ) )
			{
				$this->session->set_flashdata('msg', 'Regel verwijderd' );
				redirect( $this->config->item( 'base_url' ) . 'facturatie/concepten/marge/' . $concept->ID()  ,'location' );
			}
			else
				$this->smarty->assign( 'msg', msg( 'danger', $concept->errors() ));
		}

		$factuur = $concept->details();
		$regels = $concept->regels();
		
		
		//factuur maken
		if( isset($_POST['genereren']) )
		{
			$factuurfactory = new FactuurFactory();
			$factuurfactory->runCustom( $factuur['factuur_id'] );
		}
		
		//msg
		if( $this->session->flashdata('msg') !== NULL )
			$this->smarty->assign( 'msg', msg( 'success', $this->session->flashdata('msg') ) );

		//vshow($regels);

		$this->smarty->assign( 'factuur', $factuur );
		$this->smarty->assign( 'regels', $regels );
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign( 'inleners', InlenerGroup::list( $factuur['uitzender_id'] ) );
		$this->smarty->display('facturatie/concepten/marge.tpl');
	}

	
}
