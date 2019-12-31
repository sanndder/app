<?php

use models\documenten\Document;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Welkom extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign( 'av', $this->werkgever->AVhtml() );
		$this->smarty->assign( 'werkgever', $this->werkgever->bedrijfsgegevens() );
	}
	
	

	//-----------------------------------------------------------------------------------------------------------------
	// uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{
		//geen onterechte redirect
		if( !$this->uitzender->blockAccess() )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/uitzender'  ,'location' );
		
		//documenten aanmaken voor uitzender
		$documentIDS = $this->uitzender->generateDocuments();
		
		//samenwerkingsovereenkomst ophalen
		$samenwerkingsovereenkomst = new Document( $documentIDS['samenwerkingsovereenkomst'] );
		
		
		$this->smarty->assign( 'samenwerkingsovereenkomst', $samenwerkingsovereenkomst->details() );
		$this->smarty->assign( 'uitzender_id', $this->uitzender->uitzender_id );
		$this->smarty->assign( 'accepted_av', $this->uitzender->acceptedAV() );
		$this->smarty->display('welkom/uitzender.tpl');
	}

}
