<?php

use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
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
		
		//show($inlenergroup->aanmeldActies());
		
		$this->smarty->assign('uitzenders', $uitzendergroup->new());
		$this->smarty->assign('inlener_acties', $inlenergroup->aanmeldActies() );
		$this->smarty->assign('count_uitzenders', $uitzendergroup->count());
		$this->smarty->assign('inleners', $inlenergroup->new());
		$this->smarty->assign('kredietaanvragen', $kredietgroup->all() );
		$this->smarty->assign('count_inleners', $inlenergroup->count());
		$this->smarty->assign('werknemers', $werknemergroup->new());
		$this->smarty->assign('count_werknemers', $werknemergroup->count());
		$this->smarty->display('dashboard/werkgever.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function uitzender()
	{

		$this->smarty->display('dashboard/uitzender.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// inlener
	//-----------------------------------------------------------------------------------------------------------------
	public function inlener()
	{
		$this->smarty->display('dashboard/inlener.tpl');
	}

}
