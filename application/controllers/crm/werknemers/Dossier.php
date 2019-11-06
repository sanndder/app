<?php

use models\Documenten\IDbewijs;
use models\forms\Formbuilder;
use models\utils\Carbagecollector;
use models\utils\Codering;
use models\utils\VisitsLogger;
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
		if( $this->user->user_type != 'werkgever' )forbidden();

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
			if( $werknemer->factuurgegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/werknemers/dossier/factuurgegevens/' . $werknemer_id ,'location');
			if( $werknemer->contactpersoon_complete != 1 ) redirect($this->config->item('base_url') . 'crm/werknemers/dossier/contactpersonen/' . $werknemer_id ,'location');
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
			$factoren =  $werknemer->factoren();
			$errors = false; //no errors
		}

		//form maken
		$formdata = $formbuidler->table( 'werknemers_factoren' )->data( $factoren )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

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

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->assign('id_voorkant', $idbewijs->url( 'voorkant' ));
		
		//afwijkende template voor
		if( $werknemer->documenten_complete != 1 )
			$this->smarty->display('crm/werknemers/dossier/documenten_wizard.tpl');
		else
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
	// facturen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function facturen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/facturen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// werknemers pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemers( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/werknemers.tpl');
	}

}
