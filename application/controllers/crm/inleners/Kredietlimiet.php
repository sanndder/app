<?php

use models\inleners\InlenerGroup;
use models\inleners\Kredietaanvraag;
use models\uitzenders\UitzenderGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Kredietlimiet extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//check and save
		if( isset($_POST['set']) )
		{
			$kredietaanvraag = new Kredietaanvraag();
			$bedrijfsgegevens = $kredietaanvraag->add();
			
			$errors = $kredietaanvraag->errors();
			
			//status
			if( $errors === false )
			{
				//bestaande uiztender melding tonen
				$this->smarty->assign('success', true);
			}
			else
			{
				$this->smarty->assign( 'bedrijfsgegevens', $bedrijfsgegevens);
				$this->smarty->assign( 'msg', msg( 'warning', $errors ) );
			}
		}
		
		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->display('crm/inleners/kredietlimiet.tpl');
	}


}
