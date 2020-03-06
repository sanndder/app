<?php

use models\facturatie\Factuur;
use models\facturatie\FactuurFactory;
use models\inleners\Inlener;
use models\inleners\InlenerGroup;
use models\verloning\Invoer;
use models\verloning\InvoerET;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;
use models\verloning\UrentypesGroup;
use models\werknemers\Werknemer;
use models\zzp\Zzp;

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
			$this->_uitzender_id = $this->user->uitzender_id;
		
		//uitzender instellen
		if( $this->user->user_type == 'inlener' )
			$this->_inlener_id = $this->inlener->inlener_id;
		
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
	// factuur genereren
	//-----------------------------------------------------------------------------------------------------------------
	public function generateFacturen()
	{
		//tijdvak uit post data
		$tijdvak = [ 'tijdvak' => $_POST['tijdvak'], 'jaar' => $_POST['jaar'], 'periode' => $_POST['periode'] ];
		
		//schoon beginnen
		if( ENVIRONMENT == 'development')
			FactuurFactory::clear();
		
		$factuurFactory = new FactuurFactory();
		$factuurFactory->setTijdvak( $tijdvak );
		$factuurFactory->setInlener( $_POST['inlener_id'] );
		$factuurFactory->setUitzender( $this->_uitzender_id );
		
		//alle benodigde gegevens zijn ingesteld, nu aan het werk
		$factuurFactory->run();
		
		//default
		$result['status'] = 'success';
		
		if( $factuurFactory->errors() !== false )
		{
			$result['status'] = 'error';
			$result['error'] = $factuurFactory->errors();
		}
		
		echo json_encode( $result );

	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Werknemer invoerdata ophalen
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemerInvoer()
	{
		if( $this->user->werkgever_type == 'uitzenden' )
			$werknemer = new Werknemer( $_POST['werknemer_id'] );
		
		//classes
		$invoerUren = new InvoerUren( $this->invoer );
		$invoerKm = new InvoerKm( $this->invoer );
		$invoervergoedingen = new InvoerVergoedingen( $this->invoer );
		
		//uiztenden
		if( $this->user->werkgever_type == 'uitzenden' )
		{
			$invoerUren->setWerknemer( $_POST['werknemer_id'] );
			$invoerKm->setWerknemer( $_POST['werknemer_id'] );
			$invoervergoedingen->setWerknemer( $_POST['werknemer_id'] );
			
			$invoerET = new InvoerET( $this->invoer );
			$invoerET->setWerknemer( $_POST['werknemer_id'] );
			
			//urenmatrix
			$array['invoer']['uren'] = $invoerUren->urenMatrix();
			
			//kilometers
			$array['invoer']['km'] = $invoerKm->getWerknemerKilometers();
			
			//vergoedingen
			$invoervergoedingen->setWerknemerUren( $invoerUren->getWerknemerUren() );
			$array['invoer']['vergoedingen'] = $invoervergoedingen->getWerknemerVergoedingen();
			
			//urentypes erbij
			$urentypesGroup = new UrentypesGroup();
			$array['info']['urentypes'] = $urentypesGroup->inlener(  $_POST['inlener_id'] )->urentypesWerknemer( $_POST['werknemer_id'] );
			
			//et regeling
			$array['invoer']['et'] = $invoerET->getEtRow();
			
			//ET info
			$invoerET->setWerknemerUren( $invoerUren->getWerknemerUren() );
			
			$array['info']['et_regeling'] = $werknemer->deelnemer_etregeling;
			$array['info']['et']['max'] = $invoerET->maxUitruil();
		}
		
		//bemiddeling
		if( $this->user->werkgever_type == 'bemiddeling' )
		{
			$invoerUren->setZZP( $_POST['werknemer_id'] );
			$invoerKm->setZZP( $_POST['werknemer_id'] );
			$invoervergoedingen->setZZP( $_POST['werknemer_id'] );
			
			//urenmatrix
			$array['invoer']['uren'] = $invoerUren->urenMatrix();
			
			//kilometers
			$array['invoer']['km'] = $invoerKm->getZzpKilometers();
			
			//vergoedingen
			$invoervergoedingen->setZzpUren( $invoerUren->getZzpUren() );
			$array['invoer']['vergoedingen'] = $invoervergoedingen->getZzpVergoedingen();
			
			//urentypes erbij
			$urentypesGroup = new UrentypesGroup();
			$array['info']['urentypes'] = $urentypesGroup->inlener(  $_POST['inlener_id'] )->urentypesZzp( $_POST['werknemer_id'] );
			
		}
		
		$array['info']['projecten'] = $this->invoer->getProjecten();
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
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerUren->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerUren->setZZP( $_POST['werknemer_id'] );
		
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
	// vergoeding doorbelasten opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveVergoedingDoorbelasten()
	{
		$invoerVergoedingen = new InvoerVergoedingen( $this->invoer );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerVergoedingen->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerVergoedingen->setZZP( $_POST['werknemer_id'] );
		
		//save
		if( $invoerVergoedingen->setDoorbelasten( $_POST['invoer_id'], $_POST['doorbelasten'] ) )
			$array['status'] = 'success';
		else
			$array['status'] = 'error';
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// vergoeding bedrag opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveEtHuisvesting()
	{
		$invoerET = new InvoerET( $this->invoer );
		$invoerET->setWerknemer( $_POST['werknemer_id'] );
		
		//save
		if( $invoerET->setBedrag( $_POST['bedrag'], 'bedrag_huisvesting' ) )
			$array['status'] = 'success';
		else
			$array['status'] = 'error';
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// vergoeding bedrag opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveEtLevensstandaard()
	{
		$invoerET = new InvoerET( $this->invoer );
		$invoerET->setWerknemer( $_POST['werknemer_id'] );
		
		//save
		if( $invoerET->setBedrag( $_POST['bedrag'], 'bedrag_levensstandaard' ) )
			$array['status'] = 'success';
		else
			$array['status'] = 'error';
		
		echo json_encode( $array );
	}


	
	//-----------------------------------------------------------------------------------------------------------------
	// vergoeding bedrag opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveVergoedingBedrag()
	{
		$invoerVergoedingen = new InvoerVergoedingen( $this->invoer );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerVergoedingen->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerVergoedingen->setZZP( $_POST['werknemer_id'] );
		
		//save
		if( $invoerVergoedingen->setBedrag( $_POST['invoer_id'], $_POST['werknemer_vergoeding_id'], $_POST['bedrag'] ) )
		{
			$array['status'] = 'success';
			$array['invoer_id'] = $invoerVergoedingen->getVergoedingInsertId();
		}
		else
			$array['status'] = 'error';
		
		echo json_encode( $array );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// km opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveKm()
	{
		$invoerKm = new InvoerKm( $this->invoer );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerKm->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerKm->setZZP( $_POST['werknemer_id'] );
		
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
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerKm->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerKm->setZZP( $_POST['werknemer_id'] );
		
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
			$array['periodes'] = array(  7 => '07' , 8 => '08', 9=> '09');
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
