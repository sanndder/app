<?php

use models\boekhouding\MargeGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\Tijdvak;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * UitzenderUren controller
 */
class UitzenderUren extends MY_Controller
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
		$uitzenderGroup = new UitzenderGroup();
		$uitzenders = $uitzenderGroup->all();
		
		$margeGroup = new MargeGroup();
		$urenUitzenders = $margeGroup->getUrenAlleUitzenders();
		
		//52 weken op de x-as
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( date('Y'), 0 );
		$this->smarty->assign( 'x_as', '['.array_keys_to_string($weken_array) .']' );
		
		$this->smarty->assign( 'uitzenders', $uitzenders );
		$this->smarty->assign( 'uitzender_uren', $urenUitzenders );
		$this->smarty->display('overzichten/uitzenderuren/overzicht.tpl');
	}
}
