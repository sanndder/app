<?php

use models\documenten\Document;
use models\file\File;
use models\users\User;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Teken document
 */

class Bedrijfsinformatie extends EX_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//uitloggen om fouten te voorkomen
		if( isset($_SESSION['logindata']['main']['user_id']) )
		{
			$this->auth->logout( $_SESSION['logindata']['main']['user_id'], $_SESSION['logindata']['main']['sid'], 'sign', false);
			redirect(  current_url() .'?'. $_SERVER['QUERY_STRING']  ,'location' );
		}
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// bedrijfsinformatie
	//-----------------------------------------------------------------------------------------------------------------
	public function index( $bedrijfsnaam = NULL )
	{
		$this->load->model( 'super_model', 'super' );
		
		$default = $this->super->defaultBedrijf();
		if( $default !== NULL )
			$_GET['wid'] = $default['wid'];
		
		$this->load->model( 'werkgever_model', 'werkgever');
		
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		$documenten = $this->werkgever->documenten();

		$this->smarty->assign( 'date', date('Y-m-d'));
		$this->smarty->assign( 'wid', $_GET['wid']);
		$this->smarty->assign( 'documenten', $documenten);
		$this->smarty->assign( 'bedrijfsgegevens', $bedrijfsgegevens);
		$this->smarty->display('bedrijfsinformatie.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// download/view
	//-----------------------------------------------------------------------------------------------------------------
	public function document( $mode = 'v', $file_id = NULL )
	{
		$this->load->model( 'super_model', 'super' );
		$this->load->model( 'werkgever_model', 'werkgever');
		$this->load->model( 'user_model', 'user');
		
		$file_info = $this->werkgever->document($file_id);
		if( $file_info === NULL )
			die('Bestand niet gevonden');

		$file = new File($file_info);
		
		if( $mode == 'v' )
			$file->inline();
		else
			$file->download();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// download/view
	//-----------------------------------------------------------------------------------------------------------------
	public function av( $mode = 'v', $file_id = NULL )
	{
		$this->load->model( 'super_model', 'super' );
		$this->load->model( 'werkgever_model', 'werkgever');
		$this->load->model( 'user_model', 'user');
		
		if( $mode == 'v' )
			$this->werkgever->AVpdf();
		else
			$this->werkgever->AVpdf( 'download' );
	}
}
