<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test class
 */

class Image extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//-----------------------------------------------------------------------------------------------------------------
	// url naar logo uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function logouitzender( $uitzender_id = '' )
	{
		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );
		$file = $uitzender->logo();

		header('Content-type: image/jpeg');
		readfile($file);

		die();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// url naar logo werkgever
	//-----------------------------------------------------------------------------------------------------------------
	public function logowerkgever( $entiteit_id = '' )
	{
		$this->werkgever->setEntiteitID( $entiteit_id );
		$file = $this->werkgever->logo();
		
		header('Content-type: image/jpeg');
		readfile($file);
		
		die();
	}

	//-----------------------------------------------------------------------------------------------------------------
	// url naar handtekening uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function handtekeninguitzender( $uitzender_id = '' )
	{
		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();

		//init uitzender object
		$uitzender = new \models\Uitzenders\Uitzender( $uitzender_id );
		$file = $uitzender->handtekening();

		header('Content-type: image/jpeg');
		echo($file);

		die();
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// url naar handtekening uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function handtekeningwerkgever( $entiteit_id = '' )
	{
		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' )forbidden();
		
		$this->werkgever->setEntiteitID( $entiteit_id );
		$file = $this->werkgever->handtekening();
		
		header('Content-type: image/jpeg');
		echo($file);
		
		die();
	}

}
