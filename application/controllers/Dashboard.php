<?php

use models\boekhouding\MargeGroup;
use models\boekhouding\OmzetGroup;
use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\Tijdvak;
use models\verloning\LoonstrokenGroup;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Dashboard extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//beveiligen
		if(	$this->uri->segment(2) != $this->user->user_type )
			redirect( $this->config->item( 'base_url' ) . 'dashboard/' . $this->user->user_type  ,'location' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werkgever
	//-----------------------------------------------------------------------------------------------------------------
	public function werkgever()
	{
		
		$uitzendergroup = new UitzenderGroup();
		$inlenergroup = new InlenerGroup();
		$werknemergroup = new WerknemerGroup();
		$zzpgroup = new ZzpGroup();
		$kredietgroup = new KredietaanvraagGroup();
		$documentengroup = new DocumentGroup();
		
		$documentengroup->arbeidscontracten(); //documenten laden om flags te tellen
		
		$omzet = new OmzetGroup();
		
		//show($inlenergroup->aanmeldActies());
		
		$this->smarty->assign('totaalOmzet', $omzet->totaal() );
		$this->smarty->assign('jaar', date('Y') );
		$this->smarty->assign('uitzenders', $uitzendergroup->new());
		$this->smarty->assign('inlener_acties', $inlenergroup->aanmeldActies() );
		$this->smarty->assign('count_uitzenders', $uitzendergroup->count());
		$this->smarty->assign('inleners', $inlenergroup->new());
		$this->smarty->assign('kredietaanvragen', $kredietgroup->all() );
		$this->smarty->assign('count_inleners', $inlenergroup->count());
		$this->smarty->assign('werknemers', $werknemergroup->new());
		$this->smarty->assign('count_werknemers', $werknemergroup->count());
		$this->smarty->assign('count_zzp', $zzpgroup->count());
		$this->smarty->assign('documenten_werknemers_flags', $documentengroup->flags() );
		$this->smarty->display('dashboard/werkgever.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{
		$inlenergroup = new InlenerGroup();
		$werknemergroup = new WerknemerGroup();
		$zzpgroup = new ZzpGroup();

		//margegegevens
		$margeGroup = new MargeGroup();
		
		$margeGroup->uitzender( $this->uitzender->id );
		$info_jaren = $margeGroup->infoJaren();
		$set_jaar = max($info_jaren);
		$margeGroup->jaar( $set_jaar );
		$info_inleners = $margeGroup->infoInleners();
		
		//52 weken op de x-as
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( $set_jaar, 0 );
		$this->smarty->assign( 'x_as', '['.array_keys_to_string($weken_array) .']' );
		
		//data ophalen
		$margeGroup->calcMargeData();
		$margeGroup->getUrenData();
		
		$margeUitzender = $margeGroup->getMargeDataTotaalUitzender();
		$urenUitzender = $margeGroup->getUrenDataTotaalUitzender();
		$top5MargeInleners = $margeGroup->getTop5MargeInleners();

		if(!isset($margeUitzender['weken']))
			$margeUitzender['weken'] = array();
		if(!isset($urenUitzender['weken']))
			$urenUitzender['weken'] = array();
		
		$this->smarty->assign( 'data_marge', '['.implode(',',$margeUitzender['weken']) .']' );
		$this->smarty->assign( 'data_uren', '['.implode(',',$urenUitzender['weken']) .']' );
		
		$this->smarty->assign( 'top5', $top5MargeInleners );
		$this->smarty->assign( 'set_jaar', $set_jaar );
		
		$this->smarty->assign('count_inleners', $inlenergroup->count());
		$this->smarty->assign('count_werknemers', $werknemergroup->count());
		$this->smarty->assign('count_zzp', $zzpgroup->count());

		$this->smarty->display('dashboard/uitzender.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// inlener
	//-----------------------------------------------------------------------------------------------------------------
	public function inlener()
	{
		$this->smarty->display('dashboard/inlener.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// werknemer TODO: laatste 10 loonstroken
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemer()
	{
		$werknemer = new Werknemer( $this->werknemer->werknemer_id );
		
		$loonstrokengroup = new LoonstrokenGroup();
		$reserveringen = new \models\verloning\Reserveringen();
		
		//show($werknemer->gegevens());
		$this->smarty->assign('werknemer', $werknemer->gegevens() );
		$this->smarty->assign( 'stand', $reserveringen->werknemer( $this->werknemer->werknemer_id )->stand() );
		$this->smarty->assign( 'loonstroken', $loonstrokengroup->werknemer( $this->werknemer->werknemer_id )->all() );
		$this->smarty->display('dashboard/werknemer.tpl');
	}

}
