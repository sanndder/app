<?php

use models\documenten\Document;
use models\documenten\DocumentFactory;
use models\documenten\DocumentGroup;
use models\documenten\IDbewijs;
use models\documenten\Template;
use models\forms\Formbuilder;
use models\inleners\InlenerGroup;
use models\pdf\PdfAanmelding;
use models\pdf\PdfUrenbriefje;
use models\uitzenders\UitzenderGroup;
use models\utils\Carbagecollector;
use models\utils\Codering;
use models\utils\VisitsLogger;
use models\verloning\Invoer;
use models\verloning\LoonstrokenGroup;
use models\verloning\Loonstrook;
use models\verloning\UrenbriefjesGroup;
use models\werknemers\Loonbeslag;
use models\werknemers\LoonbeslagGroup;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingGroup;
use models\werknemers\Werknemer;

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Instellingen controller
 */
class Dossier extends MY_Controller
{
	//TODO per class beveiligen
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//Deze pagina mag alleen bezocht worden door werkgever TODO werknemer beveiliging
		//if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' && $this->user->user_type != 'werknemer')
			//forbidden();
		
		//method naar smarty
		$this->smarty->assign( 'method', $this->router->method );
		
		//log visit
		$log = new VisitsLogger();
		$log->logCRMVisit( 'werknemer', $this->uri->segment( 5 ) );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function overzicht( $werknemer_id = NULL )
	{
		$werknemer = new Werknemer( $werknemer_id );
		
		//redirect indien nodig
		if( $werknemer->complete == 0 )
		{
			if( $werknemer->gegevens_complete != 1 )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/gegevens/' . $werknemer_id, 'location' );
			if( $werknemer->documenten_complete != 1 )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/' . $werknemer_id, 'location' );
			if( $werknemer->dienstverband_complete != 1 )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/dienstverband/' . $werknemer_id, 'location' );
			if( $werknemer->verloning_complete != 1 )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/verloning/' . $werknemer_id, 'location' );
			if( $werknemer->etregeling != 1 )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/etregeling/' . $werknemer_id, 'location' );
		}
		
		//acties
		if( isset($_GET['action']) )
		{
			switch( $_GET['action'] )
			{
				case 'archief':
					$werknemer->setArchief( true );
					break;
				case 'uitarchief':
					$werknemer->setArchief( false );
					break;
			}
		}
		
		//show($werknemers);
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->assign( 'gegevens', $werknemer->gegevens() );
		$this->smarty->display( 'crm/werknemers/dossier/overzicht.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// exporteer werknemer
	//-----------------------------------------------------------------------------------------------------------------
	public function exportwerknemer( $werknemer_id = NULL )
	{
		$export = new \models\verloning\ExportWerknemers();
		$export->setWerknemer( $werknemer_id );
		
		$export->xml();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Algemene instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function algemeneinstellingen( $werknemer_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		//set gegevens
		if( isset( $_POST['set'] ) )
		{
			//sitch
			switch( $_POST['set'] )
			{
				case 'werknemers_factoren':
					$factoren = $werknemer->setFactoren();
					break;
			}
			
			$errors = $werknemer->errors();
			
			//msg
			if( $errors === false )
				$this->smarty->assign( 'msg', msg( 'success', 'Wijzigingen opgeslagen!' ) );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!' ) );
		} else
		{
			//$factoren =  $werknemer->factoren();
			//$errors = false; //no errors
		}
		
		//form maken
		//$formdata = $formbuidler->table( 'werknemers_factoren' )->data( $factoren )->errors( $errors )->build();
		//$this->smarty->assign('formdata', $formdata);
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/algemeneinstellingen.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function gegevens( $werknemer_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		//set gegevens
		if( isset( $_POST['set'] ) )
		{
			$gegevens = $werknemer->setGegevens();
			$errors = $werknemer->errors();
			
			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $werknemer->documenten_complete != 1 )
					redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/' . $werknemer->werknemer_id, 'location' );
				
				//bestaande uiztender melding tonen
				$this->smarty->assign( 'msg', msg( 'success', 'Wijzigingen opgeslagen!' ) );
			} else
				$this->smarty->assign( 'msg', msg( 'warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!' ) );
		} else
		{
			$gegevens = $werknemer->gegevens();
			$errors = false; //no errors
		}
		
		//form opbouwen
		$formdata = $formbuidler->table( 'werknemers_gegevens' )->data( $gegevens )->errors( $errors )->build();
		
		$this->smarty->assign( 'formdata', $formdata );
		
		//show(Codering::listNationaliteiten());
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign( 'list', array( 'nationaliteiten' => Codering::listNationaliteiten(), 'landen' => Codering::listLanden() ) );
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/gegevens.tpl' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function aanmelding( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$pdf = new PdfAanmelding();
		
		$pdf->setFooter();
		$pdf->setHeader();
		
		$werknemer = new Werknemer( $werknemer_id );
		
		$pdf->setBody( $werknemer  );
		
		$pdf->preview();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function documenten( $werknemer_id = NULL )
	{

		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		//id bewijs is appart object
		$idbewijs = new IDbewijs();
		$idbewijs->werknemer( $werknemer_id );
		
		//TODO weghalen
		if( isset( $_GET['contract'] ) )
		{
			$template = new Template( 7 ); //4 is samenwerkingsovereenkomst
			$document = DocumentFactory::createFromTemplateObject( $template );
			$document->setUitzenderID($werknemer->uitzenderID())->setWerknemerID( $werknemer_id )->build()->pdf();
			
			redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/' . $werknemer_id, 'location' );
		}
		
		if( isset( $_GET['contractro'] ) )
		{
			$template = new Template( 12 ); //4 is samenwerkingsovereenkomst
			$document = DocumentFactory::createFromTemplateObject( $template );
			$document->setUitzenderID($werknemer->uitzenderID())->setWerknemerID( $werknemer_id )->build()->pdf();
			
			redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/' . $werknemer_id, 'location' );
		}
		
		if( isset( $_GET['et'] ) )
		{
			$template = new Template( 10 ); //4 is samenwerkingsovereenkomst
			$document = DocumentFactory::createFromTemplateObject( $template );
			$document->setWerknemerID( $werknemer_id )->build()->pdf();
			
			redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/' . $werknemer_id, 'location' );
		}
		
		
		//contract verzenden
		if( isset( $_GET['deldocument'] ) )
		{
			$document = new Document($_GET['deldocument'] );
			$document->setWerknemerID($werknemer_id);
			if( $document->delete() )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/'.$werknemer_id.'?deleted' ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Document kon niet worden verwijderd!' ) );
			
		}
		
		//contract verzenden
		if( isset( $_GET['sendforsign'] ) )
		{
			$document = new Document($_GET['sendforsign'] );
			if( $document->emailSignLink() )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/'.$werknemer_id.'?send' ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Document kon niet worden verstuurd!' ) );
			
		}
		
