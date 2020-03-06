<?php

use models\documenten\DocumentGroup;

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Instellingen controller
 */
class Documenten extends MY_Controller
{
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//Deze pagina mag alleen bezocht worden door werkgever en uitzender
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )
			forbidden();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{

		$documentengroup = new DocumentGroup();
		$documenten = $documentengroup->arbeidscontracten();

		$this->smarty->assign( 'documenten', $documenten );
		$this->smarty->display( 'crm/werknemers/documenten.tpl' );
	}

}
