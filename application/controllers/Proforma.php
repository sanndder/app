<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Proforma extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		if(	$this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender'  )forbidden();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Proforma pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$proforma = new \models\verloning\Proforma();
		
		if( isset($_POST['bereken']) )
		{
			$proforma->settings( $_POST );
			if( $proforma->errors() === false)
			{
				$result = $proforma->loon();
				$this->smarty->assign( 'result', $result );
			}
			else
			{
				$this->smarty->assign( 'msg', msg( 'warning', $proforma->errors() ) );
			}
			
		}
		

		if(isset($_GET['s']))
		show($_POST);
		
		$this->smarty->display('proforma/overzicht.tpl');
	}

}
