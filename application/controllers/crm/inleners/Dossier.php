<?php

use models\Api\CreditSafe;
use models\cao\CAO;
use models\cao\CAOGroup;
use models\facturatie\Betaaltermijnen;
use models\forms\Formbuilder;
use models\inleners\Inlener;
use models\Inleners\Kredietaanvraag;
use models\Inleners\KredietaanvraagGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\History;
use models\utils\VisitsLogger;
use models\verloning\Urentypes;
use models\verloning\UrentypesGroup;
use models\werknemers\WerknemerGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Dossier extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' &&  $this->user->user_type != 'uitzender')forbidden();

		//method naar smarty
		$this->smarty->assign('method', $this->router->method);
		
		//check of uitzender hier mag zijn, niet checken bij kredietpagina
		if( $this->user->user_type == 'uitzender' && strpos( $this->uri->segment( 5 ), 'k') === false )
			if( !Inlener::access( $this->uri->segment( 5 ), 'uitzender', $this->uitzender->id ) ) forbidden();
			
		//log visit
		$log = new VisitsLogger();
		$log->logCRMVisit( 'inlener', $this->uri->segment(5));
		
	}

	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function overzicht( $inlener_id = NULL )
	{
		$inlener = new Inlener( $inlener_id );

		//redirect indien nodig
		if( $inlener->complete == 0 )
		{
			if( $inlener->bedrijfsgegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/inleners/dossier/bedrijfsgegevens/' . $inlener_id ,'location');
			if( $inlener->emailadressen_complete != 1 ) redirect($this->config->item('base_url') . 'crm/inleners/dossier/emailadressen/' . $inlener_id ,'location');
			if( $inlener->factuurgegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/inleners/dossier/factuurgegevens/' . $inlener_id ,'location');
			if( $inlener->contactpersoon_complete != 1 ) redirect($this->config->item('base_url') . 'crm/inleners/dossier/contactpersonen/' . $inlener_id ,'location');
		}

		//show($inleners);
		
		$this->smarty->assign('bedrijfsgegevens', $inlener->bedrijfsgegevens());
		$this->smarty->assign('emailadressen', $inlener->emailadressen() );
		
		$this->smarty->assign('uitzender', UitzenderGroup::bedrijfsnaam( $inlener->uitzenderID() ) );
		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/overzicht.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Krediet pagina
	// TODO: inlener aanvraag afhandelen
	//-----------------------------------------------------------------------------------------------------------------
	public function kredietoverzicht( $inlener_id = NULL )
	{
		//is ID een krediet ID
		$kredietaanvraag_id = NULL;
		if( strpos($inlener_id, 'k') !== false )
			$kredietaanvraag_id = str_replace('k','', $inlener_id);
		
		//alle aanvragen ophalen
		$kredietgroup = new KredietaanvraagGroup();
		
		//inlener is al aangemaakt
		if( $kredietaanvraag_id === NULL )
		{
			$inlener = new Inlener( $inlener_id );
			$bedrijfsgegevens = $inlener->bedrijfsgegevens();
		
			$kredietgegevens = $inlener->kredietgegevens();
			$kredietaanvragen = $kredietgroup->inlener( $inlener_id )->all();
			
			$this->smarty->assign('inlener', $inlener);
		}
		//alleen een aavraag, dan anders afhandelen
		else
		{
			$kredietaanvraag = new Kredietaanvraag( $kredietaanvraag_id );
			$bedrijfsgegevens = $kredietaanvraag->aanvraag();
			
			//check of uitzender hier mag zijn
			if( $this->user->user_type == 'uitzender' && $bedrijfsgegevens['uitzender_id'] != $this->uitzender->id )	forbidden();
			
			//omzetten naar inlener
			if( isset($_POST['accept']) )
			{
				$kredietaanvraag->accept();
				
				if( $kredietaanvraag->errors() !== false )
					$this->smarty->assign('msg', msg('warning', $kredietaanvraag->errors() ));
				else
					$this->smarty->assign('msg', msg('success', 'Kredietaanvraag goedgekeurd. De uitzender kan nu de inlener verder invullen' ));
			}
			
			if( isset($_GET['deny']) )
			{
				if( $kredietaanvraag->deny( $_GET['deny'] ))
					$this->smarty->assign('msg', msg('success', 'Kredietaanvraag afgewezen' ));
				else
					$this->smarty->assign('msg', msg('warning', 'Er gaat wat mis met wegschrijven naar de database' ));
			}
			
			//init
			$kredietgegevens['kredietlimiet'] = NULL;
			$kredietgegevens['kredietgebruik'] = NULL;
			
			$kredietaanvragen = $kredietgroup->aanvraag( $kredietaanvraag_id )->all();
			
			$this->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		}
		
		//krediet rapport ophalen
		$creditsafe = new CreditSafe();
		$rapport = $creditsafe->companyReport( $bedrijfsgegevens['kvknr'] );
		
		
		//TODO: grafiek maken van gebruikt krediet
		
		$this->smarty->assign('rapport', $rapport);
		$this->smarty->assign('rapport_datum', $creditsafe->reportDate() );
		$this->smarty->assign('kredietgegevens', $kredietgegevens);
		$this->smarty->assign('kredietaanvragen', $kredietaanvragen);
		$this->smarty->assign('kredietaanvraag_id', $kredietaanvraag_id);
		$this->smarty->display('crm/inleners/dossier/kredietoverzicht.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Algemene instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function algemeneinstellingen( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );
		
		//default
		$errors = false;
		
		//del
		if( isset($_POST['del']) )
		{
			$inlener->delKoppelingUitzender( $_POST['uitzender_id'] );
		}

		//set data
		if( isset($_POST['set']) )
		{
			$inlener->koppelenAanUitzender( $_POST['uitzender_id'] );
			$errors = $inlener->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Inlener gekoppeld!'));
			else
				$this->smarty->assign('msg', msg('warning', $errors ));
		}
	
		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/algemeneinstellingen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function bedrijfsgegevens( $inlener_id = NULL )
	{
		//historische data ophalen
		$log = new History();
		$data = $log->table( 'inlener_bedrijfsgegevens')->index( array('inlener_id' => 3 ) )->data();
		//show($data);
		
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init inlener object
		$inlener = new Inlener( $inlener_id );

		//set bedrijfsgegevens
		if( isset($_POST['set']) )
		{
			$bedrijfsgevens = $inlener->setBedrijfsgegevens();
			$errors = $inlener->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $inlener->emailadressen_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/inleners/dossier/emailadressen/' . $inlener->inlener_id ,'location');

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$bedrijfsgevens =  $inlener->bedrijfsgegevens();
			$errors = false; //no errors
		}
		
		$formdata = $formbuidler->table( 'inleners_bedrijfsgegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		$formdata['uitzender_id']['value'] = $inlener->uitzenderID();
		
		$this->smarty->assign('formdata', $formdata);
		//show($inlener);

		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/bedrijfsgegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function factuurgegevens( $inlener_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init inlener object
		$inlener = new Inlener( $inlener_id );

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$factuurgegevens = $inlener->setFactuurgegevens();
			$errors = $inlener->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $inlener->contactpersoon_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/inleners/dossier/contactpersonen/' . $inlener->inlener_id ,'location');

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$factuurgegevens =  $inlener->factuurgegevens();
			$errors = false; //no errors
		}
		//show($factuurgegevens);
		$formdata = $formbuidler->table( 'inleners_factuurgegevens' )->data( $factuurgegevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($formdata);

		$this->smarty->assign('list', array( 'betaaltermijnen' => Betaaltermijnen::list() ));
		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/factuurgegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function emailadressen( $inlener_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init inlener object
		$inlener = new Inlener( $inlener_id );

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$emailadressen = $inlener->setEmailadressen();
			$errors = $inlener->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $inlener->factuurgegevens_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/inleners/dossier/factuurgegevens/' . $inlener->inlener_id ,'location');


				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$emailadressen =  $inlener->emailadressen();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'inleners_emailadressen' )->data( $emailadressen )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($formdata);

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/emailadressen.tpl');
	}

	//-----------------------------------------------------------------------------------------------------------------
	// Verloning instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function verloninginstellingen( $inlener_id = NULL )
	{
		//init objects
		$inlener = new Inlener( $inlener_id );		
		$urentypes = new Urentypes();
		$urentypesgroup = new UrentypesGroup();
		$CAOgroup = new CAOGroup();
		
		//del data
		if( isset($_POST['del']) )
		{
			//sitch
			switch ($_POST['del'])
			{
				//delete, pak de key van veld omschrijving voor ID
				case 'inleners_factoren': $inlener->delFactoren( key( $_POST['omschrijving'] ) );
			}
		}
		
		//del cao
		if( isset($_GET['delcao']) )
		{
			$cao = new CAO( $_GET['delcao'] );
			$cao->delCAOFromInlener( $inlener_id );
			if( $cao->errors() === false )
				redirect( $this->config->item( 'base_url' ) . '/crm/inleners/dossier/verloninginstellingen/'.$inlener_id.'?tab=tab-cao' ,'location' );
			
			//msg wanneer geen redirect
			$this->smarty->assign('msg', msg('warning', $cao->errors() ));
		}
			
			//set data
		if( isset($_POST['set']) )
		{
			//switch for each tab
			switch ($_POST['set']) {
				//extra factoren toevoegen
				case 'inleners_factoren':
					$inlener->setFactoren();
					if( $inlener->errors() === false )
						redirect( $this->config->item( 'base_url' ) . '/crm/inleners/dossier/verloninginstellingen/'.$inlener_id ,'location' );
					$errors = $inlener->errors();
					break;
				//urentype aan inlener koppelen
				case 'add_urentype_to_inlener':
					$urentypes->addUrentypeToInlener( $inlener_id, $_POST );
					if( $urentypes->errors() === false )
						redirect( $this->config->item( 'base_url' ) . '/crm/inleners/dossier/verloninginstellingen/'.$inlener_id.'?tab=tab-urentypes' ,'location' );
					$errors = $urentypes->errors();
					break;
				//cao aan inlener koppelen
				case 'add_cao_to_inlener':
					$cao = new CAO( $_POST['cao_id'] );
					$cao->addCAOToInlener( $inlener_id );
					if( $cao->errors() === false )
						redirect( $this->config->item( 'base_url' ) . '/crm/inleners/dossier/verloninginstellingen/'.$inlener_id.'?tab=tab-cao' ,'location' );
					$errors = $cao->errors();
					break;
			}

			//msg wanneer geen redirect
			$this->smarty->assign('msg', msg('warning', $errors ));
		}
		
		$matrix = $urentypesgroup->inlener( $inlener_id )->getUrentypeWerknemerMatrix();
		//show($matrix);
		
		$this->smarty->assign( 'urentypes', $urentypes->getAll() );
		$this->smarty->assign( 'factoren', $inlener->factoren() );
		$this->smarty->assign( 'matrix', $matrix );
		$this->smarty->assign( 'caos', $CAOgroup->all() );
		$this->smarty->assign( 'caos_inlener', $CAOgroup->inlener( $inlener_id ) );
		
		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/verloninginstellingen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function contactpersonen( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );
		
		//contactpersoon goedkeuren
		if( isset($_POST['set']) )
		{
			$inlener->approveContactpersoon( $_POST['set'] );
			redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/users/add?id='.$inlener_id.'&user_type=inlener' ,'location' );
		}

		$contactpersonen = $inlener->contactpersonen();
		$this->smarty->assign('contactpersonen', $contactpersonen);

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/contactpersonen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function documenten( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/documenten.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// notities pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function notities( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/notities.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// facturen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function facturen( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/facturen.tpl');
	}

	
	//-----------------------------------------------------------------------------------------------------------------
	// werknemers pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemers( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );
		
		$werknemergroup = new WerknemerGroup();
		$werknemers = $werknemergroup->all( array('inlener_id' => $inlener_id) );
		
		//show($werknemers);

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->assign('werknemers', $werknemers);
		$this->smarty->display('crm/inleners/dossier/werknemers.tpl');
	}

}
