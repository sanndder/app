<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Dossier extends MY_Controller
{

	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();


	}

	//--------------------------------------------------------------------------
	// Overzicht pagina
	//--------------------------------------------------------------------------
	public function overzicht( $uitzender_id )
	{
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		//show($uitzenders);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/overzicht.tpl');
	}


	//--------------------------------------------------------------------------
	// Bedrijfsgegevens
	//--------------------------------------------------------------------------
	public function bedrijfsgegevens( $uitzender_id )
	{
		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$bedrijfsgevens = $uitzender->setBedrijfsgegevens();
			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
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

		//show($uitzenders);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/bedrijfsgegevens.tpl');
	}


	//--------------------------------------------------------------------------
	// Factuurgegevens
	//--------------------------------------------------------------------------
	public function Factuurgegevens( $uitzender_id )
	{
		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$factuurgegevens = $uitzender->setFactuurgegevens();
			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
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


	//--------------------------------------------------------------------------
	// Factuurgegevens
	//--------------------------------------------------------------------------
	public function Emailadressen( $uitzender_id )
	{
		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$emailadressen = $uitzender->setEmailadressen();
			$errors = $uitzender->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
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


	//--------------------------------------------------------------------------
	// documenten pagina
	//--------------------------------------------------------------------------
	public function contactpersonen( $uitzender_id )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		$contactpersonen = $uitzender->contactpersonen();
		$this->smarty->assign('contactpersonen', $contactpersonen);

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/contactpersonen.tpl');
	}


	//--------------------------------------------------------------------------
	// documenten pagina
	//--------------------------------------------------------------------------
	public function documenten( $uitzender_id )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/documenten.tpl');
	}


	//--------------------------------------------------------------------------
	// notities pagina
	//--------------------------------------------------------------------------
	public function notities( $uitzender_id )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/notities.tpl');
	}


	//--------------------------------------------------------------------------
	// facturen pagina
	//--------------------------------------------------------------------------
	public function facturen( $uitzender_id )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/facturen.tpl');
	}


	//--------------------------------------------------------------------------
	// inleners pagina
	//--------------------------------------------------------------------------
	public function inleners( $uitzender_id )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/inleners.tpl');
	}


	//--------------------------------------------------------------------------
	// werknemers pagina
	//--------------------------------------------------------------------------
	public function werknemers( $uitzender_id )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );

		$this->smarty->assign('uitzender', $uitzender);
		$this->smarty->display('crm/uitzenders/dossier/werknemers.tpl');
	}

}
