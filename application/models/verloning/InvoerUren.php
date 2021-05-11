<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\Plaatsing;
use models\werknemers\PlaatsingGroup;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Object voor afhandelen uurinvoer
 *
 *
 */

class InvoerUren extends Invoer
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
	 *
	 * gegevens overnemen van invoer object
	 *
	 * @return void
	 */
	public function copySettings( Invoer $invoer )
	{
		$this->setTijdvak( $invoer->tijdvakinfo() );
		
		if( $invoer->inlener() !== NULL )
			$this->setInlener( $invoer->inlener() );
		
		if( $invoer->uitzender() !== NULL )
			$this->setUitzender( $invoer->uitzender() );
		
		if( $invoer->werknemers() !== NULL )
			$this->setWerknemers( $invoer->werknemers() );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij opslaan
	 * TODO: betere controle, zit datum in periode? Max uren
	 */
	private function _validateInput( $row ): ?array
	{
		if( $row['project_tekst'] == '' )
			$row['project_tekst'] = NULL;
		if( $row['locatie_tekst'] == '' )
			$row['locatie_tekst'] = NULL;
		
		//omzetten naar decimaal
		if( strpos( $row['aantal'], ':' ) !== false )
			$row['aantal'] = h2d( $row['aantal'] );
		
		$row['invoer_id'] = intval( $row['invoer_id'] );
		$row['datum'] = reverseDate( $row['datum'] );
		$row['uren_type_id_werknemer'] = $row['urentype_id'];
		
		if( is_numeric($row['project_id']))
			$row['project_id'] = intval($row['project_id']);
		else
			$row['project_id'] = NULL;
		
		unset( $row['urentype_id'] );

		return $row;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij ophalen
	 *
	 */
	public function getRow( $invoer_id ): ?array
	{
		$query = $this->db_user->query( "SELECT * FROM invoer_uren WHERE invoer_id = ?", array( $invoer_id ) );
		return DBhelper::toRow( $query, 'NULL' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij opslaan
	 * TODO: fout afhandeling
	 */
	public function setRow( $row ): ?array
	{
		$set = $this->_validateInput( $row );
		
		//nieuwe entry
		if( $set['invoer_id'] == 0 )
		{
			
			if( $this->user->werkgever_type == 'uitzenden' )
			{
				$plaatsingGroup = new \models\werknemers\PlaatsingGroup();
				$plaatsingen = $plaatsingGroup->werknemer( $this->_werknemer_id )->inlener( $this->_inlener_id )->all();
			}
			if( $this->user->werkgever_type == 'bemiddeling' )
			{
				$plaatsingGroup = new \models\zzp\PlaatsingGroup();
				$plaatsingen = $plaatsingGroup->zzp( $this->_zzp_id )->inlener( $this->_inlener_id )->all();
			}
			
			$set['uitzender_id'] = $this->_uitzender_id;
			$set['inlener_id'] = $this->_inlener_id;
			$set['zzp_id'] = $this->_zzp_id;
			$set['werknemer_id'] = $this->_werknemer_id;
			$set['plaatsing_id'] = key( $plaatsingen );
			
			$this->db_user->insert( 'invoer_uren', $set );
			
			$set['invoer_id'] = $this->db_user->insert_id();
		}
		else
		{
			$oude_entry = $this->getRow( $set['invoer_id'] );
			
			$this->db_user->where( 'invoer_id', $set['invoer_id'] );
			$this->db_user->where( 'factuur_id',  NULL );
			$this->db_user->where( 'werknemer_id', $this->_werknemer_id );
			$this->db_user->update( 'invoer_uren', $set );
			
			$this->_logUrenActie( 'update', $oude_entry );
		}
		
		return $set;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Standaard aantal uren invullen en verdelen
	 *
	 */
	public function fillUren( $aantal ): bool
	{
		$per_dag = round($aantal / 5,2);
		
		$uren = $this->urenMatrix();
		
		$urentypesGroup = new UrentypesGroup();

		if( $this->user->werkgever_type == 'uitzenden' )
			$urentypes = $urentypesGroup->inlener(  $_POST['inlener_id'] )->urentypesWerknemer( $_POST['werknemer_id'] );

		if( $this->user->werkgever_type == 'bemiddeling' )
			$urentypes = $urentypesGroup->inlener(  $_POST['inlener_id'] )->urentypesZzp( $_POST['werknemer_id'] );

		//standaard urentype er uit halen
		$urentype_id = current(array_keys(array_combine(array_keys($urentypes), array_column($urentypes, 'default_urentype')),1));

		foreach( $uren as $dag )
		{
			if( !isset($dag['rows']) && $dag['dag'] != 'za' && $dag['dag'] != 'zo' )
			{
				$row['invoer_id'] = 0;
				$row['datum'] = $dag['datum'];
				$row['aantal'] = $per_dag;
				$row['urentype_id'] = $urentype_id;
				$row['project_tekst'] = NULL;
				$row['project_id'] = NULL;
				$row['locatie_tekst'] = NULL;
				
				$this->setRow($row);
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij verwijderen
	 *
	 */
	public function delRow( $row ): bool
	{
		$oude_entry = $this->getRow( $row['invoer_id'] );

		if( $this->user->werkgever_type == 'uitzenden' )
			$this->db_user->query( "DELETE FROM invoer_uren WHERE factuur_id IS NULL AND invoer_id = ? AND werknemer_id = ?", array( $row['invoer_id'], $this->_werknemer_id ) );

		if( $this->user->werkgever_type == 'bemiddeling' )
			$this->db_user->query( "DELETE FROM invoer_uren WHERE factuur_id IS NULL AND invoer_id = ? AND zzp_id = ?", array( $row['invoer_id'], $this->_zzp_id ) );


		if( $this->db_user->affected_rows() != -1 )
		{
			$this->_logUrenActie( 'delete', $oude_entry );
			return true;
		}
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Rij verwijderen
	 *
	 */
	public function delAll(): bool
	{
		$uren = $this->urenMatrix();
		
		foreach( $uren as $datum )
		{
			if( isset($datum['rows']) )
			{
				foreach( $datum['rows'] as $row )
				{
					$this->delRow($row);
				}
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Log actie
	 *
	 */
	private function _logUrenActie( $action, $row )
	{
		$insert['user_id'] = $this->user->user_id;
		$insert['json'] = json_encode( $row );
		$insert['action'] = $action;
		$insert['invoer_id'] = $row['invoer_id'];
		
		@$this->db_user->insert( 'invoer_uren_log', $insert );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Urenmatrix opbouwen voor invoer
	 *
	 */
	public function urenMatrix()
	{
		//eerst lege matrix opbouwen
		foreach( $this->_periode_dagen as $datum )
		{
			$matrix[$datum]['datum'] = reverseDate( $datum );
			$matrix[$datum]['week'] = Tijdvak::weeknr( $datum );
			$matrix[$datum]['dag'] = Tijdvak::dagAfkorting( $datum );
			$matrix[$datum]['class'] = 'tr-'.$matrix[$datum]['dag'];
			if( Tijdvak::isWeekend( $datum ) )
				$matrix[$datum]['class'] = 'tr-weekend';
		}
		
		//uren ophalen
		if( $this->user->werkgever_type == 'uitzenden' ) $uren = $this->getWerknemerUren();
		if( $this->user->werkgever_type == 'bemiddeling' ) $uren = $this->getZzpUren();
		
		if( $uren === NULL )
			return $matrix;
		
		foreach( $uren as $key => $row )
		{
			//aantal gegevens filteren die niet naar json mogen
			
			$matrix[$row['datum']]['rows'][$key] = $row;
		}
		
		//dummy uren
		/*
		$matrix['2020-01-06']['rows'][1]['invoer_id'] = 1;
		$matrix['2020-01-06']['rows'][1]['aantal'] = 8;
		$matrix['2020-01-06']['rows'][1]['urentype_id'] = 10;
		$matrix['2020-01-06']['rows'][1]['project_tekst'] = 'Afbouw';
		$matrix['2020-01-06']['rows'][1]['locatie_tekst'] = 'Den Helder';
		
		$matrix['2020-01-06']['rows'][2]['invoer_id'] = 2;
		$matrix['2020-01-06']['rows'][2]['aantal'] = '2:30';
		$matrix['2020-01-06']['rows'][2]['urentype_id'] = 11;
		$matrix['2020-01-06']['rows'][2]['project_tekst'] = 'Timmerwerk';
		$matrix['2020-01-06']['rows'][2]['locatie_tekst'] = 'Amsterdam';*/
		
		return $matrix;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * samenvatting ophalen voor overzicht
	 *
	 */
	public function getWerknemerUrenSamenvatting()
	{
		$totaalRows = $this->getWerknemersUrenRijen();
		$uren = NULL;

		if( $totaalRows === NULL )
			return $uren;

		foreach( $totaalRows as $werknemer_id => $urenRows )
		{
			foreach( $urenRows as $row )
			{
				$label = strtolower($row['label']);
				
				if( !isset($uren[$werknemer_id][$row['urentype_id']]) )
				{
					$uren[$werknemer_id][$row['urentype_id']]['label'] = $label;
					$uren[$werknemer_id][$row['urentype_id']]['aantal'] = 0;
				}
				
				$uren[$werknemer_id][$row['urentype_id']]['aantal'] += $row['aantal'];
			}
		}
		
		return $uren;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle uren voor werknemer ophalen voor ajax invoer
	 *
	 */
	public function getWerknemerUren()
	{
		$sql = "SELECT invoer_uren.invoer_id, invoer_uren.aantal, invoer_uren.uren_type_id_werknemer, invoer_uren.datum, invoer_uren.project_id, invoer_uren.project_tekst, invoer_uren.locatie_tekst, invoer_uren.uitkeren,
       					urentypes.urentype_categorie_id, invoer_uren.factuur_id
				FROM invoer_uren
				LEFT JOIN werknemers_urentypes ON invoer_uren.uren_type_id_werknemer = werknemers_urentypes.id
				LEFT JOIN urentypes ON urentypes.urentype_id = werknemers_urentypes.urentype_id
				WHERE invoer_uren.werknemer_id = ? AND invoer_uren.inlener_id = ? AND invoer_uren.datum >= ? AND invoer_uren.datum <= ?";
		
		$query = $this->db_user->query( $sql, array( $this->_werknemer_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['urentype_id'] = $row['uren_type_id_werknemer'];
			
			if( $row['project_tekst'] === NULL )
				$row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL )
				$row['locatie_tekst'] = '';
			
			$row['decimaal'] = $row['aantal'];
			$row['aantal'] = d2h( $row['aantal'] );
			
			$data[$row['invoer_id']] = $row;
		}
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle uren voor werknemer ophalen voor ajax invoer
	 *
	 */
	public function getZzpUren()
	{
		$sql = "SELECT invoer_uren.invoer_id, invoer_uren.aantal, invoer_uren.uren_type_id_werknemer, invoer_uren.datum, invoer_uren.project_id, invoer_uren.project_tekst, invoer_uren.locatie_tekst, invoer_uren.uitkeren,
       					urentypes.urentype_categorie_id, invoer_uren.factuur_id
				FROM invoer_uren
				LEFT JOIN zzp_urentypes ON invoer_uren.uren_type_id_werknemer = zzp_urentypes.id
				LEFT JOIN urentypes ON urentypes.urentype_id = zzp_urentypes.urentype_id
				WHERE invoer_uren.zzp_id = ? AND invoer_uren.inlener_id = ? AND invoer_uren.datum >= ? AND invoer_uren.datum <= ?";
		
		$query = $this->db_user->query( $sql, array( $this->_zzp_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['urentype_id'] = $row['uren_type_id_werknemer'];
			
			if( $row['project_tekst'] === NULL )
				$row['project_tekst'] = '';
			if( $row['locatie_tekst'] === NULL )
				$row['locatie_tekst'] = '';
			
			$row['decimaal'] = $row['aantal'];
			$row['aantal'] = d2h( $row['aantal'] );
			
			$data[$row['invoer_id']] = $row;
		}
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * totalen ophalen voor overzichten
	 *
	 */
	public function getWerknemersUrenRijen()
	{
		$sql = "SELECT  invoer_uren.invoer_id, invoer_uren.werknemer_id, invoer_uren.zzp_id, invoer_uren.datum, invoer_uren.aantal, invoer_uren.plaatsing_id, invoer_uren.doorbelasten, invoer_uren.project_id, invoer_uren.project_tekst, invoer_uren.locatie_tekst,
       					DAYOFWEEK(invoer_uren.datum) AS dag_nr, WEEK(invoer_uren.datum, 3) AS week_nr,
       					urentypes.naam, urentypes.percentage, urentypes.urentype_id,
       					urentypes_categorien.naam AS categorie,
						inleners_urentypes.doorbelasten_uitzender, inleners_urentypes.label
				FROM invoer_uren
				LEFT JOIN werknemers_urentypes ON invoer_uren.uren_type_id_werknemer = werknemers_urentypes.id
				LEFT JOIN inleners_urentypes ON werknemers_urentypes.inlener_urentype_id = inleners_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien ON urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				LEFT JOIN werknemers_inleners ON werknemers_inleners.plaatsing_id = werknemers_urentypes.plaatsing_id
				WHERE werknemers_inleners.deleted = 0 AND invoer_uren.verloning_id IS NULL
				  AND invoer_uren.werknemer_id IN (".array_keys_to_string($this->_werknemer_ids).") AND invoer_uren.inlener_id = ? AND invoer_uren.datum >= ? AND invoer_uren.datum <= ?";
		
		//run query
		$query = $this->db_user->query( $sql, array( $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			//naam naar label
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			$data[$row['werknemer_id']][$row['invoer_id']] = $row;
		}

		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle urenrijen voor werknemer ophalen voor facturatie
	 * bevat meer info die niet over JSON mag
	 *
	 */
	public function getWerknemerUrenRijen()
	{
		$sql = "SELECT  invoer_uren.invoer_id, invoer_uren.werknemer_id, invoer_uren.zzp_id, invoer_uren.datum, invoer_uren.aantal, invoer_uren.plaatsing_id, invoer_uren.doorbelasten, invoer_uren.project_id, invoer_uren.project_tekst, invoer_uren.locatie_tekst,
       					DAYOFWEEK(invoer_uren.datum) AS dag_nr, WEEK(invoer_uren.datum, 3) AS week_nr,
       					werknemers_urentypes.verkooptarief,
       					urentypes.naam, urentypes.percentage,
       					urentypes_categorien.factor, urentypes_categorien.naam AS categorie,
						inleners_urentypes.doorbelasten_uitzender, inleners_urentypes.label,
      					werknemers_inleners.bruto_loon,
       					inleners_factoren.factor_hoog, inleners_factoren.factor_laag
				FROM invoer_uren
				LEFT JOIN werknemers_urentypes ON invoer_uren.uren_type_id_werknemer = werknemers_urentypes.id
				LEFT JOIN inleners_urentypes ON werknemers_urentypes.inlener_urentype_id = inleners_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien ON urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				LEFT JOIN werknemers_inleners ON werknemers_inleners.plaatsing_id = werknemers_urentypes.plaatsing_id
				LEFT JOIN inleners_factoren ON werknemers_inleners.factor_id = inleners_factoren.factor_id
				WHERE inleners_factoren.deleted = 0 AND werknemers_inleners.deleted = 0 AND invoer_uren.factuur_id IS NULL AND invoer_uren.verloning_id IS NULL
				  AND invoer_uren.werknemer_id = ? AND invoer_uren.inlener_id = ? AND invoer_uren.datum >= ? AND invoer_uren.datum <= ?";
	
		//run query
		$query = $this->db_user->query( $sql, array( $this->_werknemer_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );

		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			//juiste factor
			$row['factor'] = $row['factor_' . $row['factor']];
			
			$row['uurtarief'] = NULL;
			$row['marge'] = NULL;
			
			//naam naar label
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			$data[$row['invoer_id']] = $row;
		}/*
		if( $this->_werknemer_id == 20017 )
		{
			show( $data );
			die();
		}*/
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle urenrijen voor zzp'er ophalen voor facturatie
	 * bevat meer info die niet over JSON mag
	 *
	 */
	public function getZzpUrenRijen()
	{
		$sql = "SELECT  invoer_uren.invoer_id, invoer_uren.zzp_id, invoer_uren.zzp_id, invoer_uren.datum, invoer_uren.aantal, invoer_uren.plaatsing_id, invoer_uren.doorbelasten, invoer_uren.project_id, invoer_uren.project_tekst, invoer_uren.locatie_tekst,
       					DAYOFWEEK(invoer_uren.datum) AS dag_nr, WEEK(invoer_uren.datum, 3) AS week_nr,
       					zzp_urentypes.verkooptarief, zzp_urentypes.uurtarief, zzp_urentypes.marge,
       					urentypes.naam, urentypes.percentage,
       					urentypes_categorien.naam AS categorie,
						inleners_urentypes.doorbelasten_uitzender, inleners_urentypes.label
				FROM invoer_uren
				LEFT JOIN zzp_urentypes ON invoer_uren.uren_type_id_werknemer = zzp_urentypes.id
				LEFT JOIN inleners_urentypes ON zzp_urentypes.inlener_urentype_id = inleners_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON inleners_urentypes.urentype_id = urentypes.urentype_id
				LEFT JOIN urentypes_categorien ON urentypes.urentype_categorie_id = urentypes_categorien.urentype_categorie_id
				LEFT JOIN zzp_inleners ON zzp_inleners.plaatsing_id = zzp_urentypes.plaatsing_id
				WHERE zzp_inleners.deleted = 0 AND invoer_uren.factuur_id IS NULL AND invoer_uren.verloning_id IS NULL
				  AND invoer_uren.zzp_id = ? AND invoer_uren.inlener_id = ? AND invoer_uren.datum >= ? AND invoer_uren.datum <= ?";
		
		//run query
		$query = $this->db_user->query( $sql, array( $this->_zzp_id, $this->_inlener_id, $this->_periode_start, $this->_periode_einde ) );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			//naam naar label
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			$row['bruto_loon'] = NULL;
			$row['factor'] = 1;
			
			$data[$row['invoer_id']] = $row;
		}
		
		return $data;
	}
}

?>