		//messages
		if( isset( $_GET['send'] ) )
			$this->smarty->assign( 'msg', msg( 'success', 'Document is verstuurd!' ) );
		if( isset( $_GET['deleted'] ) )
			$this->smarty->assign( 'msg', msg( 'success', 'Document verwijderd!' ) );
			
		//ID opslaan vanuit wizard
		if( isset( $_POST['set_wizard'] ) )
		{
			$idbewijs->setVervalDatum( $_POST['vervaldatum'] );
			
			if( $idbewijs->errors() !== false )
				$this->smarty->assign( 'msg', msg( 'warning', $idbewijs->errors() ) );
			else
			{
				//check of alles compleet is
				if( $idbewijs->complete() )
				{
					//cache value, wordt te vroeg bijgewerkt
					$documenten_complete_cache = $werknemer->documenten_complete;
					//set documenten als complete
					$werknemer->documenten_complete();
					
					//nieuwe aanmelding doorzetten naar volgende pagina
					if( $documenten_complete_cache != 1 )
						redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/dienstverband/' . $werknemer->werknemer_id, 'location' );
				} else
				{
					//set documenten als NIET complete
					$werknemer->documenten_complete( false );
					$this->smarty->assign( 'msg', msg( 'warning', 'U moet een kopie van het ID bewijs uploaden voordat u verder kunt' ) );
				}
			}
		}
		
		$documentGroup = new DocumentGroup();
		$documenten = $documentGroup->werknemer( $werknemer_id )->get();
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->assign( 'documenten', $documenten);
		$this->smarty->assign( 'contract', $werknemer->contract() );
		$this->smarty->assign( 'id_voorkant', $idbewijs->url( 'voorkant' ) );
		$this->smarty->assign( 'id_achterkant', $idbewijs->url( 'achterkant' ) );
		$this->smarty->assign( 'vervaldatum', $idbewijs->vervaldatum() );
		
