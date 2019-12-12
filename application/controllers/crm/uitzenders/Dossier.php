<?php

use models\forms\Formbuilder;
use models\inleners\InlenerGroup;
use models\uitzenders\Uitzender;
use models\utils\VisitsLogger;
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
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'external' )forbidden();

		//method naar smarty
		$this->smarty->assign('method', $this->router->method);
		
		//log visit
		$log = new VisitsLogger();
		$log->logCRMVisit( 'uitzender', $this->uri->segment(5) );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Dossier beveiligen
	//-----------------------------------------------------------------------------------------------------------------
	public function checkaccess( Uitzender $uitzender )
	{
		//niet checken bij nieuwe aanmelding
		if( $uitzender->uitzender_id != 0 )
		{
			//wanneer uitzender compleet is, dan mag er nooit extern benaderd worden
			if( $uitzender->complete == 1 && $this->user->user_type == 'external' )
				forbidden();
			//externe users mogen alleen hun eigen aangemaakt uitzender bekijken
			if( $uitzender->aanmeld_ip !== $_SERVER['REMOTE_ADDR'] && $this->user->user_type == 'external' )
				forbidden();
		}
		//check for redirect bij terugkeren aanmelding
		else
		{
			//wanneer er een eerdere aanmelding is dan aanroepen overzicht pagina, deze zet weer door naar de juiste pagina
			if( get_cookie( 'new_uitzender_id' ) !== NULL )
				$this->overzicht( get_cookie( 'new_uitzender_id' ) );
		}

		//redirect naar bedankt voor aangemelding
		if( $this->user->user_type == 'external' && $uitzender->bedrijfsgegevens_complete == 0 && $uitzender->emailadressen_complete == 0 && $uitzender->factuurgegevens_complete == 0 && $uitzender->contactpersoon_complete == 0 )
			redirect( $this->config->item( 'base_url' ) . 'crm/uitzenders/dossier/bedankt' ,'location' );
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Bedankt voor aanmelding
	//-----------------------------------------------------------------------------------------------------------------
	public function bedankt()
	{
		$this->smarty->display('crm/uitzenders/dossier/bedankt.tpl');
	}

	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function overzicht( $uitzender_id = NULL )
	{
		$uitzender = new Uitzender( $uitzender_id );

		//redirect indien nodig
		if( $uitzender->complete == 0 )
		{
			if( $uitzender->bedrijfsgegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/uitzenders/dossier/bedrijfsgegevens/' . $uitzender_id ,'location');
			if( $uitzender->emailadressen_complete != 1 ) redirect($this->config->item('base_url') . 'crm/uitzenders/dossier/emailadressen/' . $uitzender_id ,'location');
			if( $uitzender->factuurgegevens_complete != 1 ) redirect($this->config->item('base_url') . 'crm/uitzenders/dossier/factuurgegevens/' . $uitzender_id ,'location');
			if( $uitzender->contactpersoon_complete != 1 ) redirect($this->config->item('base_url') . 'crm/uitzenders/dossier/contactpersonen/' . $uitzender_id ,'location');
		}

		//show($uitzenders);

		$this->smarty->assign('bedrijfsgegevens', $uitzender->bedrijfsgegevens());
		$this->smarty->assign('emailadressen', $uitzender->emailadressen());

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/overzicht.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Algemene instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function algemeneinstellingen( $uitzender_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);
		
		//del logo
		if( isset($_GET['dellogo']) )
		{
			$uitzender->delLogo();
			redirect($this->config->item('base_url') . '/crm/uitzenders/dossier/algemeneinstellingen/' . $uitzender_id ,'location');
		}

		//del handtekening
		if( isset($_GET['delhandtekening']) )
		{
			$uitzender->delHandtekening();
			redirect($this->config->item('base_url') . '/crm/uitzenders/dossier/algemeneinstellingen/' . $uitzender_id ,'location');
		}

		//set bedrijfsgegevens
		if( isset($_POST['set']) )
		{
			//sitch
			switch ($_POST['set']) {
				case 'uitzenders_factoren':
					$factoren = $uitzender->setFactoren();
					break;
			}

			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$factoren =  $uitzender->factoren();
			$errors = false; //no errors
		}

		//form maken
		$formdata = $formbuidler->table( 'uitzenders_factoren' )->data( $factoren )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/algemeneinstellingen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function bedrijfsgegevens( $uitzender_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);

		//set bedrijfsgegevens
		if( isset($_POST['set']) )
		{
			$bedrijfsgevens = $uitzender->setBedrijfsgegevens();
			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $uitzender->emailadressen_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/uitzenders/dossier/emailadressen/' . $uitzender->uitzender_id ,'location');

				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$bedrijfsgevens =  $uitzender->bedrijfsgegevens();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'uitzenders_bedrijfsgegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($uitzender);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/bedrijfsgegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function factuurgegevens( $uitzender_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$factuurgegevens = $uitzender->setFactuurgegevens();
			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $uitzender->contactpersoon_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/uitzenders/dossier/contactpersonen/' . $uitzender->uitzender_id ,'location');


				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$factuurgegevens =  $uitzender->factuurgegevens();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'uitzenders_factuurgegevens' )->data( $factuurgegevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($formdata);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/factuurgegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Factuurgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function emailadressen( $uitzender_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$emailadressen = $uitzender->setEmailadressen();
			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
			{
				//nieuwe aanmelding doorzetten naar volgende pagina
				if( $uitzender->factuurgegevens_complete != 1 )
					redirect( $this->config->item('base_url') . 'crm/uitzenders/dossier/factuurgegevens/' . $uitzender->uitzender_id ,'location');


				//bestaande uiztender melding tonen
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			}
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$emailadressen =  $uitzender->emailadressen();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'uitzenders_emailadressen' )->data( $emailadressen )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($formdata);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/emailadressen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function contactpersonen( $uitzender_id = NULL )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);

		$contactpersonen = $uitzender->contactpersonen();
		$this->smarty->assign('contactpersonen', $contactpersonen);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/contactpersonen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// documenten pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function documenten( $uitzender_id = NULL )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/documenten.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// notities pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function notities( $uitzender_id = NULL )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess( $uitzender );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/notities.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// facturen pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function facturen( $uitzender_id = NULL )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/facturen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Uitzenders pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function inleners( $uitzender_id = NULL )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);
		
		//inleners voor deze uitzender
		$inlenergroup = new InlenerGroup();
		$inleners = $inlenergroup->all( array('uitzender_id' => $uitzender_id ) );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->assign('inleners', $inleners);
		$this->smarty->display('crm/uitzenders/dossier/inleners.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// werknemers pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemers( $uitzender_id = NULL )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );
		$this->checkaccess($uitzender);
		
		$werknemergroup = new WerknemerGroup();
		$werknemers = $werknemergroup->all( array('uitzender_id' => $uitzender_id) );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->assign('werknemers', $werknemers);
		$this->smarty->display('crm/uitzenders/dossier/werknemers.tpl');
	}

}
