<?php

use models\cao\CAO;
use models\cao\CAOGroup;
use models\Documenten\IDbewijs;
use models\File\File;
use models\File\Img;
use models\File\Pdf;
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

		$img = new models\File\Img($row);


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
