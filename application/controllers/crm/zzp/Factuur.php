<?php

use models\documenten\DocumentFactory;
use models\documenten\IDbewijs;
use models\documenten\Template;
use models\facturatie\FacturenGroup;
use models\forms\Formbuilder;
use models\inleners\InlenerGroup;
use models\zzp\ZzpGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\Carbagecollector;
use models\utils\Codering;
use models\utils\VisitsLogger;
use models\zzp\Plaatsing;
use models\zzp\PlaatsingGroup;
use models\zzp\Zzp;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Instellingen controller
 */
class Factuur extends MY_Controller
{

	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();

		//Deze pagina mag alleen bezocht worden door werkgever en zzper
		if( $this->user->user_type != 'werkgever' &&  $this->user->user_type != 'zzp' )forbidden();
	}

	//-----------------------------------------------------------------------------------------------------------------
	// Overzicht pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function view( $zzp_id = NULL, $factuur_id = NULL )
	{
		$zzp = new Zzp( $zzp_id );
		
		$factuur = $zzp->viewFactuur( $factuur_id );
	}
	
	
}
