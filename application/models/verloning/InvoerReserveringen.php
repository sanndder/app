<?php

namespace models\verloning;

use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Object voor afhandelen uurinvoer
 *
 *
 */

class InvoerReserveringen extends Invoer
{
	
	protected $_type = NULL;
	
	
	
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
	 * werknemer ID
	 *
	 * @return object
	 */
	public function setType( $type ) :object
	{
		$this->_type = $type;
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * reserveringen opslaan
	 *
	 */
	public function setBedrag( $bedrag ) :?bool
	{
		if( !is_numeric($bedrag) || $bedrag < 0 || $bedrag == '' )
		{
			$this->_error[] = 'Ongeldig bedrag';
			return false;
		}
		
		//check
		$stand = $this->getStandReserveringen();
		
		if( !isset($stand[$this->_type]) )
			return false;
		
		if( $stand[$this->_type] < $bedrag)
		{
			$this->_error[] = 'Het opgevraagde bedrag is te hoog';
			return false;
		}
		
		$invoer =  $this->getOpgevraagdeReserveringen();
		
		//nieuwe insert
		if( $invoer === NULL )
		{
			$insert[$this->_type] = $bedrag;
			$insert['werknemer_id'] = $this->_werknemer_id;
			$insert['tijdvak'] = $this->_tijdvak;
			$insert['jaar'] = $this->_jaar;
			$insert['periode'] = $this->_periode;
			$insert['user_id'] = $this->user->user_id;
			
			$this->db_user->insert( 'invoer_reserveringen', $insert );
		}
		else
		{
			$update[$this->_type] = $bedrag;
			$this->db_user->where( 'invoer_id', $invoer['invoer_id'] );
			$this->db_user->update( 'invoer_reserveringen', $update );
			
			$this->_logReserveringenActie( 'update', $invoer );
		}
		
		return true;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * opgevraagde reserveringen ophalen
	 *
	 */
	public function getOpgevraagdeReserveringen() :?array
	{
		$sql = "SELECT invoer_id, vakantieuren_F12, vakantieuren, vakantiegeld, feestdagen, kort_verzuim, atv_uren FROM invoer_reserveringen
				WHERE invoer_id IS NOT NULL AND deleted = 0 AND werknemer_id = $this->_werknemer_id";
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * stand reserveringen ophalen
	 *
	 */
	public function getStandReserveringen() :?array
	{
		$reserveringen = new Reserveringen();
		return $reserveringen->werknemer( $this->_werknemer_id )->stand();
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log actie
	 *
	 */
	private function _logReserveringenActie( $action, $row )
	{
		$insert['user_id'] = $this->user->user_id;
		$insert['json'] = json_encode( $row );
		$insert['action'] = $action;
		$insert['invoer_id'] = $row['invoer_id'];
		
		@$this->db_user->insert( 'invoer_reserveringen_log', $insert );
	}
	
	
}
?>