		//afwijkende template voor
		if( $werknemer->complete != 1 )
			$this->smarty->display( 'crm/werknemers/dossier/documenten_wizard.tpl' );
		else
			$this->smarty->display( 'crm/werknemers/dossier/documenten.tpl' );
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// document details
	//-----------------------------------------------------------------------------------------------------------------
	public function documentdetails( $werknemer_id = NULL, $document_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		$document = new Document( $document_id );
		
		$details = $document->details();
		//show($details);
		$this->smarty->assign( 'document_details', $details );
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/documentdetails.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// plaatsingen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function plaatsingen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		$inlenerGroup = new InlenerGroup();
		$plaatsingGroup = new PlaatsingGroup();
		
		//Uitzender wijzigen
		if( isset( $_POST['set'] ) )
		{
			//switch for verschillende forms
			switch( $_POST['set'] )
			{
				//extra factoren toevoegen
				case 'set_uitzender':
					$werknemer->setUitzender( $_POST['uitzender_id'] );
					if( $werknemer->errors() === false )
						$this->smarty->assign( 'msg', msg( 'success', 'Uitzender voor werknemer gewijzigd' ) );
					else
						$this->smarty->assign( 'msg', msg( 'warning', $werknemer->errors() ) );
					break;
			}
		}
		
		//plaatsing verwijderen
		if( isset( $_GET['delplaatsing'] ) )
		{
			$plaatsing = new Plaatsing( $_GET['delplaatsing'] );
			$plaatsing->delete();
		}
		
		//plaatsing verwijderen
		if( isset( $_GET['uitzendbevestiging'] ) )
		{
			$plaatsing = new Plaatsing( $_GET['uitzendbevestiging'] );
			if( $plaatsing->generateUitzendbevestiging() )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/plaatsingen/' . $werknemer->werknemer_id, 'location' );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Uitzendbevestiging kan niet worden gemaakt' ));
		}
		
		
		
		$inleners = $inlenerGroup->uitzender( $werknemer->uitzenderID() )->all( array('complete' => 1) );
		
		//$this->smarty->assign('plaatsingen', $plaatsingen );
		$this->smarty->assign( 'uitzenders', UitzenderGroup::list() );
		$this->smarty->assign( 'inleners', $inleners );
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->assign( 'plaatsingen', $plaatsingGroup->werknemer( $werknemer_id )->all() );
		$this->smarty->assign( 'werknemer_uitzender', $werknemer->uitzender() );
		$this->smarty->display( 'crm/werknemers/dossier/plaatsingen.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// reserveringen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function reserveringen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		$reserveringen = new \models\verloning\Reserveringen();
		
		$this->smarty->assign( 'stand', $reserveringen->werknemer( $werknemer_id )->stand() );
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/reserveringen.tpl' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// urenbriefjes pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function urenbriefjes( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$urenbriefjesGroup = new UrenbriefjesGroup();
		$urenbriefjes = $urenbriefjesGroup->werknemer($werknemer_id)->all();
		
		$this->smarty->assign( 'urenbriefjes', $urenbriefjes );
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/urenbriefjes.tpl' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonstroken pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function loonstroken( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )
			forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$loonstrokengroup = new LoonstrokenGroup();
		$loonstrokengroup->werknemer( $werknemer_id );
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->assign( 'loonstroken', $loonstrokengroup->all() );
		$this->smarty->display( 'crm/werknemers/dossier/loonstroken.tpl' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonbeslagen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function loonbeslagen( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' )	forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		//add
		if( isset($_POST['go']) )
		{
			$loonbeslag = new Loonbeslag();
			$loonbeslag->setWerknemerID( $werknemer_id );
			if( $loonbeslag->add( $_POST ) )
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/loonbeslag/' . $werknemer_id . '/' . $loonbeslag->id()  ,'location' );
			else
				$this->smarty->assign( 'msg', msg( 'danger', $loonbeslag->errors() ));
		}
		
		$loonbeslagenGroup = new LoonbeslagGroup();
		$loonbeslagenGroup->setWerknemerID( $werknemer_id );
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->assign( 'loonbeslagen', $loonbeslagenGroup->all() );
		$this->smarty->display( 'crm/werknemers/dossier/loonbeslagen.tpl' );
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonbeslagen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function loonbeslag( $werknemer_id = NULL, $loonbeslag_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' )	forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		$loonbeslag = new Loonbeslag( $loonbeslag_id );
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/loonbeslag.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// ziekmeldingen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function ziekmeldingen( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )
			forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/ziekmeldingen.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// notities pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function notities( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever')
			forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/notities.tpl' );
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen dienstverband
	//-----------------------------------------------------------------------------------------------------------------
	public function dienstverband( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' &&  $this->user->user_type != 'uitzender' )
			forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		//set wizard
		if( isset( $_POST['set_wizard'] ) )
		{
			$werknemer->setDefaultCao( $_POST['default_cao'] );
			$werknemer->setStartDienstverband( $_POST['indienst'] );
			$werknemer->setPensioen( $_POST['stipp'] );

			if( $werknemer->errors() !== false )
				$this->smarty->assign( 'errors', $werknemer->errors() );
			else
			{
				$werknemer->dienstverbandIsSet();
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/verloning/' . $werknemer_id, 'location' );
			}
		}
		
		//Pensioen aanpassen
		if( isset( $_POST['set_pensioen'] ) )
		{
			$werknemer->setPensioen( $_POST['stipp'] );
			if( $werknemer->errors() !== false )
				$this->smarty->assign( 'errors', $werknemer->errors() );
		}
		
		//CAO aanpassen
		if( isset( $_POST['set_cao'] ) )
		{
			$werknemer->setDefaultCao( $_POST['default_cao'] );
			if( $werknemer->errors() !== false )
				$this->smarty->assign( 'errors', $werknemer->errors() );
		}
		
		$this->smarty->assign( 'pensioen', $werknemer->pensioen() );
		$this->smarty->assign( 'default_cao', $werknemer->defaultCao() );
		$this->smarty->assign( 'indienst', $werknemer->startDienstverband() );
		$this->smarty->assign( 'werknemer', $werknemer );
		
		if( $werknemer->complete != 1 )
			$this->smarty->display( 'crm/werknemers/dossier/dienstverband_wizard.tpl' );
		else
			$this->smarty->display( 'crm/werknemers/dossier/dienstverband.tpl' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen dienstverband
	//-----------------------------------------------------------------------------------------------------------------
	public function verloning( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )
			forbidden();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		//verlonings instellingen aanpassen
		if( isset( $_POST['set'] ) )
		{
			$werknemer->setVerloning();
			if( $werknemer->errors() !== false )
				$this->smarty->assign( 'msg', msg( 'error', $werknemer->errors() ) );
			else
			{
				$this->smarty->assign( 'msg',  msg( 'success', 'Wijzigingen opgeslagen' ));
				if( $werknemer->verloning_complete != NULL AND $werknemer->complete == 0 )
				{
					$verloning = $werknemer->verloning();
					//bij et regeling naar ET wizard
					if( $verloning['et_regeling'] == 1 )
						redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/etregeling/' . $werknemer_id, 'location' );
					//anders is het klaar
					else
						redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/overzicht/success', 'location' );
				}
			}
		}

		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->assign( 'indienst', $werknemer->startDienstverband() );
		$this->smarty->assign( 'verloning', $werknemer->verloning() );
		$this->smarty->display( 'crm/werknemers/dossier/verloning.tpl' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen dienstverband
	//-----------------------------------------------------------------------------------------------------------------
	public function etregeling( $werknemer_id = NULL )
	{
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender' )
			forbidden();
		
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		$et = $werknemer->etregeling();
		
		//del bsn
		if( isset( $_GET['delbsn'] ) )
		{
			$et->delbsn();
			redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/etregeling/' . $werknemer_id, 'location' );
		}
		
		//wanneer ET actief, bestand ophalen
		if( $et !== NULL )
			$this->smarty->assign( 'bsn', $et->fileBsn() );
		
		//dagteking opslaan
		if( isset( $_POST['set'] ) && $_POST['set'] == 'dagtekening' )
		{
			$et->setDagtekening( $_POST['dagtekening'] );
			if( $et->errors() !== false )
				$this->smarty->assign( 'msg', msg( 'warning', $et->errors() ) );
			else
				$this->smarty->assign( 'msg', msg( 'success', 'Dagtekening opgeslagen!' ) );
		}
		
		//adres opslaan
		if( isset( $_POST['set'] ) && $_POST['set'] == 'adres' )
		{
			$verblijf =	$et->setVerblijf();
			$errors = $et->errors();
			
			//msg
			if( $errors === false )
				$this->smarty->assign( 'msg', msg( 'success', 'Wijzigingen opgeslagen!' ) );
			else
				$this->smarty->assign( 'msg', msg( 'warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!' ) );
			
		}
		else
		{
			$verblijf = $et->verblijf();
			$errors = false; //no errors
		}
		
		//is ET compleet ingevuld
		if( $werknemer->complete != 1 )
		{
			if( $et->isComplete() )
			{
				$werknemer->setEtComplete();
				redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/overzicht/success', 'location' );
			}
		}
		
		//form opbouwen
		$formdata = $formbuidler->table( 'werknemers_et_verblijf' )->data( $verblijf )->errors( $errors )->build();
		
		$this->smarty->assign( 'formdata', $formdata );
		$this->smarty->assign( 'landen', Codering::listLanden() );
		$this->smarty->assign( 'gegevens', $werknemer->gegevens() );
		$this->smarty->assign( 'dagtekening', $et->dagtekening() );
		$this->smarty->assign( 'werknemer', $werknemer );
		$this->smarty->display( 'crm/werknemers/dossier/etregeling.tpl' );
	}
	
}
