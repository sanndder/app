<?php

use models\boekhouding\TransactieGroup;
use models\debiteurbeheer\OpenstaandeFacturen;
use models\inleners\InlenerFinancien;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Inlener extends MY_Controller
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
	public function index( $inlener_id = NULL  )
	{
		$financien = new InlenerFinancien($inlener_id);

		$this->smarty->assign( 'emailadressen', $financien->emailadressen() );
		$this->smarty->assign( 'inlener', $financien->bedrijfsgegevens() );
		$this->smarty->assign( 'inlener_id', $inlener_id );
		$this->smarty->display('debiteurbeheer/inlener/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// betalingen
	//-----------------------------------------------------------------------------------------------------------------
	public function betalingen( $inlener_id = NULL  )
	{
		$financien = new InlenerFinancien($inlener_id);
		
		$transactieGroep = new TransactieGroup();
		
		$transacties = $transactieGroep->inlener( $inlener_id )->all();
		show($transacties);
		
		$this->smarty->assign( 'emailadressen', $financien->emailadressen() );
		$this->smarty->assign( 'inlener', $financien->bedrijfsgegevens() );
		$this->smarty->assign( 'inlener_id', $inlener_id );
		$this->smarty->display('debiteurbeheer/inlener/betalingen.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// facturen
	//-----------------------------------------------------------------------------------------------------------------
	public function facturen( $inlener_id = NULL  )
	{
		$financien = new InlenerFinancien($inlener_id);
		
		$this->smarty->assign( 'emailadressen', $financien->emailadressen() );
		$this->smarty->assign( 'inlener', $financien->bedrijfsgegevens() );
		$this->smarty->assign( 'inlener_id', $inlener_id );
		$this->smarty->display('debiteurbeheer/inlener/betalingen.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// notities
	//-----------------------------------------------------------------------------------------------------------------
	public function notities( $inlener_id = NULL  )
	{
		$financien = new InlenerFinancien($inlener_id);
		
		$this->smarty->assign( 'emailadressen', $financien->emailadressen() );
		$this->smarty->assign( 'inlener', $financien->bedrijfsgegevens() );
		$this->smarty->assign( 'inlener_id', $inlener_id );
		$this->smarty->display('debiteurbeheer/inlener/notities.tpl');
	}
}
