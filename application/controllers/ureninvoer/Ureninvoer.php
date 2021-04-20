<?php

use models\facturatie\Factuur;
use models\facturatie\FactuurFactory;
use models\file\File;
use models\pdf\PdfFactuurDefault;
use models\uitzenders\UitzenderGroup;
use models\verloning\ETregeling;
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
		{
			$this->smarty->assign( 'inlener_id', $this->inlener->id );
			$this->smarty->assign( 'uitzender_id', $this->inlener->_uitzender_id );
		}
		
		if( $this->user->user_type == 'werknemer' )
		{
			$werknemer = new \models\werknemers\Werknemer( $this->werknemer->id );
			$this->smarty->assign( 'inleners', $werknemer->inleners() );
			$this->smarty->assign( 'uitzender_id', $werknemer->uitzenderID() );
			$this->smarty->assign( 'werknemer_id', $this->werknemer->id );
		}
		
		$this->smarty->assign('cola', ETregeling::colBebedragen() ); // cola bedragen
		$this->smarty->assign('uitzenders', UitzenderGroup::list()); //uitzenders voor werkgever ophalen
		
		if( isset($_GET['dummy']))
			$this->smarty->display('ureninvoer/main_dummy.tpl');
		else
			$this->smarty->display('ureninvoer/main.tpl');
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// factuur bekijken
	//-----------------------------------------------------------------------------------------------------------------
	public function factuur()
	{
		//tijdvak uit post data
		$tijdvak = [ 'tijdvak' => $_GET['tijdvak'], 'jaar' => $_GET['jaar'], 'periode' => $_GET['periode'] ];
		
		//schoon beginnen
		//FactuurFactory::clear();
		
		if( $this->user->user_type == 'uitzender')
			$_GET['uitzender'] = $this->uitzender->id;
		
		$factuurFactory = new FactuurFactory();
		$factuurFactory->setTijdvak( $tijdvak );
		$factuurFactory->setInlener( $_GET['inlener'] );
		$factuurFactory->setUitzender( $_GET['uitzender'] );
		$factuurFactory->preview();
		
		//alle benodigde gegevens zijn ingesteld, nu aan het werk
		$factuurFactory->run();
		
		$errors = $factuurFactory->errors();
		if( $errors !== false )
		{
			foreach( $errors as $e )
			{
				echo $e . '<br />';
			}
		}
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// bijlage bekijken
	//-----------------------------------------------------------------------------------------------------------------
	public function bijlage( $file_id = NULL )
	{
		if( !isset($_GET['extra']))
		{
			$invoer = new Invoer();
			$file_array = $invoer->getBijlage( $file_id );
		}
		else
		{
			$factuur = new Factuur();
			$file_array = $factuur->getBijlageByID( $file_id );
		}
		
		if( $file_array === NULL )
			die('Geen toegang');
		
		if( $this->user->user_type == 'werknemer' )
			die('Geen toegang');

		/*if( $this->user->user_type == 'uitzender' &&  $file_array['uitzender_id'] != $this->uitzender->id )
			die('Geen toegang');*/
		
		if( $this->user->user_type == 'inlener' &&  $file_array['inlener_id'] != $this->inlener->id )
			die('Geen toegang');
		
		$file = new File($file_array);
		$file->inline();
		
	}
	
}
