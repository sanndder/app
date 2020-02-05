<?php

use models\file\File;
use models\uitzenders\UitzenderGroup;
use models\verloning\Invoer;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ureninvoer extends MY_Controller
{


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// hoofdpagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		//inlener
		if( $this->user->user_type == 'inlener' )
			$this->smarty->assign('inlener_id', $this->inlener->id);
		
		
		$this->smarty->assign('uitzenders', UitzenderGroup::list()); //uitzenders voor werkgever ophalen
		
		if( isset($_GET['dummy']))
			$this->smarty->display('ureninvoer/main_dummy.tpl');
		else
			$this->smarty->display('ureninvoer/main.tpl');
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// bijlage bekijken
	//-----------------------------------------------------------------------------------------------------------------
	public function bijlage( $file_id = NULL )
	{
		$invoer = new Invoer();
		$file_array = $invoer->getBijlage( $file_id );
		
		if( $file_array === NULL )
			die('Geen toegang');
		
		if( $this->user->user_type == 'werknemer' )
			die('Geen toegang');
		
		if( $this->user->user_type == 'uitzender' &&  $file_array['uitzender_id'] != $this->uitzender->id )
			die('Geen toegang');
		
		if( $this->user->user_type == 'inlener' &&  $file_array['inlener_id'] != $this->inlener->id )
			die('Geen toegang');
		
		$file = new File($file_array);
		$file->inline();
		
	}
	
}
