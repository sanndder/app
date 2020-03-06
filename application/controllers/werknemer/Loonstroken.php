<?php

use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\verloning\LoonstrokenGroup;
use models\verloning\Loonstrook;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;
use models\zzp\ZzpGroup;


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Loonstroken extends MY_Controller {


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
	public function downloadloonstrook( $werknemer_id = NULL, $loonstrook_id = NULL )
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
		$loonstrook = new Loonstrook( $loonstrook_id );
		
		$details = $loonstrook->details();
		
		//controle
		if( $details['werknemer_id'] != $werknemer_id)
			forbidden();
		
		$file = new \models\file\Pdf($details);
		$file->inline();
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
