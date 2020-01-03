<?php

use models\email\Email;
use models\forms\Formbuilder;
use models\forms\Valid;
use models\uitzenders\Uitzender;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Ajax extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//Deze pagina mag alleen bezocht worden door werkgever
		if( $this->user->user_type != 'werkgever' && $this->user->user_type != 'external' )forbidden();

	}
	
	//-----------------------------------------------------------------------------------------------------------------
	// Email aanmeldlink nieuwe uitzender
	//-----------------------------------------------------------------------------------------------------------------
	public function emailaanmeldlink()
	{
		header('Content-Type: application/json');
		
		$emailadres = $_POST['email'];
		
		//stop bij ongeldig emailadres
		if( !Valid::email($emailadres) )
		{
			$result = [ 'status' => 'error', 'error' => 'Ongeldig emailadres' ];
			echo json_encode($result);
			die();
		}
		
		$wid = $this->werkgever->wid();
		$hash = $this->werkgever->hash();
		
		$email = new Email();
		
		$to['email'] = $emailadres;
		$to['name'] = $emailadres;
		
		$email->to( $to );
		$email->setSubject('Aanmeldlink Abering Uitzend B.V.');
		$email->setTitel('Welkom bij Abering Uitzend B.V.');
		$email->setBody('In deze email vind u uw aanmeldlink voor onze online applicatie <b>Devis Online</b>. Nadat u uw gegevens heeft ingevuld zullen wij binnen één werkdag uw gegevens controleren
						en uw account activeren. Daarna kunt u volledig gebruik maken van alle mogelijkheden van <b>Devis Online</b>.
						<br /><br /> <a href="https://www.devisonline.nl/aanmelden/uitzender?wid='.$wid.'&wg_hash='.$hash.'">https://www.devisonline.nl/aanmelden/uitzender?wid='.$wid.'&wg_hash='.$hash.'</a><br /><br />Wij hopen op een fijne samenwerking!<br /><br />Abering Uitzend B.V.');
		$email->useHtmlTemplate( 'default' );
		$email->delay( 0 );
		$email->send();
		
		if( $email->errors() === false )
			$result = [ 'status'=> 'success' ];
		else
			$result = [ 'status'=> 'error', 'error' => 'Email kon niet worden verzonden' ];
		
		echo json_encode($result);
	}

	//-----------------------------------------------------------------------------------------------------------------
	// get contactpersoon JSON
	//-----------------------------------------------------------------------------------------------------------------
	public function getcontactpersoon( $uitzender_id = NULL, $contact_id = 0 )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );

		//allemaal ophalen
		$contactpersoon = $uitzender->contactpersoon($contact_id);

		//load the formbuilder
		$formbuidler = new models\forms\Formbuilder();

		//contactpersoon is bekend
		if(isset($contactpersoon))
		{
			$formdata = $formbuidler->table( 'uitzenders_contactpersonen' )->data( $contactpersoon )->build();
			echo json_encode($formdata);
		}

		//nieuwe toevoegen
		if( $contact_id == 0 )
		{
			$formdata = $formbuidler->table( 'uitzenders_contactpersonen' )->build();
			echo json_encode($formdata);
		}
	}


	//-----------------------------------------------------------------------------------------------------------------
	// set contactpersoon
	//-----------------------------------------------------------------------------------------------------------------
	public function setcontactpersoon( $uitzender_id = NULL, $contact_id = 0 )
	{
		//init uitzender object
		$uitzender = new Uitzender( $uitzender_id );

		//load the formbuilder
		$formbuidler = new Formbuilder();

		$uitzender->setContactpersoon( $contact_id );
		$errors = $uitzender->errors();

		//init response
		$response = array( 'status' => 'error' );

		//msg
		if( $errors === false )
			$response['status'] = 'success';
		else
			$response['error'] = $errors;

		echo json_encode( $response );
	}
}
