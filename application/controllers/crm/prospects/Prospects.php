<?php

use models\prospects\Prospect;
use models\prospects\ProspectGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Prospects extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

	}


	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//toevoegen
		if( isset($_POST['new']) )
		{
			$prospect = new Prospect();
			if( $prospect->validate() )
			{
				if( $prospect->new() )
					redirect( $this->config->item( 'base_url' ) . 'crm/prospects/prospects/details/' . $prospect->insertID()  ,'location' );
				else
					$this->smarty->assign( 'msg', msg( 'warning', 'database error' ) );
			}
			else
				$this->smarty->assign( 'msg', msg( 'warning', $prospect->errors() ) );
		}
		
		
		$prospectGroup = new ProspectGroup();
		
		$this->smarty->assign('prospects', $prospectGroup->all() );
		$this->smarty->display('crm/prospects/overzicht.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// details
	//-----------------------------------------------------------------------------------------------------------------
	public function details( $id = NULL )
	{
		$prospect = new Prospect($id);
		
		//notitie toevoegen
		if( isset($_POST['notitie_opslaan']) )
		{
			if( $prospect->addNotitie() )
				redirect( $this->config->item( 'base_url' ) . 'crm/prospects/prospects/details/' . $id ,'location' );
		}
		
		$this->smarty->assign('prospect', $prospect->details() );
		$this->smarty->assign('notities', $prospect->notities() );
		$this->smarty->display('crm/prospects/details.tpl');
	}

	
}
