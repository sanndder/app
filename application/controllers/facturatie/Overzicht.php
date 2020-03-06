<?php

use models\documenten\Document;
use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\documenten\TemplateGroup;
use models\facturatie\FacturenGroup;
use models\forms\Formbuilder;
use models\instellingen\Minimumloon;
use models\instellingen\Feestdagen;
use models\verloning\Urentypes;
use models\verloning\Vergoeding;
use models\verloning\VergoedingGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Overzicht extends MY_Controller
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
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$facturengroep = new FacturenGroup();
		$facturen = $facturengroep->facturenMatrix();
		
		$this->smarty->assign( 'facturen', $facturen );
		
		$this->smarty->display('facturatie/overzicht.tpl');
	}

	
}
