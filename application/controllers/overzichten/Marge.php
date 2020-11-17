<?php

use models\boekhouding\MargeGroup;
use models\boekhouding\OmzetGroup;
use models\utils\Tijdvak;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Marge extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		if( $this->user->user_type != 'werkgever' &&  $this->user->user_type != 'uitzender' )forbidden();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$margeGroup = new MargeGroup();
		
		$margeGroup->uitzender( $this->uitzender->id );
		$info_jaren = $margeGroup->infoJaren();

		// set
		if( isset($_GET['set_jaar']))
		{
			$margeGroup->jaar($_GET['set_jaar']);
			$set_jaar = $_GET['set_jaar'];
		}
		
		//set laatste jaar voor default
		if( count($info_jaren) > 0 && !isset($_GET['set_jaar']) )
			$set_jaar = max($info_jaren);
		
		//na het jaar pas de inleners ophalen
		$info_inleners = $margeGroup->infoInleners();
		
		//keuze van de inleners
		if( isset($_GET['set_inleners']))
			$margeGroup->inleners( $_GET['set_inleners'] );
		
		//52 weken op de x-as
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( $set_jaar, 0 );
		$this->smarty->assign( 'x_as', '['.array_keys_to_string($weken_array) .']' );
		
		//data ophalen
		$margeGroup->calcMargeData();
		$margeGroup->calcMargeDataWerknemers();
		$margeGroup->getUrenData();
		
		$margeUitzender = $margeGroup->getMargeDataTotaalUitzender();
		$margeInleners = $margeGroup->getMargeDataInleners();
		$margeWerknemers = $margeGroup->getMargeDataWerknemers();
		$urenUitzender = $margeGroup->getUrenDataTotaalUitzender();
		$urenInleners = $margeGroup->getUrenDataInleners();
		$urenWerknemers = $margeGroup->getUrenDataWerknemers();
		
		$top5MargeInleners = $margeGroup->getTop5MargeInleners();
		
		if( !isset($margeUitzender['weken']) || !is_array($margeUitzender['weken']) ) $margeUitzender['weken'] = [];
		if( !isset($urenUitzender['weken']) || !is_array($urenUitzender['weken']) ) $urenUitzender['weken'] = [];
		
		$this->smarty->assign( 'data_marge', '['.implode(',',$margeUitzender['weken']) .']' );
		$this->smarty->assign( 'data_uren', '['.implode(',',$urenUitzender['weken']) .']' );
		
		$this->smarty->assign( 'top5', $top5MargeInleners );
		
		$this->smarty->assign( 'data_marge_inleners', $margeInleners );
		$this->smarty->assign( 'data_marge_werknemers', $margeWerknemers );
		$this->smarty->assign( 'data_uren_inleners', $urenInleners );
		$this->smarty->assign( 'data_uren_werknemers', $urenWerknemers );
		
		$this->smarty->assign( 'set_jaar', $set_jaar );
		$this->smarty->assign( 'info_jaren', $info_jaren );
		$this->smarty->assign( 'info_inleners', $info_inleners );
		
		$this->smarty->display('overzichten/marge/overzicht.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// json data
	//-----------------------------------------------------------------------------------------------------------------
	public function json()
	{
		$omzetGroup = new OmzetGroup();
		/*
		//$data['omzet'] = array_values($omzetGroup->omzetverkoop());
		$data['omzetuitzenden'] = array_values($omzetGroup->omzetuitzenden());
		$data['loonkosten'] = array_values($omzetGroup->loonkosten());
		$data['winst'] = array_values($omzetGroup->winst());
		$data['winstcum'] = array_values($omzetGroup->winstCum());
		
		header('Content-Type: application/json');

		echo json_encode($data);*/
	}
}
