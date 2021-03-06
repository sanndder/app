<?php

use models\facturatie\Factuur;
use models\facturatie\FactuurFactory;
use models\inleners\Inlener;
use models\inleners\InlenerGroup;
use models\verloning\Invoer;
use models\verloning\InvoerAangenomenwerk;
use models\verloning\InvoerET;
use models\verloning\InvoerKm;
use models\verloning\InvoerReserveringen;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;
use models\verloning\UrentypesGroup;
use models\werknemers\PlaatsingGroup;
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
		{
			$this->_inlener_id = $this->inlener->inlener_id;
			$this->_uitzender_id = $this->inlener->uitzender();
		}
		
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
	// instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function settings()
	{
		$result['settings'] = $this->invoer->settings();
		echo json_encode( $result );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function setsettings()
	{
		$this->invoer->setSettings( $_POST );
		echo json_encode( ['status' => 'success'] );
	}

	//-----------------------------------------------------------------------------------------------------------------
	// kijken of er bijlages zijn
	//-----------------------------------------------------------------------------------------------------------------
	public function checkforbijlages()
	{
		$result['hold'] = true;
		
		$inlener = new Inlener( $this->invoer->inlener() );
		$factuurgegevens = $inlener->factuurgegevens();
		
		//check for factoring
		if( $factuurgegevens['factoring'] == 0 )
		{
			$result['hold'] = false;
			echo json_encode($result);
			die();
		}
		else
		{
			//check for bijlages
			if( count($this->invoer->getBijlages()) == 0 )
				$result['hold'] = true;
			else
				$result['hold'] = false;
			
			echo json_encode($result);
			die();
		}
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
		$invoerReserveringen = new InvoerReserveringen( $this->invoer );
		
		//uiztenden
		if( $this->user->werkgever_type == 'uitzenden' )
		{
			$invoerUren->setWerknemer( $_POST['werknemer_id'] );
			$invoerKm->setWerknemer( $_POST['werknemer_id'] );
			$invoervergoedingen->setWerknemer( $_POST['werknemer_id'] );
			$invoerReserveringen->setWerknemer( $_POST['werknemer_id'] );
			
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
			
			//reserveringen
			$array['invoer']['reserveringen']['opgevraagd'] = $invoerReserveringen->getOpgevraagdeReserveringen();
			$array['invoer']['reserveringen']['stand'] = $invoerReserveringen->getStandReserveringen();
			
			//uurlonen
			$plaatsingGroup = new PlaatsingGroup();
			$array['info']['uurlonen'] = $plaatsingGroup->werknemer( $_POST['werknemer_id'] )->uurlonen();
			
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
	// project bij afgesprokenwerk opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveAangenomenwerkProject()
	{
		$invoerAangenomenWerk = new InvoerAangenomenwerk( $this->invoer );
		$invoerAangenomenWerk->setProject($_POST);
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// project data bij afgesprokenwerk opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveAangenomenwerkProjectData()
	{
		$invoerAangenomenWerk = new InvoerAangenomenwerk( $this->invoer );
		
		$array['invoer_id'] = $invoerAangenomenWerk->setProjectData( $_POST['invoer_id'], $_POST['project_id'], $_POST['omschrijving'], $_POST['bedrag'] );
		
		echo json_encode( $array );
	}

	
	//-----------------------------------------------------------------------------------------------------------------
	// project bij afgesprokenwerk opslaan
	// TODO: zonder splitsen
	//-----------------------------------------------------------------------------------------------------------------
	public function getAangenomenwerkData()
	{
		$inlener = new Inlener( $_POST['inlener_id'] );
		$factuurgegevens = $inlener->factuurgegevens();
		
		$invoerAangenomenWerk = new InvoerAangenomenwerk( $this->invoer );
		
		if( $factuurgegevens['factuur_per_project'] == 1 )
		{
			$projecten = $this->invoer->getActieveProjecten();
			$array = $invoerAangenomenWerk->getDataForProjecten( $projecten );
		}
		
		echo json_encode( $array );
		
	}


	//-----------------------------------------------------------------------------------------------------------------
	// alle uren invoer verwijderen
	//-----------------------------------------------------------------------------------------------------------------
	public function deluren()
	{
		$invoerUren = new InvoerUren( $this->invoer );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerUren->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerUren->setZZP( $_POST['werknemer_id'] );
		
		if( $invoerUren->delAll())
			$array['status'] = 'success';
		else
		{
			$array['error'] = $invoerUren->errors();
			$array['status'] = 'error';
		}
		
		echo json_encode( $array );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// standaard aantal uren invullen en verdelen
	//-----------------------------------------------------------------------------------------------------------------
	public function filluren( $aantal = NULL )
	{
		$aantal = intval($aantal);
		
		if( $aantal < 36 || $aantal > 40 )
		{
			$array['error'] = 'ongeldig aantal uren';
			$array['status'] = 'error';
			
			echo json_encode( $array );
			die();
		}
		
		$invoerUren = new InvoerUren( $this->invoer );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerUren->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerUren->setZZP( $_POST['werknemer_id'] );
		
		if( $invoerUren->fillUren( $aantal ))
			$array['status'] = 'success';
		else
		{
			$array['error'] = $invoerUren->errors();
			$array['status'] = 'error';
		}
		
		echo json_encode( $array );
	}
	

	//-----------------------------------------------------------------------------------------------------------------
	// uren opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveUren()
	{
		$invoerUren = new InvoerUren( $this->invoer );
		
		//komma vervangen
		$_POST['urenrow']['aantal'] = str_replace( ',', '.', $_POST['urenrow']['aantal'] );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerUren->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerUren->setZZP( $_POST['werknemer_id'] );
		
		//welke actie
		if( $_POST['urenrow']['invoer_id'] != '' && ( $_POST['urenrow']['aantal'] == '' || strcmp($_POST['urenrow']['aantal'], 0) === 0 ) )
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
	// vergoeding project opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveVergoedingProject()
	{
		$invoerVergoedingen = new InvoerVergoedingen( $this->invoer );
		
		if( $this->user->werkgever_type == 'uitzenden' ) $invoerVergoedingen->setWerknemer( $_POST['werknemer_id'] );
		if( $this->user->werkgever_type == 'bemiddeling' ) $invoerVergoedingen->setZZP( $_POST['werknemer_id'] );
		
		//save
		if( $invoerVergoedingen->setProject( $_POST['invoer_id'], $_POST['project_id'] ) )
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
	// reservering bedrag opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveReservering()
	{
		$invoerReserveringen = new InvoerReserveringen( $this->invoer );
		$invoerReserveringen->setWerknemer( $_POST['werknemer_id'] );
		$invoerReserveringen->setType( $_POST['reserveringType'] )->setBedrag( $_POST['bedrag'] );
		
		//save
		if( $invoerReserveringen->errors() !== false )
		{
			$array['status'] = 'error';
			$array['error'] = $invoerReserveringen->errors();
		}
		else
			$array['status'] = 'success';
		
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
	public function getInlenerInfo()
	{
		$inlener = new Inlener( $_POST['inlener_id'] );
		$factuurgegevens = $inlener->factuurgegevens();
		
		if( $factuurgegevens['frequentie'] == 'w')
		{
			$array['tijdvak'] = 'w';
			$array['titel'] = 'week';
			$array['jaren'] = array( 2021 );
			
			
			$week = date( 'W' )-1;
			
			$array['periodes'] = array(
				$week-3 => sprintf("%02d", $week-3),
				$week-2 => sprintf("%02d", $week-2),
				$week-1 => sprintf("%02d", $week-1),
				$week => sprintf("%02d", $week)
			);
			
			//vrijdag nieuwe week open
			if( date('w') > 4 || date('w') == 0 )
				$array['periodes'][$week+1] = sprintf("%02d", $week+1);
		}
		
		if( $factuurgegevens['frequentie'] == '4w')
		{
			$array['tijdvak'] = '4w';
			$array['titel'] = '4 weken';
			$periode = ceil(date( 'W' )/4);
			$array['periodes'] = array(
				$periode-2 => sprintf("%02d", $periode-2),
				$periode-1 => sprintf("%02d", $periode-1),
			);
		}
		
		if( $factuurgegevens['frequentie'] == 'm')
		{
			$array['tijdvak'] = 'm';
			$array['titel'] = 'maand';
			$array['jaren'] = array( 2021 );
			$array['periodes'] = array( 4 => 'april', 5 => 'mei' );
		}
		
		//niet standaard alles teruggeven
		$array['aangenomenwerk'] = $factuurgegevens['afgesproken_werk'];
		$array['factuur_per_project'] = $factuurgegevens['factuur_per_project'];
		
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
		$array['inleners'] = $inlenerGroup->uitzender( $this->_uitzender_id )->listForUreninvoer( $this->invoer->tijdvakinfo() );
		
		echo json_encode( $array );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// upload bijlages
	//-----------------------------------------------------------------------------------------------------------------
	public function uploadBijlages()
	{
		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( 'invoer/bijlages' );
		$this->uploadfiles->setAllowedFileTypes( 'jpg|png|pdf|jpeg|JPG|JPEG|PNG|PDF' );
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
		$result['info']['projecten'] = $this->invoer->getProjecten();
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
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// bijlage project opslaan
	//-----------------------------------------------------------------------------------------------------------------
	public function saveBijlageProject()
	{
		//save
		if( $this->invoer->setBijlageProject( $_POST['file_id'], $_POST['project_id'] ) )
			$array['status'] = 'success';
		else
			$array['status'] = 'error';
		
		echo json_encode( $array );
	}
	
}
