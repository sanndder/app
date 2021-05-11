<?php

use models\file\File;
use models\utils\Tijdvak;
use models\werknemers\Data;
use models\werknemers\WerknemerGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Margebetalingen extends MY_Controller
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
	public function index()
	{
		$tijdvakhelper = new Tijdvak( 'w' );
		$margebetalingen = new \models\boekhouding\MargeBetalingen();
		
		//sepa maken
		if( isset($_POST['sepa']) && isset($_POST['factuur']) && count($_POST['factuur']) > 0 )
		{
			if( $margebetalingen->generateSepa($_POST['factuur']) )
				$this->smarty->assign('msg', msg('success', 'Sepa aangemaakt') );
			else
				$this->smarty->assign('msg', msg('warning', $margebetalingen->errors()) );
		}
		
		//voldaan maar geen sepa maken
		if( isset($_POST['voldaan']) && isset($_POST['factuur']) && count($_POST['factuur']) > 0 )
		{
			if( $margebetalingen->facturenVoldaan($_POST['factuur']) )
				$this->smarty->assign('msg', msg('success', 'Facturen gemarkeerd als voldaan') );
			else
				$this->smarty->assign('msg', msg('warning', $margebetalingen->errors()) );
		}
		
		$facturen = $margebetalingen->openstaandeMargefacturen();
		$sepas = $margebetalingen->sepaBestanden( 20 );
		
		$this->smarty->assign( 'hide_week', $tijdvakhelper->prev()->prev()->getPeriode() );
		$this->smarty->assign( 'hide_jaar', $tijdvakhelper->getJaar() );

		$this->smarty->assign( 'sepas', $sepas );
		$this->smarty->assign( 'facturen', $facturen );
		$this->smarty->display('overzichten/margebetalingen/overzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function downloadsepa( $file_id = NULL )
	{
		$margebetalingen = new \models\boekhouding\MargeBetalingen();
		$row = $margebetalingen->sepaBestand( $file_id );
		
		$file = new File($row);
		$file->download();
	}
	
}
