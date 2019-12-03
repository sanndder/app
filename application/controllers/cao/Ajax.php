<?php

use models\cao\CAO;

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
		if( $this->user->user_type != 'werkgever' )forbidden();

	}

	//-----------------------------------------------------------------------------------------------------------------
	// get cao data
	//-----------------------------------------------------------------------------------------------------------------
	public function caodata()
	{
		//show($_POST);
		
		$cao = new CAO( $_POST['cao_id'] );
		$cao->setLeeftijd( $_POST['cao_id'] );
		
		//loontabellen altijd ophalen als de cao bekend is
		$return['loontabellen'] = $cao->loontabellen();
		
		//set loontabel
		if( isset($_POST['loontabel_id']) )
		{
			$cao->setLoontabel( $_POST['loontabel_id'] );
			$return['jobs'] = $cao->jobs();
		}
		
		//set job
		if( isset($_POST['job_id']) )
		{
			$cao->setJob( $_POST['job_id'] );
			$return['schalen'] = $cao->schalen() ;
		}
		
		//set schaal
		if( isset($_POST['schaal_id']) )
		{
			$cao->setSchaal( $_POST['schaal_id'] );
			//geen of 1 optie? dan gelijk door
			if( $cao->periodieken() === NULL || count($cao->periodieken() ) == 1 )
				$return['uurloon'] = $cao->uurloon();
			else
				$return['periodieken'] = $cao->periodieken();
		}
		
		if( isset($_POST['periodiek_id']) )
		{
			$cao->setPeriodiek( $_POST['periodiek_id'] );
			$return['uurloon'] =  $cao->uurloon();
		}
		
		//zijn er errors
		if( $cao->errors() !== false )
			$return['error'] = $cao->errors();
			
		echo  json_encode($return);
	}
	
}
