<?php

use models\documenten\Document;
use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\documenten\TemplateGroup;
use models\forms\Formbuilder;
use models\instellingen\Minimumloon;
use models\instellingen\Feestdagen;
use models\verloning\Urentypes;
use models\verloning\Vergoeding;
use models\verloning\VergoedingGroup;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Werkgever extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		//alleen werkgever
		if(	$this->user->user_type != 'werkgever' )forbidden();
		
		//entiteiten afhandelen
		$this->smarty->assign('entiteiten', $this->werkgever->listEntiteiten() );
		$this->smarty->assign('replace', 'entity_id='.$this->session->entiteit_id );
	}


	//-----------------------------------------------------------------------------------------------------------------
	// index doorzetten
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		redirect($this->config->item('base_url') . 'instellingen/werkgever/bedrijfsgegevens', 'location');
	}

	//-----------------------------------------------------------------------------------------------------------------
	// aanpassen bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function minimumloon()
	{
		//$werkgever = new models\Werkgever();
		$formbuidler = new Formbuilder();

		//minimumloon class
		$minimumloon = new Minimumloon();

		//set minimumloon
		if( isset($_POST['set'] ))
		{
			$minimumloon_data = $minimumloon->updateMinimumloon();
			$errors = $minimumloon->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$minimumloon_data = $minimumloon->getData();
			$errors = false; //no errors
		}

		$formdata = $formbuidler->table( 'settings_minimumloon' )->data( $minimumloon_data )->errors( $errors )->build();
		$this->smarty->assign('formdata', $formdata);

		$this->smarty->display('instellingen/werkgever/minimumloon.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// aanpassen urentypes
	//-----------------------------------------------------------------------------------------------------------------
	public function urentypes()
	{
		//urentypes class
		$urentypes = new Urentypes();
		$urentypes_categorien = $urentypes->categorien();
		
		//del urentype
		if( isset($_POST['del'] ))
		{
			//msg
			if( $urentypes->delete( $_POST['del'] ) )
				$this->smarty->assign('msg', msg('success', 'Urentype verwijderd!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Urentype kon niet worden verwijderd!'));
		}
		
		//set urentype
		if( isset($_POST['set'] ))
		{
			//add
			$urentypes->add();

			//then msg
			if($urentypes->errors() === false )
				$this->smarty->assign('msg', msg('success', 'Urentype toegevoegd!'));
			else
				$this->smarty->assign( 'errors', $urentypes->errors() );
		}
		
		$urentypes_array = $urentypes->getAll();
		
		//show($urentypes_array);
		$this->smarty->assign('urentypes_array', $urentypes_array);
		$this->smarty->assign('urentypes_categorien', $urentypes_categorien);
		
		$this->smarty->display('instellingen/werkgever/urentypes.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// aanpassen urentypes
	//-----------------------------------------------------------------------------------------------------------------
	public function vergoedingen()
	{
		//urentypes class
		$vergoeding = new Vergoeding();
		$vergoedingen = new VergoedingGroup();
		
		//del urentype
		if( isset($_POST['del'] ))
		{
			//msg
			if( $vergoeding->delete( $_POST['del'] ) )
				$this->smarty->assign('msg', msg('success', 'Vergoeding verwijderd!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Vergoeding kon niet worden verwijderd!'));
		}
		
		//set urentype
		if( isset($_POST['set'] ))
		{
			//add
			$vergoeding->add();
			
			//then msg
			if($vergoeding->errors() === false )
				$this->smarty->assign('msg', msg('success', 'Vergoeding toegevoegd!'));
			else
				$this->smarty->assign( 'errors', $vergoeding->errors() );
		}
		
		
		//show($urentypes_array);
		$this->smarty->assign('vergoedingen', $vergoedingen->all() );
		$this->smarty->display('instellingen/werkgever/vergoedingen.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// aanpassen feestdagen
	//-----------------------------------------------------------------------------------------------------------------
	public function feestdagen()
	{
		//$werkgever = new models\Werkgever();
		$formbuidler = new Formbuilder();
		
		//minimumloon class
		$feestdagen = new Feestdagen();
		
		//set urentype
		if( isset($_POST['del'] ))
		{
			//msg
			if( $feestdagen->delete( $_POST['del'] ) )
				$this->smarty->assign('msg', msg('success', 'Feestdag verwijderd!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Feestdag kon niet worden verwijderd!'));
		}
		
		//set feestdag
		if( isset($_POST['set'] ))
		{
			$feestdagen->add();
			
			//msg
			if( $feestdagen->errors() === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('errors', $feestdagen->errors() );
		}
		
		//haal alle feestdagen voor 1 jaar terug en volgende op
		$feestdagen_list = $feestdagen->getAll();
		$this->smarty->assign('vandaag', date('Y-m-d') );
		$this->smarty->assign('ditjaar', date('Y') );
		$this->smarty->assign('dagnaam', getDagNaam() );
		$this->smarty->assign('feestdagen_list', $feestdagen_list);
		
		$this->smarty->display('instellingen/werkgever/feestdagen.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// aanpassen bedrijfsgegevens
	//-----------------------------------------------------------------------------------------------------------------
	public function bedrijfsgegevens()
	{
		//$werkgever = new models\Werkgever();
		$formbuidler = new models\forms\Formbuilder();
		
		//del logo
		if( isset($_GET['dellogo']) )
		{
			$this->werkgever->delLogo();
			redirect($this->config->item('base_url') . '/instellingen/werkgever/bedrijfsgegevens' ,'location');
		}
		
		//del handtekening
		if( isset($_GET['delhandtekening']) )
		{
			$this->werkgever->delHandtekening();
			redirect($this->config->item('base_url') . '/instellingen/werkgever/bedrijfsgegevens' ,'location');
		}

		//set bedrijfsgegevens
		if( isset($_POST['set'] ))
		{
			$bedrijfsgevens = $this->werkgever->setBedrijfsgegevens();
			$errors = $this->werkgever->errors();

			//msg
			if( $errors === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen!'));
			else
				$this->smarty->assign('msg', msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!'));
		}
		else
		{
			$bedrijfsgevens = $this->werkgever->bedrijfsgegevens();
			$errors = false; //no errors
		}

		//TODO: remove
		if(isset($_GET['img']))
		{
			$this->werkgever->handtekening();
		}


		$formdata = $formbuidler->table( 'werkgever_bedrijfsgegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		//show($formdata);
		$this->smarty->assign('formdata', $formdata);
		$this->smarty->assign('handtekening', $this->werkgever->handtekening( 'url' ) );
		$this->smarty->assign('logo', $this->werkgever->logo( 'url' ) );

		$this->smarty->display('instellingen/werkgever/bedrijfsgegevens.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// bankrekeningen instellen
	//-----------------------------------------------------------------------------------------------------------------
	public function bankrekeningen()
	{
		//new formbuilder
		$formbuidler = new Formbuilder();

		//eerst alle rekeningen laden
		$bankrekeningen_database = $this->werkgever->bankrekeningen();

		//altijd lege rekening toevoegen
		$bankrekening_leeg[0] = array('omschrijving' => '', 'iban' => '');

		//samenvoegen
		$bankrekeningen = $bankrekening_leeg + $bankrekeningen_database;

		//bankrekeningen in juiste format gieten
		$bankrekeningen = $formbuidler->table( 'werkgever_bankrekeningen' )->data( $bankrekeningen )->buildFromArray();

		//del bankrekening
		if( isset($_POST['del'] ))
		{
			//welk id is geklikt
			$id = $_POST['del'];
			if( $this->werkgever->delBankrekening( $id ) === true )
			{
				unset($bankrekeningen[$id]);
				$this->smarty->assign('msg', msg('success', 'Bankrekening verwijderd'));
			}
			else
				$this->smarty->assign('msg['.$id.']', msg('warning', 'Bankrekening kon niet worden verwijderd'));
		}

		//set bankrekening
		if( isset($_POST['set'] ))
		{
			//welk id is geklikt
			$id = key($_POST['set']);

			$post = $this->werkgever->setBankrekening();
			$insert_id = $this->werkgever->getInsertId();
			$errors = $this->werkgever->errors();

			//msg
			if( $errors === false )
			{
				//success msg
				$this->session->set_flashdata('msg', 'Wijzigingen opgeslagen');
				$this->session->set_flashdata('id', $insert_id);

				//redirect, save a lot of trouble, refresh gives errors
				redirect($this->config->item('base_url') . 'instellingen/werkgever/bankrekeningen' ,'location');

			}
			else
			{
				//fout toevoegen aan aangeklikte bankrekening
				$bankrekeningen[$id] = $formbuidler->table( 'werkgever_bankrekeningen' )->data( $post )->errors( $errors )->build();

				//error alleen voor aangeklikte bankrekening
				$this->smarty->assign('msg', array( $id => msg('warning', 'Wijzigingen konden niet worden opgeslagen, controleer uw invoer!')));
			}
		}

		//zijn er flash messages?
		if( $this->session->flashdata('msg') != NULL )
			$this->smarty->assign( 'msg', array( $this->session->flashdata('id') => msg( 'success', $this->session->flashdata('msg') ) ));


		//naar template
		$this->smarty->assign('bankrekeningen', $bankrekeningen);

		$this->smarty->display('instellingen/werkgever/bankrekeningen.tpl');
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// overzicht documenten
	//-----------------------------------------------------------------------------------------------------------------
	public function documentenoverzicht()
	{
		//nieuwe template
		if( isset($_POST['set']) )
		{
			$template = new Template();
			$template->new( $_POST );
			if( $template->errors() === false )
				redirect( $this->config->item( 'base_url' ) . 'instellingen/werkgever/documentenedit/' . $template->id() ,'location' );
			else
				$this->smarty->assign('msg', msg('warning', $template->errors() ));
		}
		
		$templategroup = new TemplateGroup();
		
		$this->smarty->assign('categorieen', $templategroup->categorieen() );
		$this->smarty->assign('templates', $templategroup->all() );
		$this->smarty->display('instellingen/werkgever/documentenoverzicht.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// wijzigen documenten
	//-----------------------------------------------------------------------------------------------------------------
	public function documentenedit( $template_id)
	{
		$template = new Template( $template_id );
		
		//save document
		if( isset($_POST['set']) && $_POST['set'] == 'save_document' )
		{
			$template->setBodyAndTitel();
		}
		
		$this->smarty->assign('settings', $template->settings() );
		$this->smarty->assign('titel', $template->titel() );
		$this->smarty->assign('body', $template->body() );
		
		$this->smarty->display('instellingen/werkgever/documentenedit.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// pdf preview documenten
	//-----------------------------------------------------------------------------------------------------------------
	public function documentenpreview( $template_id )
	{
		$template = new Template( $template_id );
		
		//document aanmaken
		$document = DocumentFactory::createFromTemplateObject( $template );
		$document->dummy()->build()->preview();
		//$document->setInlenerID(3021)->build()->preview();
	}

	//-----------------------------------------------------------------------------------------------------------------
	// algemene voorwaarden
	//-----------------------------------------------------------------------------------------------------------------
	public function av()
	{
		//submit
		if( isset($_POST['set']) && $_POST['set'] == 'save' )
		{
			$this->werkgever->setAV();
			if( $this->werkgever->errors() === false )
				$this->smarty->assign('msg', msg('success', 'Wijzigingen opgeslagen'));
			else
				$this->smarty->assign('msg', msg('warning', $this->werkgever->errors() ));
		}
		
		//publiceren
		if( isset($_POST['set']) && $_POST['set'] == 'activate' )
		{
			$this->werkgever->publicateAV();
		}
		
		$this->smarty->assign('av', $this->werkgever->AVhtml() );
		$this->smarty->display('instellingen/werkgever/av.tpl');
	}
	
}
