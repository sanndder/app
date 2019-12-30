<?php

use models\cao\CAO;
use models\cao\CAOGroup;
use models\documenten\IDbewijs;
use models\email\Email;
use models\file\File;
use models\file\Img;
use models\file\Pdf;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingCollection;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Test extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//$this->load->model('test2_model', 'test2');
		//$this->load->model('emailcentrum_model', 'emailcentrum');

		//show( $this->user->user_type );


	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// test ondertekenen
	//-----------------------------------------------------------------------------------------------------------------
	public function pdf()
	{
		
		$this->smarty->display('test/pdf.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// connect to creditsafe
	//-----------------------------------------------------------------------------------------------------------------
	public function credit()
	{
		/*
		//init curl
		$curl = curl_init( 'https://connect.creditsafe.com/v1/companies/' );
		
		//juiste header
		$headers = array(
			'Authorization:' . $this->_api_key
		);
		
		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//result ophalen
		$this->_json = curl_exec($curl);
		
		//afsluiten voor recources
		curl_close($curl);
*/
		
		
		$curl = curl_init( 'https://connect.creditsafe.com/v1/authenticate' );
		
		//juiste header
		$headers = array(
			'Content-Type: application/json'
		);
		
		$data = '{ "username": "sander@aberinghr.nl",  "password": “7/w0DE6xhB]]g$y$|cuz”}';
		
		$array['username'] = 'sander@aberinghr.nl';
		$array['password'] = '7/w0DE6xhB]]g$y$|cuz';
		
		$data = json_encode($array, JSON_UNESCAPED_SLASHES);

		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		$json = curl_exec($curl);
		$json = json_decode($json);
		curl_close($curl);
		$token = $json->token;
		
		// ---------------------------------------------- company zoeken -------------------------------------------------------------------------
		$curl = curl_init( 'https://connect.creditsafe.com/v1/companies?regNo=66122422&countries=NL&page=1&pageSize=10' );
		
		//juiste header
		$headers = array(
			'Content-Type: application/json',
			'Authorization: ' .$token
		);
		
		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$json = curl_exec($curl);
		$json = json_decode($json);
		curl_close($curl);
		
		show($json);
		
		// ---------------------------------------------- company report -------------------------------------------------------------------------
		$curl = curl_init( 'https://connect.creditsafe.com/v1/companies/' . $json->companies[0]->id );
		
		//juiste header
		$headers = array(
			'Content-Type: application/json',
			'Authorization: ' .$token
		);
		
		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$json = curl_exec($curl);
		$json = json_decode($json);
		curl_close($curl);
		
		show($json);
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// test ajax method
	//-----------------------------------------------------------------------------------------------------------------
	public function ajax()
	{
		$inleners = array('1001Tafelkleden.com','4you Personeelsdiensten','Aardappelgroothandel Jansen-Dongen B.V.');
		echo json_encode($inleners);
	}

	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function vue()
	{
		$this->smarty->assign('t', time());
		$this->smarty->display('test/vue.tpl');
	}


	//-----------------------------------------------------------------------------------------------------------------
	// Run a series of validation tests
	//-----------------------------------------------------------------------------------------------------------------
	public function validation()
	{

		$validator = new models\forms\Validator();
		$validator->table( 'test' )->input( $data )->run();

		$this->smarty->display('test/validation.tpl');
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function image()
	{

		//load file
		$img = new Img('werknemer/idbewijs/id_0547813001573059839_PCCSe8am.jpg');
		
		$img->trimWhiteSpace();
		
		//nieuwe ID starten
		//$idbewijs = new IDbewijs();
		//$idbewijs->werknemer( 14003 )->imgObjectToDatabase( 'front', $img );
		
		
		die();
		header('Content-type: image/jpeg');
		echo $idbewijs->image();
		
		die();
		
		//img class aden om plaatje te resizen
		//$image = new Img( $file_array );
		//$image->setMaxWidthHeight( 700, 400 )->setQuality(80)->resize();
	
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function cao()
	{
		
		
		$this->smarty->display('test.tpl');
	}
	

	//-----------------------------------------------------------------------------------------------------------------
	// test method
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$email = new Email();
		
		//$email->test();
		$email->debug();
		$email->setSubject('Testmail');
		$email->setTitel('Welkom bij Abering Uitzend B.V.');
		$email->setBody('In deze email vind u uw aanmeldlink voor onze online applicatie <b>Devis Online</b>. Nadat u uw gegevens heeft ingevuld zullen wij binnen één werkdag uw gegevens controleren
						en uw account activeren. Daarna kunt u volledig gebruik maken van alle mogelijkheden van <b>Devis Online</b>.
						<br /><br /> <a href="https://www.devisonline.nl/aanmelden/uitzender?wid=3">https://www.devisonline.nl/aanmelden/uitzender?wid=3</a><br /><br />Wij hopen op een fijne samenwerking!<br /><br />Abering Uitzend B.V.');
		$email->useHtmlTemplate( 'default' );
		//$email->test();
		
		
		//docent
		/*$cao = new CAO();
		$cao->setID( 228 );
		show( $cao->loontabellen() );
		$cao->setSalaryTable( 2 )->setLeeftijd( 23 );
		show($cao->jobs());*/
		
		//afbouw jeugd
		/*
		$cao = new CAO();
		$cao->setID( 75 )->setLeeftijd( 20 );
		show( $cao->loontabellen() );
		$cao->setloontabel( 8 );
		show( $cao->jobs());
		$cao->setJob( 12794 );
		show( $cao->schalen() );
		$cao->setSchaal( '1b' );
		show( $cao->periodieken() );
		$cao->setPeriodiek( '1.00' );
		
		show ($cao->uurloon() );*/
		/*
		$caogroup = new CAOGroup();
		
		if( isset($_GET['cao_id']) )
		{
			$cao = new CAO( $_GET['cao_id'] );
			$cao->setLeeftijd( 25 );
			
			$this->smarty->assign('loontabellen', $cao->loontabellen() );
			
			
			if( isset($_GET['tabel_id']) )
			{
				$cao->setLoontabel( $_GET['tabel_id'] );
				$this->smarty->assign('jobs', $cao->jobs() );
			}
			
			if( isset($_GET['functie_id']) )
			{
				$cao->setJob( $_GET['functie_id'] );
				$this->smarty->assign('schalen',  $cao->schalen() );
			}
			
			if( isset($_GET['schaal_id']) )
			{
				$cao->setSchaal( $_GET['schaal_id'] );
				
				//geen of 1 optie? dan gelijk door
				if( $cao->periodieken() === NULL || count($cao->periodieken() ) == 1 )
					$this->smarty->assign('uurloon',  $cao->uurloon() );
				else
					$this->smarty->assign('periodieken',  $cao->periodieken() );
			}
			
			if( isset($_GET['periodiek_id']) )
			{
				$cao->setPeriodiek( $_GET['periodiek_id'] );
				$this->smarty->assign('uurloon',  $cao->uurloon() );
			}
			
			if( $cao->errors() !== false )
				$this->smarty->assign( 'msg', msg( 'warning', $cao->errors() ) );
		}
		
		
		
		$this->smarty->assign('caos', $caogroup->all() );
	
		
		$this->smarty->display('test.tpl');
		
		die();
		
		show( $cao->salaryTable() );
		
		//show( $cao->periodieken() );
		
		
		//$pdf = new models\pdf\PdfFactuur();
		//$pdf->setHeader()->setFooter()->view();

		/*
		$row['file_name'] = 'image.jpg';
		$row['file_dir'] = 'test';
		$row['logo_id'] = 133;
		$row['id_field'] = 'logo_id';

		//$row = 'test/image.jpg';

		$img = new models\file\Img($row);


		//$img->download();

		//$file->info();

		/*
		$email = new models\Email\Email();


		//$email->test();
		$email->debug();
		$email->setSubject('Testmail');
		$email->setBody('Test met <b>HTML</b>');
		$email->useHtmlTemplate( 'default' );
		//$email->test();
		*/

		//$this->smarty->display('index.tpl');
	}

}
