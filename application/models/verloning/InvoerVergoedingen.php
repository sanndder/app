<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Object voor afhandelen uurinvoer
 *
 *
 */

class InvoerVergoedingen extends Invoer
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct( ?Invoer $invoer = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $invoer !== NULL )
			$this->copySettings( $invoer );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * gegevens overnemen van invoer object
	 *
	 * @return void
	 */
	public function copySettings( Invoer $invoer )
	{
		$this->setTijdvak( $invoer->tijdvakinfo() );
		
		if( $invoer->inlener() !== NULL )
			$this->setInlener( $invoer->inlener()  );
		
		if( $invoer->uitzender() !== NULL )
			$this->setUitzender( $invoer->uitzender()  );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vergoedingen voor werknemer ophalen
	 *
	 */
	public function getWerknemerVergoedingen()
	{
	
	
	}
	
}
?>