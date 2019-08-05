<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Werkgever extends MY_Controller
{


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//--------------------------------------------------------------------------
	// index doorzetten
	//--------------------------------------------------------------------------
	public function index()
	{
		redirect($this->config->item('base_url') . 'instellingen/werkgever/bedrijfsgegevens', 'location');
	}

	//--------------------------------------------------------------------------
	// aanpassen bedrijfsgegevens
	//--------------------------------------------------------------------------
	public function bedrijfsgegevens()
	{
		//$werkgever = new models\Werkgever();
		$formbuidler = new models\forms\Formbuilder();

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

		//TEMP
		if(isset($_GET['img']))
		{
			$this->werkgever->handtekening();
		}


		$formdata = $formbuidler->table( 'werkgever_bedrijfsgegevens' )->data( $bedrijfsgevens )->errors( $errors )->build();
		//show($formdata);
		$this->smarty->assign('formdata', $formdata);

		$this->smarty->display('instellingen/werkgever/bedrijfsgegevens.tpl');
	}


	//--------------------------------------------------------------------------
	// bankrekeningen instellen
	//--------------------------------------------------------------------------
	public function bankrekeningen()
	{
		//new formbuilder
		$formbuidler = new models\forms\Formbuilder();

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


	//--------------------------------------------------------------------------
	// algemene voorwaarden
	//--------------------------------------------------------------------------
	public function av()
	{

		$this->smarty->display('instellingen/werkgever/av.tpl');
	}


	//--------------------------------------------------------------------------
	// AJAX upload
	//--------------------------------------------------------------------------
	public function upload( $type = '' )
	{
		if( $type == 'logo' )
		{
			$dir = 'werkgever/logo';
		}

		$this->load->model('upload_model', 'uploadfiles');
		$this->uploadfiles->setUploadDir( $dir );
		$this->uploadfiles->setDatabaseTable( 'uitzenders_handtekening' );
		$this->uploadfiles->setPrefix( $type . '_' );
		$this->uploadfiles->uploadfilesToDatabase();

		if( $this->uploadfiles->errors() === false)
		{
			$preview[] = 'http://via.placeholder.com/150';
			$config[] = array('url' => '/test', 'caption' => 'test.jpg', 'key' => 101, 'size' => 100);
			$result = [ 'initialPreview' => $preview,'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
		}
		else
			$result['error'] = $this->uploadfiles->errors();

		header('Content-Type: application/json'); // set json response headers
		echo json_encode($result);
		die();

	}

}
