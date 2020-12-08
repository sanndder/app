<?php

use models\documenten\DocumentFactory;
use models\documenten\IDbewijs;
use models\documenten\Template;
use models\facturatie\FacturenGroup;
use models\forms\Formbuilder;
use models\inleners\InlenerGroup;
use models\zzp\ZzpGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\Carbagecollector;
use models\utils\Codering;
use models\utils\VisitsLogger;
use models\zzp\Plaatsing;
use models\zzp\PlaatsingGroup;
use models\zzp\Zzp;

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
		if( $this->user->user_type != 'werkgever' &&  $this->user->user_type != 'uitzender' )forbidden();

		//method naar smarty
		$this->smarty->assign('method', $this->router->method);
		
		//log visit
		$log = new VisitsLogger();
		$log->logCRMVisit( 'zzp', $this->uri->segment(5));
	}

	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function overzicht( $zzp_id = NULL )
	{
		$zzp = new Zzp( $zzp_id );

		//redirect indien nodig
		if( $zzp->complete == 0 )
		{
			if( $zzp->bedrijfsgegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/zzp/dossier/bedrijfsgegevens/' . $zzp_id ,'location');
			if( $zzp->documenten_complete != 1 ) redirect($this->config->item('base_url') . 'crm/zzp/dossier/documenten/' . $zzp_id ,'location');
			//if( $zzp->contactpersoon_complete != 1 ) redirect($this->config->item('base_url') . 'crm/zzp/dossier/contactpersonen/' . $zzp_id ,'location');
		}

		//show($zzps);

		$this->smarty->assign('zzp', $zzp);
		$this->smarty->assign('bedrijfsgegevens', $zzp->bedrijfsgegevens());
		$this->smarty->display('crm/zzp/dossier/overzicht.tpl');
	}



	//-----------------------------------------------------------------------------------------------------------------
	// Algemene instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function algemeneinstellingen( $zzp_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init werknemer object
		$zzp = new Zzp( $zzp_id );

		//set gegevens
		if( isset($_POST['set']) )
		{
			//sitch
			switch ($_POST['set']) {
				case 'zzp_factoren':
					$factoren = $zzp->setFactoren();
					break;
			}

			$errors = $zzp->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			//$factoren =  $zzp->factoren();
			//$errors = false; //no errors
		}

		//form maken
		//$formdata = $formbuidler->table( 'zzp_factoren' )->data( $factoren )->errors( $errors )->build();
		//$this->smarty->assign('formdata', $formdata);

		$this->smarty->assign('zzp', $zzp);
		$this->smarty->display('crm/zzp/dossier/algemeneinstellingen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Persoonsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function persoonsgegevens( $zzp_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init werknemer object
		$zzp = new Zzp( $zzp_id );
		
		//set gegevens
		if( isset($_POST['set']) )
		{
			$persoonsgegevens = $zzp->setPersoonsgegevens();
			$errors = $zzp->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $zzp->documenten_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/zzp/dossier/documenten/' . $zzp->zzp_id ,'location');

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$persoonsgegevens =  $zzp->persoonsgegevens();
			$errors = false; //no errors
		}

		//form opbouwen
		$formdata = $formbuidler->table( 'zzp_persoonsgegevens' )->data( $persoonsgegevens )->errors( $errors )->build();
		$formdata['uitzender_id']['value'] = $zzp->uitzenderID();

		$this->smarty->assign('formdata', $formdata);
		
		//show(Codering::listNationaliteiten());
		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('list', array( 'nationaliteiten' => Codering::listNationaliteiten(), 'landen' => Codering::listLanden() ));
		$this->smarty->assign('zzp', $zzp);
		$this->smarty->display('crm/zzp/dossier/persoonsgegevens.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function bedrijfsgegevens( $zzp_id = NULL )
	{
		//historische data ophalen
		//$log = new History();
		//$data = $log->table( 'inlener_bedrijfsgegevens')->index( array('zzp_id' => 3 ) )->data();
		
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init inlener object
		$zzp = new Zzp( $zzp_id );
		
		//set bedrijfsgegevens
		if( isset($_POST['set']) )
		{
			$bedrijfsgevens = $zzp->setBedrijfsgegevens();
			$errors = $zzp->errors();
			
			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $zzp->persoonsgegevens_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/zzp/dossier/persoonsgegevens/' . $zzp->zzp_id ,'location');
				
				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$bedrijfsgevens =  $zzp->bedrijfsgegevens();
			$errors = false; //no errors
		}
		
		$formdata = $formbuidler->table( 'zzp_bedrijfsgegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		$formdata['uitzender_id']['value'] = $zzp->uitzenderID();
		
		$this->smarty->assign('formdata', $formdata);
		
		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('zzp', $zzp);
		$this->smarty->display('crm/zzp/dossier/bedrijfsgegevens.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function documenten( $zzp_id = NULL )
	{
		//init werknemer object
		$zzp = new Zzp( $zzp_id );
		
		//id bewijs is appart object
		$idbewijs = new IDbewijs();
		$idbewijs->werknemer( $zzp_id );
		
		//TODO weghalen
		/*
		if( isset($_GET['contract']) )
		{
			$template = new Template( 7 ); //4 is samenwerkingsovereenkomst
			$document = DocumentFactory::createFromTemplateObject( $template );
			$document->setZzpID( $zzp_id )->build()->pdf();
			
			redirect( $this->config->item( 'base_url' ) . 'crm/zzp/dossier/documenten/' . $zzp_id ,'location' );
		}*/
		
		//ID opslaan vanuit wizard
		if( isset($_POST['set_wizard']) )
		{
			$idbewijs->setVervalDatum( $_POST['vervaldatum'] );
			
			if( $idbewijs->errors() !== false)
				$this->smarty->assign('msg', msg('warning', $idbewijs->errors() ));
			else
			{
				//check of alles compleet is
				if( $idbewijs->complete() )
				{
					//cache value, wordt te vroeg bijgewerkt
					$documenten_complete_cache = $zzp->documenten_complete;
					//set documenten als complete
					$zzp->documenten_complete();
					
					//nieuwe aanmelding doorzetten naar volgende pagina
					if( $documenten_complete_cache != 1 )
						redirect( $this->config->item( 'base_url' ) . 'crm/zzp/dossier/factuurgegevens/' . $zzp->zzp_id, 'location' );
				}
				else
				{
					//set documenten als NIET complete
					$zzp->documenten_complete( false );
					$this->smarty->assign( 'msg', msg( 'warning', 'U moet een kopie van het ID bewijs uploaden voordat u verder kunt' ) );
				}
			}
		}
		
		$this->smarty->assign('zzp', $zzp);

		$this->smarty->assign('uittreksel', $zzp->uittreksel() );
		$this->smarty->assign('id_voorkant', $idbewijs->url( 'voorkant' ));
		$this->smarty->assign('id_achterkant', $idbewijs->url( 'achterkant' ));
		$this->smarty->assign('vervaldatum', $idbewijs->vervaldatum() );

		//afwijkende template voor
		if( $zzp->complete != 1 )
			$this->smarty->display('crm/zzp/dossier/documenten_wizard.tpl');
		else
			$this->smarty->display('crm/zzp/dossier/documenten.tpl');
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// uittreksel kvk downloaden
	//-----------------------------------------------------------------------------------------------------------------
	public function uittrekselkvk( $zzp_id = NULL )
	{
		//init zzp object
		$zzp = new Zzp( $zzp_id );
		
		$uittreksel = $zzp->uittreksel( 'download' );
	}
	
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// plaatsingen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function plaatsingen( $zzp_id = NULL )
	{
		//init werknemer object
		$zzp = new Zzp( $zzp_id );
		$inlenerGroup = new InlenerGroup();
		$plaatsingGroup = new PlaatsingGroup();
		
		//Uitzender wijzigen
		if( isset($_POST['set']) )
		{
			//switch for verschillende forms
			switch ($_POST['set']){
				//extra factoren toevoegen
				case 'set_uitzender':
					$zzp->setUitzender( $_POST['uitzender_id'] );
					if( $zzp->errors() === false )
						$this->smarty->assign( 'msg', msg( 'success', 'Uitzender voor werknemer gewijzigd' ) );
					else
						$this->smarty->assign( 'msg', msg( 'warning', $zzp->errors() ) );
					break;
				case 'set_plaatsing':
					$plaatsing = new Plaatsing();
					$plaatsing->zzpId($zzp_id);
					$plaatsing->add( $_POST );
					if( $plaatsing->errors() !== false )
						$this->smarty->assign( 'msg', msg( 'warning', $plaatsing->errors() ) );
					
					break;
			}
		}

		//plaatsing verwijderen
		if( isset($_GET['delplaatsing']) )
		{
			$plaatsing = new Plaatsing( $_GET['delplaatsing'] );
			$plaatsing->delete();
		}
		
		$inleners = $inlenerGroup->uitzender( $zzp->uitzenderID() )->all();

		//$this->smarty->assign('plaatsingen', $plaatsingen );
		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('inleners', $inleners );
		$this->smarty->assign('zzp', $zzp);
		$this->smarty->assign('plaatsingen', $plaatsingGroup->zzp($zzp_id)->all() );
		$this->smarty->assign('zzp_uitzender', $zzp->uitzender());
		$this->smarty->display('crm/zzp/dossier/plaatsingen.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function factuurgegevens( $zzp_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();
		
		//init inlener object
		$zzp = new Zzp( $zzp_id );
		
		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$factuurgegevens = $zzp->setFactuurgegevens();
			$errors = $zzp->errors();
			
			//cache
			$complete = $zzp->complete;
			
			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if($complete != 1 && $zzp->factuurgegevens_complete == 1 )
					redirect( $this->config->item( 'base_url' ) . 'crm/zzp/overzicht/success' ,'location' );
				
				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$factuurgegevens =  $zzp->factuurgegevens();
			$errors = false; //no errors
		}
		
		$formdata = $formbuidler->table( 'zzp_factuurgegevens' )->data( $factuurgegevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);
		
		$this->smarty->assign('zzp', $zzp);
		$this->smarty->display('crm/zzp/dossier/factuurgegevens.tpl');
	}
	

	//-----------------------------------------------------------------------------------------------------------------
	// notities pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function notities( $zzp_id = NULL )
	{
		//init werknemer object
		$zzp = new Zzp( $zzp_id );

		$this->smarty->assign('zzp', $zzp);
		$this->smarty->display('crm/zzp/dossier/notities.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// facturen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function facturen( $zzp_id = NULL )
	{
		
		if(isset($_GET['g']))
		{
			$factuur  = new \models\facturatie\FactuurFactory();
			$factuur->_pdfZzp($_GET['g']);
		}

		//init inlener object
		$zzp = new Zzp( $zzp_id );
		$facturen = $zzp->facturen();
		
		$this->smarty->assign('zzp', $zzp);
		$this->smarty->assign('facturen', $facturen);
		$this->smarty->assign( 'jaren', $zzp->jarenArrayFacturen() );
		$this->smarty->display('crm/zzp/dossier/facturen.tpl');
	}
	
	
}
