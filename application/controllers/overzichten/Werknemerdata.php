<?php

use models\werknemers\Data;
use models\werknemers\WerknemerGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Werknemerdata extends MY_Controller
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
		$werknemerGroup = new WerknemerGroup();
		
		$werknemerdata = new Data();
		
		$this->smarty->assign( 'werknemers', $werknemerdata->werknemers() );
		$this->smarty->display('overzichten/werknemerdata/overzicht.tpl');
	}
	
	
}
