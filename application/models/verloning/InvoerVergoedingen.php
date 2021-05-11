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
			$this->setUitzender( $invoer->uitzender() );
		
		if( $invoer->werknemers() !== NULL )
			$this->setWerknemers( $invoer->werknemers() );
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
	 * Werknemer uren instellen om vergoedingen uit te kunnen rekenen
	 *
	 */
	public function setZzpUren( $uren ) :InvoerVergoedingen
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
		return DBhelper::toRow($query, 'NULL');
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
	 * doorbelasten opslaan
	 *
	 */
	public function setProject( $invoer_id, $project_id )
	{
		if( !is_numeric($project_id))
			return false;
		
		$old_entry = $this->getVergoeding($invoer_id);
		
		$update['project_id'] = $project_id;
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
			$this->db_user->where( 'factuur_id', NULL );
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
	 * samenvatting ophalen voor overzicht
	 *
	 */
	public function getWerknemerVergoedingenSamenvatting() :?array
	{
		return $this->getWerknemersVergoedingenRijen();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vergoedingen voor werknemer ophalen voor facturatie
	 *
	 */
	public function getWerknemersVergoedingenRijen()
	{
		//nu bedragen er bij halen
		$sql = "SELECT invoer_vergoedingen.invoer_id, invoer_vergoedingen.werknemer_id, invoer_vergoedingen.zzp_id, invoer_vergoedingen.bedrag, invoer_vergoedingen.doorbelasten, invoer_vergoedingen.project_id,
       					inleners_vergoedingen.vergoeding_type, inleners_vergoedingen.uitkeren_werknemer, inleners_vergoedingen.doorbelasten AS doorbelasten_default,
       					vergoedingen.naam, vergoedingen.belast, invoer_vergoedingen.werknemer_id
				FROM invoer_vergoedingen
				LEFT JOIN werknemers_vergoedingen ON werknemers_vergoedingen.id = invoer_vergoedingen.werknemer_vergoeding_id
				LEFT JOIN inleners_vergoedingen ON inleners_vergoedingen.inlener_vergoeding_id = werknemers_vergoedingen.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON vergoedingen.vergoeding_id = inleners_vergoedingen.vergoeding_id
				WHERE inleners_vergoedingen.deleted = 0 AND invoer_vergoedingen.uitzender_id = ? AND invoer_vergoedingen.inlener_id = ?
				  AND invoer_vergoedingen.werknemer_id IN (".array_keys_to_string($this->_werknemer_ids).") AND invoer_vergoedingen.tijdvak = ? AND invoer_vergoedingen.jaar = ? AND invoer_vergoedingen.periode = ?";
		
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['belast'] == 0 )
				$row['factor'] = 1;
			
			$data[$row['werknemer_id']][$row['invoer_id']] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vergoedingen voor werknemer ophalen voor facturatie
	 *
	 */
	public function getWerknemerVergoedingenRijen()
	{
		//nu bedragen er bij halen
		$sql = "SELECT invoer_vergoedingen.invoer_id, invoer_vergoedingen.werknemer_id, invoer_vergoedingen.zzp_id, invoer_vergoedingen.bedrag, invoer_vergoedingen.doorbelasten, invoer_vergoedingen.project_id,
       					inleners_vergoedingen.vergoeding_type, inleners_vergoedingen.uitkeren_werknemer, inleners_vergoedingen.doorbelasten AS doorbelasten_default,
       					vergoedingen.naam, vergoedingen.belast,
       					inleners_factoren.factor_laag AS factor
				FROM invoer_vergoedingen
				LEFT JOIN werknemers_vergoedingen ON werknemers_vergoedingen.id = invoer_vergoedingen.werknemer_vergoeding_id
				LEFT JOIN inleners_vergoedingen ON inleners_vergoedingen.inlener_vergoeding_id = werknemers_vergoedingen.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON vergoedingen.vergoeding_id = inleners_vergoedingen.vergoeding_id
				LEFT JOIN inleners_factoren ON invoer_vergoedingen.inlener_id = inleners_factoren.inlener_id
				WHERE invoer_vergoedingen.factuur_id IS NULL AND inleners_vergoedingen.deleted = 0 AND inleners_factoren.deleted = 0 AND inleners_factoren.default_factor = 1 AND invoer_vergoedingen.uitzender_id = ? AND invoer_vergoedingen.inlener_id = ?
				  AND invoer_vergoedingen.werknemer_id = ? AND invoer_vergoedingen.tijdvak = ? AND invoer_vergoedingen.jaar = ? AND invoer_vergoedingen.periode = ?";
		
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_werknemer_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['belast'] == 0 )
				$row['factor'] = 1;
			
			$data[$row['invoer_id']] = $row;
		}

		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vergoedingen voor werknemer ophalen voor facturatie
	 *
	 */
	public function getZzpVergoedingenRijen()
	{
		//nu bedragen er bij halen
		$sql = "SELECT invoer_vergoedingen.invoer_id, invoer_vergoedingen.werknemer_id, invoer_vergoedingen.zzp_id, invoer_vergoedingen.bedrag, invoer_vergoedingen.doorbelasten, invoer_vergoedingen.project_id,
       					inleners_vergoedingen.vergoeding_type, inleners_vergoedingen.uitkeren_werknemer, inleners_vergoedingen.doorbelasten AS doorbelasten_default,
       					vergoedingen.naam, vergoedingen.belast
				FROM invoer_vergoedingen
				LEFT JOIN zzp_vergoedingen ON zzp_vergoedingen.id = invoer_vergoedingen.werknemer_vergoeding_id
				LEFT JOIN inleners_vergoedingen ON inleners_vergoedingen.inlener_vergoeding_id = zzp_vergoedingen.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON vergoedingen.vergoeding_id = inleners_vergoedingen.vergoeding_id
				WHERE invoer_vergoedingen.factuur_id IS NULL AND inleners_vergoedingen.deleted = 0 AND invoer_vergoedingen.uitzender_id = ? AND invoer_vergoedingen.inlener_id = ?
				  AND invoer_vergoedingen.zzp_id = ? AND invoer_vergoedingen.tijdvak = ? AND invoer_vergoedingen.jaar = ? AND invoer_vergoedingen.periode = ?";
		
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_werknemer_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$data[$row['invoer_id']] = $row;
		}
		
		return $data;
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
		$sql = "SELECT invoer_vergoedingen.*, inleners_factoren.factor_laag AS factor
				FROM invoer_vergoedingen
				LEFT JOIN inleners_factoren ON invoer_vergoedingen.inlener_id = inleners_factoren.inlener_id
				WHERE inleners_factoren.deleted = 0 AND inleners_factoren.default_factor = 1 AND invoer_vergoedingen.uitzender_id = ? AND invoer_vergoedingen.inlener_id = ?
				  AND invoer_vergoedingen.werknemer_id = ? AND invoer_vergoedingen.tijdvak = ? AND invoer_vergoedingen.jaar = ? AND invoer_vergoedingen.periode = ?";
		
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
			
			if(isset($invoer[$id]['project_id']))
				$vergoeding['project_id'] = $invoer[$id]['project_id'];
			
			//variabele vergoeding alleen toevoegen aan array
			if( $vergoeding['vergoeding_type'] == 'variabel' )
			{
				//record bestaat
				if( $invoer_id !== NULL )
				{
					$vergoeding['bedrag'] = $invoer[$id]['bedrag'];
					$vergoeding['doorbelasten'] = $invoer[$id]['doorbelasten'];
					$vergoeding['factor'] = $invoer[$id]['factor'];
				}
				//niet gevonden, bedrag op 0
				else
				{
					$vergoeding['bedrag'] = 0;
				}
			}
			
			//vaste vergoedingen uur uitrekenen
			if( $vergoeding['vergoeding_type'] == 'vast' )
			{
				$vergoeding['bedrag'] = $this->_calcVasteVergoeding( $invoer_id, $vergoeding );
				
				//record bestaat
				if( isset($invoer[$id]) )
				{
					$vergoeding['factor'] = $invoer[$id]['factor'];
					$vergoeding['doorbelasten'] = $invoer[$id]['doorbelasten'];
				}
			}
			//vaste vergoedingen dag uitrekenen
			if( $vergoeding['vergoeding_type'] == 'dag' )
			{
				$vergoeding['bedrag'] = $this->_calcDagVergoeding( $invoer_id, $vergoeding );

				//record bestaat
				if( isset($invoer[$id]) )
				{
					$vergoeding['factor'] = $invoer[$id]['factor'];
					$vergoeding['doorbelasten'] = $invoer[$id]['doorbelasten'];
				}
			}
			
		}
		
		return $beschikbare_vergoedingen;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vergoedingen voor werknemer ophalen
	 *
	 */
	public function getZzpVergoedingen()
	{
		//welke vergoedingen zijn er voor werknemer
		$vergoedingengroup = new VergoedingGroup();
		$beschikbare_vergoedingen = $vergoedingengroup->inlener( $this->_inlener_id )->zzp( $this->_zzp_id )->vergoedingenZzp();
		
		//nu bedragen er bij halen
		$sql = "SELECT invoer_vergoedingen.*, inleners_factoren.factor_laag AS factor
				FROM invoer_vergoedingen
				LEFT JOIN inleners_factoren ON invoer_vergoedingen.inlener_id = inleners_factoren.inlener_id
				WHERE inleners_factoren.deleted = 0 AND inleners_factoren.default_factor = 1 AND invoer_vergoedingen.uitzender_id = ? AND invoer_vergoedingen.inlener_id = ?
				  AND invoer_vergoedingen.zzp_id = ? AND invoer_vergoedingen.tijdvak = ? AND invoer_vergoedingen.jaar = ? AND invoer_vergoedingen.periode = ?";
		
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_zzp_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );
		
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
					$vergoeding['factor'] = $invoer[$id]['factor'];
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
				{
					$vergoeding['factor'] = $invoer[$id]['factor'];
					$vergoeding['doorbelasten'] = $invoer[$id]['doorbelasten'];
				}
			}
			
		}
		
		return $beschikbare_vergoedingen;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Vaste dag vergoedingen uitrekenen
	 *
	 */
	private function _calcDagVergoeding( $invoer_id, $vergoeding )
	{
		
		//zijn er uren
		if( $this->_uren === NULL )
			return 0;
		
		$totaal_dagen = $this->_sumDagen();
		
		// nul mag gelijk terug
		if( $totaal_dagen == 0 )
			return 0;
		
		//update of insert
		if( $invoer_id !== NULL )
		{
			$update['bedrag'] = round( ($vergoeding['bedrag_per_dag'] * $totaal_dagen), 2 );
			$this->db_user->where( 'invoer_id', $invoer_id );
			$this->db_user->update( 'invoer_vergoedingen', $update );
			
			$this->_logVergoedingActie( 'update', $this->getVergoeding($invoer_id) );
			
			return $update['bedrag'];
		}
		//nieuwe invor aanmaken
		else
		{
			$insert['bedrag'] = round( $vergoeding['bedrag_per_dag'] * $totaal_dagen );
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
	 * Vaste vergoedingen uitrekenen
	 *
	 */
	private function _calcVasteVergoeding( $invoer_id, $vergoeding )
	{
		//zijn er uren
		if( $this->_uren === NULL )
			return 0;
		
		//uren optellen
		$totaal_uren = $this->_sumUren( $vergoeding['telling_uren'], $vergoeding['telling_overuren'], $vergoeding['telling_reisuren']);
		
		// nul mag gelijk terug
		if( $totaal_uren == 0 )
			return 0;
		
		//update of insert
		if( $invoer_id !== NULL )
		{
			$update['bedrag'] = round( ($vergoeding['bedrag_per_uur'] * $totaal_uren), 2 );
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
	private function _sumUren( $uren = 0, $overuren = 0, $reisuren = 0 )
	{
		$aantal = 0;

		foreach( $this->_uren as $row )
		{
			if( $uren == 1 && $row['urentype_categorie_id'] == 1)
				$aantal += $row['decimaal'];
			
			if( $overuren == 1 && $row['urentype_categorie_id'] == 2)
				$aantal += $row['decimaal'];
			
			if( $reisuren == 1 && $row['urentype_categorie_id'] == 3)
				$aantal += $row['decimaal'];
		}
		
		return $aantal;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * dagen optellen
	 *
	 */
	private function _sumDagen()
	{
		$array = array();
		
		foreach( $this->_uren as $row )
			$array[$row['datum']] = 1;
		
		return count($array);
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