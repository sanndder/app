<?php

use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\verloning\LoonstrokenGroup;
use models\verloning\Loonstrook;
use models\verloning\Urenbriefje;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Urenbriefjes extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonstroken pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function downloadurenbriefje( $werknemer_id = NULL, $tijdvak = NULL, $jaar = NULL, $periode = NULL )
	{
		//beveiligen
		if(	$this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' && $this->user->user_type != 'werknemer' )
			forbidden();
		
		//extra check
		if(	$this->user->user_type == 'werknemer' )
		{
			if( $werknemer_id != $this->werknemer->id )
				forbidden();
		}
		
		//init werknemer object
		$urenbriefje = new Urenbriefje();
		$urenbriefje->werknemer( $werknemer_id )->tijdvak( $tijdvak )->jaar( $jaar )->periode( $periode );
		
		$urenbriefje->inline();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonstroken
	//-----------------------------------------------------------------------------------------------------------------
	public function overzicht()
	{
		//beveiligen
		if(	$this->user->user_type != 'werknemer' )
			forbidden();
		
		$werknemer = new Werknemer( $this->werknemer->werknemer_id );
		
		$loonstrokengroup = new LoonstrokenGroup();
		
		//show($werknemer->gegevens());
		$this->smarty->assign('werknemer', $werknemer->gegevens() );
		$this->smarty->assign( 'loonstroken', $loonstrokengroup->werknemer( $this->werknemer->werknemer_id )->all() );
		$this->smarty->display('werknemer/loonstroken.tpl');
	}

}
