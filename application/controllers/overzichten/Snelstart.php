<?php


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Snelstart extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$boekhouding = new \models\boekhouding\Snelstart();
		
		//settings opslaan
		if( isset($_POST['set_settings']))
		{
			$boekhouding->setSettings( $_POST );
			$errors = $boekhouding->errors();
			
			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Instellingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', $errors ));
		}
		
		//nieuwe exportlijst maken
		if( isset($_POST['go']))
		{
			$id = $boekhouding->exportBoekingen( $_POST['datum'] );
			
			$errors = $boekhouding->errors();
			
			//msg
			if( $errors === false )
			{
				$this->session->set_flashdata('msg', 'Exportbestand is aangemaakt' );
					vshow($id);//redirect( $this->config->item( 'base_url' ) . 'overzichten/snelstart/index?export_id=' . $id, 'location' );
			}
			else
				$this->smarty->assign('msg', msg('warning', $errors ));
		}
		
		//redirect msg
		if( $this->session->flashdata('msg') !== NULL )
			$this->smarty->assign( 'msg', msg( 'success', $this->session->flashdata('msg') ) );
		
		//datum maken
		$date = new DateTime();
		$this->smarty->assign( 'yesterday', $date->add(DateInterval::createFromDateString('yesterday'))->format('Y-m-d') );
		
		$this->smarty->assign( 'settings', $boekhouding->settings() );
		$this->smarty->display('overzichten/snelstart/overzicht.tpl');
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// download lijst debiteuren of crediteuren
	//-----------------------------------------------------------------------------------------------------------------
	public function lijst( $type )
	{
		$boekhouding = new \models\boekhouding\Snelstart();
		
		$boekhouding->downloadRelaties( $type );
	}
	
	
}
