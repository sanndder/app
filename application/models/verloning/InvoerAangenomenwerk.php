<?php

namespace models\verloning;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Object voor afhandelen uurinvoer
 *
 *
 */

class InvoerAangenomenwerk extends Invoer
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
	/*
	 * project opslaan
	 *
	 * @return bool
	 */
	public function setProject( $data )
	{

		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * project opslaan
	 *
	 * @return bool
	 */
	public function setProjectData( $invoer_id, $project_id, $omschrijving, $bedrag )
	{
		//oude opschonen
		/*
		if( is_numeric($invoer_id) )
			$this->delete_row( 'invoer_aangenomenwerk_regels', array('invoer_id' => intval($invoer_id)) );
		*/
		
		$set['project_id'] = $project_id;
		$set['omschrijving'] = $omschrijving;
		$set['bedrag'] = prepareAmountForDatabase($bedrag);
		
		$set['tijdvak'] = $this->_tijdvak;
		$set['jaar'] = $this->_jaar;
		$set['periode'] = $this->_periode;
		$set['uitzender_id'] = $this->_uitzender_id;
		$set['inlener_id'] = $this->_inlener_id;
		$set['user_id'] = $this->user->user_id;
		
		if( is_numeric($invoer_id) )
		{
			$this->db_user->where( 'invoer_id', $invoer_id );
			$this->db_user->update( 'invoer_aangenomenwerk_regels', $set );
			return $invoer_id;
		}
		else
		{
			$this->db_user->insert( 'invoer_aangenomenwerk_regels', $set );
			return $this->db_user->insert_id();
		}
		
	}



	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * project opslaan
	 *
	 * @return bool|array
	 */
	public function getAangenomenwerkRijen()
	{
		$sql = "SELECT invoer_aangenomenwerk_regels.*
				FROM invoer_aangenomenwerk_regels
				WHERE invoer_aangenomenwerk_regels.factuur_id IS NULL AND invoer_aangenomenwerk_regels.uitzender_id = ? AND invoer_aangenomenwerk_regels.inlener_id = ?
				  AND invoer_aangenomenwerk_regels.tijdvak = ? AND invoer_aangenomenwerk_regels.jaar = ? AND invoer_aangenomenwerk_regels.periode = ? AND deleted = 0";
		
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );

		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$data[$row['invoer_id']] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * project data ophalen
	 *
	 * @return bool|array
	 */
	public function getDataForProjecten( $projecten )
	{
		
		//nieuwe array bouwen
		foreach( $projecten as $project_id => $project )
		{
			$data[$project_id]['project_id'] = $project_id;
			$data[$project_id]['project'] = $project['omschrijving'];
			$data[$project_id]['rijen'] = [];
		}
		
		$rijen = $this->getAangenomenwerkRijen();
		
		if( $rijen === NULL )
			return $data;
		
		foreach( $rijen as $rij )
			$data[$rij['project_id']]['rijen'][$rij['invoer_id']] = $rij;
		
		return $data;
	}

	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log actie
	 *
	 */
	private function _logAangenomenwerActie( $action, $row )
	{
		
		$insert['user_id'] = $this->user->user_id;
		$insert['json'] = json_encode( $row );
		$insert['action'] = $action;
		$insert['invoer_id'] = $row['invoer_id'];
		
		@$this->db_user->insert( 'invoer_aangenomenwerk_log', $insert );
	}
	
	
}
?>