<?php

use models\documenten\IDbewijs;
use models\forms\Formbuilder;
use models\uitzenders\UitzenderGroup;
use models\utils\Carbagecollector;
use models\utils\Codering;
use models\utils\VisitsLogger;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingCollection;
use models\werknemers\Werknemer;

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
		$log->logCRMVisit( 'werknemer', $this->uri->segment(5));
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
			if( $werknemer->gegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/werknemers/dossier/gegevens/' . $werknemer_id ,'location');
			if( $werknemer->documenten_complete != 1 ) redirect($this->config->item('base_url') . 'crm/werknemers/dossier/documenten/' . $werknemer_id ,'location');
			//if( $werknemer->contactpersoon_complete != 1 ) redirect($this->config->item('base_url') . 'crm/werknemers/dossier/contactpersonen/' . $werknemer_id ,'location');
		}

		//show($werknemers);

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->assign('gegevens', $werknemer->gegevens());
		$this->smarty->display('crm/werknemers/dossier/overzicht.tpl');
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
		if( isset($_POST['set']) )
		{
			//sitch
			switch ($_POST['set']) {
				case 'werknemers_factoren':
					$factoren = $werknemer->setFactoren();
					break;
			}

			$errors = $werknemer->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			//$factoren =  $werknemer->factoren();
			//$errors = false; //no errors
		}

		//form maken
		//$formdata = $formbuidler->table( 'werknemers_factoren' )->data( $factoren )->errors( $errors )->build();
		//$this->smarty->assign('formdata', $formdata);

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/algemeneinstellingen.tpl');
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
		if( isset($_POST['set']) )
		{
			$bedrijfsgevens = $werknemer->setGegevens();
			$errors = $werknemer->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $werknemer->documenten_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/werknemers/dossier/documenten/' . $werknemer->werknemer_id ,'location');

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$bedrijfsgevens =  $werknemer->gegevens();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'werknemers_gegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);
		
		//show(Codering::listNationaliteiten());
		$this->smarty->assign('list', array( 'nationaliteiten' => Codering::listNationaliteiten(), 'landen' => Codering::listLanden() ));
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/gegevens.tpl');
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
					//set documenten als complete
					$werknemer->documenten_complete();
					
					//nieuwe aanmelding doorzetten naar volgende pagina
					if( $werknemer->documenten_complete != 1 )
						redirect( $this->config->item( 'base_url' ) . 'crm/werknemers/dossier/documenten/' . $werknemer->werknemer_id, 'location' );
				}
				else
				{
					//set documenten als NIET complete
					$werknemer->documenten_complete( false );
					$this->smarty->assign( 'msg', msg( 'warning', 'U moet een kopie van het ID bewijs uploaden voordat u verder kunt' ) );
				}
			}
		}
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->assign('id_voorkant', $idbewijs->url( 'voorkant' ));
		$this->smarty->assign('id_achterkant', $idbewijs->url( 'achterkant' ));
		$this->smarty->assign('vervaldatum', $idbewijs->vervaldatum() );
		
		//afwijkende template voor
		if( $werknemer->complete != 1 )
			$this->smarty->display('crm/werknemers/dossier/documenten_wizard.tpl');
		else
			$this->smarty->display('crm/werknemers/dossier/documenten.tpl');
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// plaatsingen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function plaatsingen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		//ID opslaan vanuit wizard
		if( isset($_POST['set']) )
		{
			//switch for verschillende forms
			switch ($_POST['set']){
				//extra factoren toevoegen
				case 'set_uitzender':
					$werknemer->setUitzender( $_POST['uitzender_id'] );
					if( $werknemer->errors() === false )
						$this->smarty->assign( 'msg', msg( 'success', 'Uitzender voor werknemer gewijzigd' ) );
					else
						$this->smarty->assign( 'msg', msg( 'warning', 'U moet een kopie van het ID bewijs uploaden voordat u verder kunt' ) );
					break;
			}
		}

		
		//$this->smarty->assign('plaatsingen', $plaatsingen );
		$this->smarty->assign('uitzenders', UitzenderGroup::list() );
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->assign('werknemer_uitzender', $werknemer->uitzender());
		$this->smarty->display('crm/werknemers/dossier/plaatsingen.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// reserveringen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function reserveringen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/documenten.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// urenbriefjes pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function urenbriefjes( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/documenten.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonstroken pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function loonstroken( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/documenten.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// loonbeslagen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function loonbeslagen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/documenten.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// ziekmeldingen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function ziekmeldingen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/documenten.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// notities pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function notities( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/notities.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen dienstverband
	//-----------------------------------------------------------------------------------------------------------------
	public function dienstverband( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/dienstverband.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen dienstverband
	//-----------------------------------------------------------------------------------------------------------------
	public function verloning( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/verloning.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// instellingen dienstverband
	//-----------------------------------------------------------------------------------------------------------------
	public function etregeling( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );
		$et = $werknemer->etregeling();
		
		//del bsn
		if( isset($_GET['delbsn']) )
		{
			$et->delbsn();
			redirect($this->config->item('base_url') . '/crm/werknemers/dossier/etregeling/' . $werknemer_id ,'location');
		}
		
		
		$this->smarty->assign('bsn',  $et->fileBsn() );
		$this->smarty->assign('landen',  Codering::listLanden() );
		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/etregeling.tpl');
	}

}
