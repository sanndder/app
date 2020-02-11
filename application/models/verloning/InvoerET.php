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

class InvoerET extends Invoer
{
	
	private $_uren = NULL;
	private $_insert_id = NULL;
	
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
	 * bedrag mx uit te ruilen
	 *
	 */
	public function maxUitruil()
	{
		return $this->_sumUren() * 12.50 * 0.3;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Werknemer uren instellen om vergoedingen uit te kunnen rekenen
	 *
	 */
	public function setWerknemerUren( $uren ) :InvoerET
	{
		$this->_uren = $uren;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * enkele row ophalen
	 *
	 */
	public function getEtRow()
	{
		$query = $this->db_user->query( "SELECT * FROM invoer_et WHERE tijdvak = ? AND jaar = ? AND periode = ? AND werknemer_id = ? AND inlener_id = ? AND uitzender_id = ?",
				array( $this->_tijdvak, $this->_jaar, $this->_periode, $this->_werknemer_id, $this->_inlener_id, $this->_uitzender_id ));
		
		return DBhelper::toRow($query, 'NULL');
	}



	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bedrag opslaan
	 *
	 */
	public function setBedrag( $bedrag, $field )
	{
		$row = $this->getEtRow();
		
		//update of insert
		if( $row !== NULL )
		{
			$update[$field] = prepareAmountForDatabase($bedrag);
			$this->db_user->where( 'invoer_id', $row['invoer_id'] );
			$this->db_user->update( 'invoer_et', $update );
			
			$this->_logETActie( 'update', $row );
			
			return $update[$field];
		}
		//nieuwe invor aanmaken
		else
		{
			$insert[$field] = prepareAmountForDatabase($bedrag);
			$insert['tijdvak'] = $this->_tijdvak;
			$insert['jaar'] = $this->_jaar;
			$insert['periode'] = $this->_periode;
			$insert['uitzender_id'] = $this->_uitzender_id;
			$insert['inlener_id'] = $this->_inlener_id;
			$insert['werknemer_id'] = $this->_werknemer_id;
			
			$this->db_user->insert( 'invoer_et', $insert );
			
			return $insert[$field];
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get nieuwe vergoeding ID
	 *
	 */
	public function getVergoedingInsertId()
	{
		return $this->_insert_id;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vaste vergoedingen uitrekenen
	 *
	 */
	private function _calcVasteVergoeding( $invoer_id, $vergoeding )
	{
		//zijn er uren
		if( $this->_uren === NULL )
			return 0;
		
		//uren optellen
		$totaal_uren = $this->_sumUren();
		
		// nul mag gelijk terug
		if( $totaal_uren == 0 )
			return 0;
		
		//update of insert
		if( $invoer_id !== NULL )
		{
			$update['bedrag'] = round( $vergoeding['bedrag_per_uur'] * $totaal_uren );
			$this->db_user->where( 'invoer_id', $invoer_id );
			$this->db_user->update( 'invoer_vergoedingen', $update );
			
			$this->_logETActie( 'update', $this->getVergoeding($invoer_id) );
			
			return $update['bedrag'];
		}
		//nieuwe invor aanmaken
		else
		{
			$insert['bedrag'] = round( $vergoeding['bedrag_per_uur'] * $totaal_uren );
			$insert['tijdvak'] = $this->_tijdvak;
			$insert['jaar'] = $this->_jaar;
			$insert['periode'] = $this->_periode;
			$insert['werknemer_vergoeding_id'] = $vergoeding['id'];
			$insert['doorbelasten'] = $vergoeding['doorbelasten'];
			$insert['uitzender_id'] = $this->_uitzender_id;
			$insert['inlener_id'] = $this->_inlener_id;
			$insert['werknemer_id'] = $this->_werknemer_id;
			$insert['zzp_id'] = $this->_zzp_id;
			
			$this->db_user->insert( 'invoer_vergoedingen', $insert );
			
			return $insert['bedrag'];
		}

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren optellen
	 * 1: uren
	 * 2: overuren
	 * 3: reisuren
	 *
	 */
	private function _sumUren()
	{
		$aantal = 0;
		
		if(  $this->_uren !== NULL && count($this->_uren) > 0 )
		{
			foreach( $this->_uren as $row )
			{
				if( $row['urentype_categorie_id'] == 1 )
					$aantal += $row['decimaal'];
			}
		}
		return $aantal;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log actie
	 *
	 */
	private function _logETActie( $action, $row )
	{
		
		$insert['user_id'] = $this->user->user_id;
		$insert['json'] = json_encode( $row );
		$insert['action'] = $action;
		$insert['invoer_id'] = $row['invoer_id'];
		
		@$this->db_user->insert( 'invoer_et_log', $insert );
	}
	
	
}
?>