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
	 * Werknemer uren instellen om vergoedingen uit te kunnen rekenen
	 *
	 */
	public function setWerknemerUren( $uren ) :InvoerVergoedingen
	{
		$this->_uren = $uren;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * enkele row ophalen
	 *
	 */
	public function getVergoeding( $invoer_id )
	{
		$query = $this->db_user->query( "SELECT * FROM invoer_vergoedingen WHERE invoer_id = ?", array($invoer_id ));
		return DBhelper::toRow($query,' NULL');
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * doorbelasten opslaan
	 *
	 */
	public function setDoorbelasten( $invoer_id, $doorbelasten )
	{
		if( $doorbelasten != 'inlener' && $doorbelasten != 'uitzender' )
			return false;
		
		$old_entry = $this->getVergoeding($invoer_id);
		
		$update['doorbelasten'] = $doorbelasten;
		$this->db_user->where( 'invoer_id', $invoer_id );
		$this->db_user->where( 'werknemer_id', $this->_werknemer_id );
		$this->db_user->where( 'tijdvak', $this->_tijdvak );
		$this->db_user->where( 'jaar', $this->_jaar );
		$this->db_user->where( 'periode', $this->_periode );
		
		$this->db_user->update( 'invoer_vergoedingen', $update );
		
		$this->_logVergoedingActie( 'update', $old_entry );
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bedrag opslaan
	 *
	 */
	public function setBedrag( $invoer_id, $werknemer_vergoeding_id, $bedrag )
	{
		$bedrag = prepareAmountForDatabase($bedrag);
		
		if( !is_numeric($bedrag) )
			return false;

		if( $invoer_id != '' )
		{
			$old_entry = $this->getVergoeding( $invoer_id );
			
			$update['bedrag'] = $bedrag;
			$this->db_user->where( 'invoer_id', $invoer_id );
			$this->db_user->where( 'werknemer_id', $this->_werknemer_id );
			$this->db_user->where( 'tijdvak', $this->_tijdvak );
			$this->db_user->where( 'jaar', $this->_jaar );
			$this->db_user->where( 'periode', $this->_periode );
			
			$this->db_user->update( 'invoer_vergoedingen', $update );
			
			$this->_logVergoedingActie( 'update', $old_entry );
		}
		else
		{
			$vergoedingengroup = new VergoedingGroup();
			$beschikbare_vergoedingen = $vergoedingengroup->inlener( $this->_inlener_id )->werknemer( $this->_werknemer_id )->vergoedingenWerknemer();
			
			$insert['bedrag'] = $bedrag;
			$insert['tijdvak'] = $this->_tijdvak;
			$insert['jaar'] = $this->_jaar;
			$insert['periode'] = $this->_periode;
			$insert['werknemer_vergoeding_id'] = $werknemer_vergoeding_id;
			$insert['doorbelasten'] = $beschikbare_vergoedingen[$werknemer_vergoeding_id]['doorbelasten'];
			$insert['uitzender_id'] = $this->_uitzender_id;
			$insert['inlener_id'] = $this->_inlener_id;
			$insert['werknemer_id'] = $this->_werknemer_id;
			$insert['zzp_id'] = $this->_zzp_id;
			
			$this->db_user->insert( 'invoer_vergoedingen', $insert );
			
			$this->_insert_id = $this->db_user->insert_id();
		}
		
		return true;
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
	 * Vergoedingen voor werknemer ophalen
	 *
	 */
	public function getWerknemerVergoedingen()
	{
		//welke vergoedingen zijn er voor werknemer
		$vergoedingengroup = new VergoedingGroup();
		$beschikbare_vergoedingen = $vergoedingengroup->inlener( $this->_inlener_id )->werknemer( $this->_werknemer_id )->vergoedingenWerknemer();
		
		//nu bedragen er bij halen
		$sql = "SELECT * FROM invoer_vergoedingen
				WHERE uitzender_id = ? AND inlener_id = ? AND werknemer_id = ? AND tijdvak = ? AND jaar = ? AND periode = ?";
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_werknemer_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );
		
		$invoer = array();
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
				$invoer[$row['werknemer_vergoeding_id']] = $row;
		}
		
		//controleren of alles nog klopt
		foreach( $beschikbare_vergoedingen as $id => &$vergoeding )
		{
			//copy
			$vergoeding['doorbelasten_setting'] = $vergoeding['doorbelasten'];
			
			//invoer ID instellen als deze bestaat
			$invoer_id = $invoer[$id]['invoer_id'] ?? NULL;
			$vergoeding['invoer_id'] = $invoer_id;
			
			//variabele vergoeding alleen toevoegen aan array
			if( $vergoeding['vergoeding_type'] == 'variabel' )
			{
				//record bestaat
				if( $invoer_id !== NULL )
				{
					$vergoeding['bedrag'] = $invoer[$id]['bedrag'];
					$vergoeding['doorbelasten'] = $invoer[$id]['doorbelasten'];
				}
				//niet gevonden, bedrag op 0
				else
				{
					$vergoeding['bedrag'] = 0;
				}
			}
			
			//vaste vergoedingen uitrekenen
			if( $vergoeding['vergoeding_type'] == 'vast' )
			{
				$vergoeding['bedrag'] = $this->_calcVasteVergoeding( $invoer_id, $vergoeding );
				
				//record bestaat
				if( isset($invoer[$id]) )
					$vergoeding['doorbelasten'] = $invoer[$id]['doorbelasten'];
			}
			
		}
		
		return $beschikbare_vergoedingen;
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
			
			$this->_logVergoedingActie( 'update', $this->getVergoeding($invoer_id) );
			
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
		
		foreach( $this->_uren as $row )
		{
			if( $row['urentype_categorie_id'] == 1)
				$aantal += $row['decimaal'];
		}
		
		return $aantal;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log actie
	 *
	 */
	private function _logVergoedingActie( $action, $row )
	{
		
		$insert['user_id'] = $this->user->user_id;
		$insert['json'] = json_encode( $row );
		$insert['action'] = $action;
		$insert['invoer_id'] = $row['invoer_id'];
		
		@$this->db_user->insert( 'invoer_vergoedingen_log', $insert );
	}
	
	
}
?>