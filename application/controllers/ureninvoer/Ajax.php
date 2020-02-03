<?php

use models\inleners\Inlener;
use models\inleners\InlenerGroup;
use models\verloning\Invoer;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\UrentypesGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ajax extends MY_Controller
{
	private $_uitzender_id = NULL;
	private $invoer = NULL;
	
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//uitzender instellen
		if( $this->user->user_type == 'uitzender' )
			$this->_uitzender_id = $this->uitzender->id;
		
		if( $this->user->user_type == 'werkgever' && isset($_POST['uitzender_id']) )
			$this->_uitzender_id = intval($_POST['uitzender_id']);
		
		//geen lege var
		$_POST['inlener_id'] = $_POST['inlener_id'] ?? null;
		
		$this->invoer = new Invoer();
		$this->invoer->setTijdvak( $_POST );
		$this->invoer->setInlener( $_POST['inlener_id'] );
		$this->invoer->setUitzender( $this->_uitzender_id );
		//set header voor hele controller
		//header( 'Content-Type: application/json' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werknemer invoerdata ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemerInvoer()
	{
		$invoerUren = new InvoerUren( $this->invoer );
		$invoerUren->setWerknemer( $_POST['werknemer_id'] );
		
		$invoerKm = new InvoerKm( $this->invoer );
		$invoerKm->setWerknemer( $_POST['werknemer_id'] );

		//urenmatrix
		$array['invoer']['uren'] = $invoerUren->urenMatrix();
		
		//kilometers
		$array['invoer']['km'] = $invoerKm->getWerknemerKilometers();
		
		//urentypes erbij
		$urentypesGroup = new UrentypesGroup();
		$array['info']['urentypes'] = $urentypesGroup->urentypesWerknemer( $_POST['werknemer_id'] );
		
		$array['info']['periode_start'] = $invoerUren->getPeriodeStart();
		$array['info']['periode_einde'] = $invoerUren->getPeriodeEinde();
		
		echo json_encode( $array );
	}


	//-----------------------------------------------------------------------------------------------------------------
	// uren opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveUren()
	{
		$invoerUren = new InvoerUren( $this->invoer );
		$invoerUren->setWerknemer( $_POST['werknemer_id'] );
		
		//welke actie
		if( $_POST['urenrow']['invoer_id'] != '' && ( $_POST['urenrow']['aantal'] == '' || $_POST['urenrow']['aantal'] == 0 ) )
		{
			if( $invoerUren->delRow( $_POST['urenrow'] ))
				$array['status'] = 'deleted';
			else
				$array['status'] = 'error';
		}
		else
		{
			$result = $invoerUren->setRow( $_POST['urenrow'] );
			$array['status'] = 'set';
			$array['row'] = $result;
		}
		
		echo json_encode( $array );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// km opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveKm()
	{
		$invoerKm = new InvoerKm( $this->invoer );
		$invoerKm->setWerknemer( $_POST['werknemer_id'] );
		
		//save
		$result = $invoerKm->setRow( $_POST['kmrow'] );
		$array['status'] = 'set';
		$array['row'] = $result;
		
		echo json_encode( $array );
	}



	//-----------------------------------------------------------------------------------------------------------------
	// Alle kilometers periode verwijderen
	//-----------------------------------------------------------------------------------------------------------------
	public function clearKm()
	{
		$invoerKm = new InvoerKm( $this->invoer );
		$invoerKm->setWerknemer( $_POST['werknemer_id'] );
		
		if( $invoerKm->clearAll() )
			$array['status'] = 'success';
		else
		{
			$array['status'] = 'error';
			$array['error'] = $invoerKm->errors();
		}
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de juiste tijdvakken op
	//-----------------------------------------------------------------------------------------------------------------
	public function listTijdvakInlener()
	{
		$inlener = new Inlener( $_POST['inlener_id'] );
		$factuurgegevens = $inlener->factuurgegevens();
		
		if( $factuurgegevens['frequentie'] == 'w')
		{
			$array['tijdvak'] = 'w';
			$array['titel'] = 'week';
			$array['jaren'] = array( 2020 );
			$array['periodes'] = array( 2 => '02',  3=> '03', 4 => '04');
		}
		
		if( $factuurgegevens['frequentie'] == '4w')
		{
			$array['tijdvak'] = '4w';
			$array['titel'] = 'periode';
			$array['jaren'] = array( 2020 );
			$array['periodes'] = array( 1 => '01', 2=> '02');
		}
		
		if( $factuurgegevens['frequentie'] == 'm')
		{
			$array['tijdvak'] = 'm';
			$array['titel'] = 'maand';
			$array['jaren'] = array( 2020 );
			$array['periodes'] = array( 1 => 'januari', 2 => 'februari' );
		}
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werknemer overzicht ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function listWerknemers()
	{
		$array['werknemers'] = $this->invoer->listWerknemers();
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werknemer overzicht ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function getWerknemerOverzicht()
	{
	
		$array['werknemers'] = $this->invoer->getWerknemerOverzicht();
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Haal voor de uitzender de inleners op
	//-----------------------------------------------------------------------------------------------------------------
	public function listInleners()
	{
		$inlenerGroup = new InlenerGroup();
		$array['inleners'] = $inlenerGroup->uitzender( $this->_uitzender_id )->listForUreninvoer();
		
		echo json_encode( $array );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload bijlages
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadBijlages()
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'invoer/bijlages' );
		$this->uploadfiles->setAllowedFileTypes( 'jpg|png|pdf' );
		$this->uploadfiles->setDatabaseTable( 'invoer_bijlages' );
		$this->uploadfiles->setPrefix( 'bijlage_' );
		$this->uploadfiles->uploadfiles();
		
		if( $this->uploadfiles->errors() === false)
		{
			$file_array = $this->uploadfiles->getFileArray();
			if( $this->invoer->saveBijlageToDatabase( $file_array) )
				$result['status'] = 'success';
			else
			{
				$result['status'] = 'error';
				$result['error'] = $this->invoer->errors();
			}
		}
		else
		{
			$result['status'] = 'error';
			$result['error'] = $this->uploadfiles->errors();
		}
		
		echo json_encode($result);
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// laod bijlages
	//-----------------------------------------------------------------------------------------------------------------
	public function getBijlages()
	{
		$result['files'] = $this->invoer->getBijlages();
		
		echo json_encode($result);
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// verwijder 1 bijlage
	//-----------------------------------------------------------------------------------------------------------------
	public function delBijlage()
	{
		if( $this->invoer->delBijlage() )
			$result['status'] = 'success';
		else
			$result['status'] = 'error';
		
		echo json_encode($result);
	}



}
