<?php

use models\forms\Formbuilder;
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
			if( $werknemer->emailadressen_complete != 1 ) redirect($this->config->item('base_url') . 'crm/werknemers/dossier/emailadressen/' . $werknemer_id ,'location');
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

		//TEMP upload ID voor scan
		if( isset($_POST['scan']) )
		{
			$this->load->model('upload_model', 'uploadfiles');
			$this->uploadfiles->setUploadDir( 'werknemer/id' );
			$this->uploadfiles->setPrefix( 'id_' );
			$this->uploadfiles->uploadfiles();

			$file_array = $this->uploadfiles->getFileArray();

			//img class laden om plaatje te resizen
			$image = new \models\File\Img( $file_array );
			$image->setMaxWidthHeight( 600, 400 )->setQuality(80)->resize();

			//make api call
			$api = new \models\Api\Scan();
			$carddata = $api->setFile( $file_array )->call();
			show($carddata);
			$this->smarty->assign('carddata', $carddata );

		}

		//set gegevens
		if( isset($_POST['set']) )
		{
			$bedrijfsgevens = $werknemer->setBedrijfsgegevens();
			$errors = $werknemer->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $werknemer->emailadressen_complete != 1 )
				{
					redirect( $this->config->item('base_url') . 'crm/werknemers/dossier/emailadressen/' . $werknemer->werknemer_id ,'location');
					die();
				}

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

		//show($werknemer);

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/gegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function Factuurgegevens( $werknemer_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		//set gegevens
		if( isset($_POST['set'] ))
		{
			$factuurgegevens = $werknemer->setFactuurgegevens();
			$errors = $werknemer->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $werknemer->contactpersoon_complete != 1 )
				{
					redirect( $this->config->item('base_url') . 'crm/werknemers/dossier/contactpersonen/' . $werknemer->werknemer_id ,'location');
					die();
				}

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$factuurgegevens =  $werknemer->factuurgegevens();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'werknemers_factuurgegevens' )->data( $factuurgegevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($formdata);

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/factuurgegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function Emailadressen( $werknemer_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		//set gegevens
		if( isset($_POST['set'] ))
		{
			$emailadressen = $werknemer->setEmailadressen();
			$errors = $werknemer->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $werknemer->factuurgegevens_complete != 1 )
				{
					redirect( $this->config->item('base_url') . 'crm/werknemers/dossier/factuurgegevens/' . $werknemer->werknemer_id ,'location');
					die();
				}

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$emailadressen =  $werknemer->emailadressen();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'werknemers_emailadressen' )->data( $emailadressen )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($formdata);

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/emailadressen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function contactpersonen( $werknemer_id = NULL )
	{
		//init werknemer object
		$werknemer = new Werknemer( $werknemer_id );

		$contactpersonen = $werknemer->contactpersonen();
		$this->smarty->assign('contactpersonen', $contactpersonen);

		$this->smarty->assign('werknemer', $werknemer);
		$this->smarty->display('crm/werknemers/dossier/contactpersonen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function documenten( $werknemer_id = NULL )
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
