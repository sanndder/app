<?php

use models\documenten\DocumentGroup;
use models\inleners\InlenerGroup;
use models\inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\verloning\LoonstrokenZip;
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

		//beveiligen
		if(	 $this->user->user_type != 'werkgever' )
			forbidden();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uploaden
	//-----------------------------------------------------------------------------------------------------------------
	public function uploaden()
	{
		//loonstroken zip's ophalen
		$zip = new LoonstrokenZip();
		
		//del
		if( isset($_GET['del']) )
		{
			$zip->setID( $_GET['del'] );
			$zip->delete();
		}
		
		//verwerken
		if( isset($_GET['verwerk']) )
		{
			$zip->setID( $_GET['verwerk'] );
			$zip->process();
		}
		
		$this->smarty->assign( 'zips', $zip->queue() );
		$this->smarty->display('verloning/loonstroken/uploaden.tpl');
	}
	

}
