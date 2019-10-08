<?php

use models\Facturatie\Betaaltermijnen;
use models\forms\Formbuilder;
use models\Inleners\Inlener;
use models\Verloning\Urentypes;

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

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/overzicht.tpl');
	}



	//-----------------------------------------------------------------------------------------------------------------
	// Algemene instellingen
	//-----------------------------------------------------------------------------------------------------------------
	public function algemeneinstellingen( $inlener_id = NULL )
	{
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init inlener object
		$inlener = new Inlener( $inlener_id );

		//del logo
		if( isset($_GET['dellogo']) )
		{
			$inlener->delLogo();
			redirect($this->config->item('base_url') . '/crm/inleners/dossier/algemeneinstellingen/' . $inlener_id ,'location');
		}

		//del handtekening
		if( isset($_GET['delhandtekening']) )
		{
			$inlener->delHandtekening();
			redirect($this->config->item('base_url') . '/crm/inleners/dossier/algemeneinstellingen/' . $inlener_id ,'location');
		}

		//set bedrijfsgegevens
		if( isset($_POST['set']) )
		{
			//sitch
			switch ($_POST['set']) {
				case 'inleners_factoren':
					$factoren = $inlener->setFactoren();
					break;
			}

			$errors = $inlener->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$factoren =  $inlener->factoren();
			$errors = false; //no errors
		}

		//form maken
		$formdata = $formbuidler->table( 'inleners_factoren' )->data( $factoren )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/algemeneinstellingen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function bedrijfsgegevens( $inlener_id = NULL )
	{
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
				{
					redirect( $this->config->item('base_url') . 'crm/inleners/dossier/emailadressen/' . $inlener->inlener_id ,'location');
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
			$bedrijfsgevens =  $inlener->bedrijfsgegevens();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'inleners_bedrijfsgegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		//show($inlener);

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
				{
					redirect( $this->config->item('base_url') . 'crm/inleners/dossier/contactpersonen/' . $inlener->inlener_id ,'location');
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
				{
					redirect( $this->config->item('base_url') . 'crm/inleners/dossier/factuurgegevens/' . $inlener->inlener_id ,'location');
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
		//load the formbuilder
		$formbuidler = new Formbuilder();

		//init objects
		$inlener = new Inlener( $inlener_id );		
		$urentypes = new Urentypes();
		
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
		
		//set data
		if( isset($_POST['set']) )
		{
			//switch for each tab
			switch ($_POST['set']) {
				//extra factoren toevoegen
				case 'inleners_factoren': $inlener->setFactoren();
				//urentype aan inlener koppelen
				case 'add_urentype_to_inlener': $urentypes->addUrentypeToInlener( $inlener_id, $_POST );
			}

			$errors = $inlener->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$errors = false; //no errors
		}

		
		
		$this->smarty->assign( 'urentypes', $urentypes->getAll() );
		$this->smarty->assign( 'factoren', $inlener->factoren() );

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
	// inleners pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function inleners( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/inleners.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// werknemers pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function werknemers( $inlener_id = NULL )
	{
		//init inlener object
		$inlener = new Inlener( $inlener_id );

		$this->smarty->assign('inlener', $inlener);
		$this->smarty->display('crm/inleners/dossier/werknemers.tpl');
	}

}
