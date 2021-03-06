<?php

use models\documenten\Document;
use models\documenten\DocumentAlgemeneVoorwaarden;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Geeft pdf van de documenten weer
 */
class Pdf extends MY_Controller
{
	
	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	//  haal pdf algemene voorwaarden op
	//-----------------------------------------------------------------------------------------------------------------
	public function av( $method = 'inline')
	{
		$this->werkgever->AVpdf( $method );
	}
	


	//-----------------------------------------------------------------------------------------------------------------
	//  haal de pdf op
	//-----------------------------------------------------------------------------------------------------------------
	public function view( $document_id )
	{
		$document = new Document( $document_id );
		
		//check rights
		if( !$document->userHasAccess() )
			die('Geen toegang');
	
		//pdf object ophalen
		$pdf = $document->pdf();
		
		//pdf opbject bekijken
		$pdf->inline();
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	//  forceer download
	//-----------------------------------------------------------------------------------------------------------------
	public function download( $document_id )
	{
		$document = new Document( $document_id );
		
		//check rights
		if( !$document->userHasAccess() )
			die('Geen toegang');
		
		//pdf object ophalen
		$pdf = $document->pdf();
		
		//pdf opbject bekijken
		$pdf->download();
		
	}
}
