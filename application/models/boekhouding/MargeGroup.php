<?php

namespace models\boekhouding;

use models\Connector;
use models\utils\DBhelper;
use models\utils\Tijdvak;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 *Omzet data
 *
 *
 */

class MargeGroup extends Connector
{
	protected $_error = NULL;
	
	private $_uitzender_id = NULL;
	private $_inlener_ids = NULL;
	
	private $_info_jaren = array();
	private $_info_inleners = array();
	
	private $_set_jaar = NULL;
	private $_set_inleners = NULL;
	private $_set_split = 'inlener';
	private $_datum_start = NULL;
	private $_datum_eind = NULL;
	
	private $_data_marge_totaal_per_week = array();
	private $_data_marge_inlener_per_week = array();
	private $_data_marge_werknemer_per_week = array();
	
	private $_data_uren_totaal_per_week = array();
	private $_data_uren_inlener_per_week = array();
	private $_data_uren_werknemer_per_week = array();
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//default dit jaar
		$this->jaar( date('Y') );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  uitzender
	 *
	 */
	public function uitzender( $id = NULL ) :MargeGroup
	{
		$this->_uitzender_id = intval($id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  jaar
	 *
	 */
	public function infoInleners() :array
	{
		$sql = "SELECT facturen.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON facturen.inlener_id = inleners_bedrijfsgegevens.inlener_id
				WHERE facturen.concept = 0 AND facturen.deleted = 0 AND facturen.uitzender_id = $this->_uitzender_id AND facturen.jaar = $this->_set_jaar
				AND inleners_bedrijfsgegevens.deleted = 0
				GROUP BY inleners_bedrijfsgegevens.bedrijfsnaam
				ORDER BY inleners_bedrijfsgegevens.bedrijfsnaam";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$this->_info_inleners[$row['inlener_id']] = $row['bedrijfsnaam'];
				
				//set default alle inleners
				$this->_set_inleners[] = $row['inlener_id'];
			}
		}
		
		return $this->_info_inleners;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  jaar
	 *
	 */
	public function infoJaren() :array
	{
		$query = $this->db_user->query( "SELECT DISTINCT jaar FROM facturen WHERE concept = 0 AND deleted = 0 AND uitzender_id = $this->_uitzender_id" );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
				$this->_info_jaren[] = $row['jaar'];
		}
		else
			$this->_info_jaren[] = date('Y');
		
		return $this->_info_jaren;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  jaar
	 *
	 */
	public function jaar( $jaar = NULL ): MargeGroup
	{
		$this->_set_jaar = $jaar;
		if($this->_set_jaar === NULL)
			$this->_set_jaar = date('Y');
		return $this;
	}



	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * data ophalen
	 *
	 */
	public function opsplitsenPer( $split = NULL ): MargeGroup
	{
		if( $split == 'inlener' || $split == 'werknemer')
			$this->_set_split = $split;
		
		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * data ophalen
	 *
	 */
	public function inleners( $inleners = NULL ): MargeGroup
	{
		$this->_set_inleners = $inleners;
		
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * marge data werknemers instellen
	 *
	 */
	public function calcMargeDataWerknemers() :?bool
	{
		if($this->_set_inleners === NULL)
			return false;

		$sql = "SELECT facturen.factuur_id, facturen.periode, facturen.tijdvak, (facturen_regels.subtotaal_verkoop - facturen_regels.subtotaal_kosten) AS marge, facturen_regels.werknemer_id, wg.voornaam, wg.tussenvoegsel, wg.voorletters, wg.achternaam
				FROM facturen_regels
				LEFT JOIN facturen ON facturen_regels.factuur_id = facturen.factuur_id
				LEFT JOIN werknemers_gegevens wg ON facturen_regels.werknemer_id = wg.werknemer_id
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0 AND facturen.jaar = $this->_set_jaar AND facturen.uitzender_id = $this->_uitzender_id AND inlener_id IN (".implode(',', $this->_set_inleners).")
				AND facturen_regels.row_end = 1 AND wg.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return  false;
		
		//weken aanmaken
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( $this->_set_jaar, 0 );
		
		
		foreach( $query->result_array() as $row )
		{
			$marge = $row['marge']; //positief en nagatief omwisselen
			$werknemer_id = $row['werknemer_id'];
			$periode = $row['periode'];
			$naam = make_name($row);
			
			//init werknemer
			if(!isset($this->_data_marge_werknemer_per_week[$werknemer_id]['totaal']))
			{
				$this->_data_marge_werknemer_per_week[$werknemer_id]['totaal'] = 0;
				$this->_data_marge_werknemer_per_week[$werknemer_id]['werknemer'] = $naam;
				$this->_data_marge_werknemer_per_week[$werknemer_id]['weken'] = $weken_array;
			}
			
			$this->_data_marge_werknemer_per_week[$werknemer_id]['totaal'] += $marge;
			
			//week: marge gewoon optellen
			if( $row['tijdvak'] == 'w' )
			{
				$this->_data_marge_werknemer_per_week[$werknemer_id]['weken'][$periode] += $marge;
			}
			
			//4 weken splitsen
			if( $row['tijdvak'] == '4w' )
			{
				$p1 = ( $periode * 4 ) - 3;
				$p2 = ( $periode * 4 ) - 2;
				$p3 = ( $periode * 4 ) - 1;
				$p4 = ( $periode * 4 );
				
				$bedrag = round( $marge / 4, 2 );
				
				$this->_data_marge_werknemer_per_week[$werknemer_id]['weken'][$p1] += $marge;
				$this->_data_marge_werknemer_per_week[$werknemer_id]['weken'][$p2] += $marge;
				$this->_data_marge_werknemer_per_week[$werknemer_id]['weken'][$p3] += $marge;
				$this->_data_marge_werknemer_per_week[$werknemer_id]['weken'][$p4] += $marge;
			}
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * marge data instellen
	 *
	 */
	public function calcMargeData() :?bool
	{
		if($this->_set_inleners === NULL)
			return false;

		$sql = "SELECT bedrag_excl, tijdvak, periode, jaar, inlener_id
				FROM facturen
				WHERE marge = 1 AND concept = 0 AND deleted = 0 AND jaar = $this->_set_jaar AND uitzender_id = $this->_uitzender_id AND inlener_id IN (".implode(',', $this->_set_inleners).")";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return  false;
		
		//weken aanmaken
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( $this->_set_jaar, 0 );
		
		//init totalen
		$this->_data_marge_totaal_per_week['totaal'] = 0;
		$this->_data_marge_totaal_per_week['weken'] = $weken_array;
		
		foreach( $this->_set_inleners as $id )
		{
			$this->_data_marge_inlener_per_week[$id]['totaal'] = 0;
			$this->_data_marge_inlener_per_week[$id]['inlener'] = $this->_info_inleners[$id];
			$this->_data_marge_inlener_per_week[$id]['weken'] = $weken_array;
		}
		
		foreach( $query->result_array() as $row )
		{
			$excl = $row['bedrag_excl'] * -1; //positief en nagatief omwisselen
			$inlener_id = $row['inlener_id'];
			$periode = $row['periode'];
			
			//altijd totaal optellen
			$this->_data_marge_totaal_per_week['totaal'] += $excl;
			$this->_data_marge_inlener_per_week[$inlener_id]['totaal'] += $excl;
			
			//week: marge gewoon optellen
			if( $row['tijdvak'] == 'w' )
			{
				$this->_data_marge_totaal_per_week['weken'][$periode] += $excl;
				$this->_data_marge_inlener_per_week[$inlener_id]['weken'][$periode] += $excl;
			}
			
			//4 weken splitsen
			if( $row['tijdvak'] == '4w' )
			{
				$p1 = ($periode*4)-3;
				$p2 = ($periode*4)-2;
				$p3 = ($periode*4)-1;
				$p4 = ($periode*4);
				
				$bedrag = round($excl / 4,2);
				
				$this->_data_marge_totaal_per_week['weken'][$p1] += $bedrag;
				$this->_data_marge_totaal_per_week['weken'][$p2] += $bedrag;
				$this->_data_marge_totaal_per_week['weken'][$p3] += $bedrag;
				$this->_data_marge_totaal_per_week['weken'][$p4] += $bedrag;
				$this->_data_marge_inlener_per_week[$inlener_id]['weken'][$p1] += $bedrag;
				$this->_data_marge_inlener_per_week[$inlener_id]['weken'][$p2] += $bedrag;
				$this->_data_marge_inlener_per_week[$inlener_id]['weken'][$p3] += $bedrag;
				$this->_data_marge_inlener_per_week[$inlener_id]['weken'][$p4] += $bedrag;
			}
			
			//TODO maand
			
			
		}
		
		//0 vervangen door null
		for( $i = 53; $i >= 0; $i-- )
		{
			if( $this->_data_marge_totaal_per_week['weken'][$i] == 0)
				$this->_data_marge_totaal_per_week['weken'][$i] = 'null';
			else
				break;
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * marge data ophalen
	 *
	 */
	public function getMargeDataTotaalUitzender() :?array
	{
		return $this->_data_marge_totaal_per_week;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * marge data ophalen
	 *
	 */
	public function getMargeDataInleners() :?array
	{
		return $this->_data_marge_inlener_per_week;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * marge data werknemers ophalen
	 *
	 */
	public function getMargeDataWerknemers() :?array
	{
		return $this->_data_marge_werknemer_per_week;
	}

	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * top 5 bepalen
	 *
	 */
	public function getTop5MargeInleners() :?array
	{
		$inleners = [];

		foreach( $this->_data_marge_inlener_per_week as $inlener_id => $inlener )
			$marge[$inlener_id] = $inlener['totaal'] ;

		if(!isset($marge) || !is_array($marge) || count($marge) == 0)
			return $inleners;

		arsort($marge);
		
		$top5 = array_slice( $marge, 0, 5, true);

		if( count($top5) == 0 )
			return $inleners;

		foreach( $top5 as $inlener_id => $value )
		{
			$inleners[$inlener_id]['inlener'] = $this->_info_inleners[$inlener_id];
			$inleners[$inlener_id]['marge'] = $value;
			$inleners[$inlener_id]['percentage'] = round(($value/$this->_data_marge_totaal_per_week['totaal'])*100,2);
		}
		
		return $inleners;
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren data ophalen
	 *
	 */
	public function getUrenData() :?bool
	{
		if($this->_set_inleners === NULL)
			return false;

		$sql = "SELECT SUM(aantal) as aantal, datum, WEEK(invoer_uren.datum, 3) AS week_nr, inlener_id, invoer_uren.werknemer_id, wg.voornaam, wg.tussenvoegsel, wg.voorletters, wg.achternaam
				FROM invoer_uren
				LEFT JOIN werknemers_gegevens wg ON invoer_uren.werknemer_id = wg.werknemer_id
				WHERE uitzender_id = $this->_uitzender_id
					AND datum >= '$this->_set_jaar-01-01' AND datum <= '$this->_set_jaar-12-31' AND factuur_id IS NOT NULL AND wg.deleted = 0
					AND inlener_id IN (".implode(',', $this->_set_inleners).")
				GROUP BY datum, invoer_uren.werknemer_id
				";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return false;
		
		//weken aanmaken
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( $this->_set_jaar, 0 );
		
		$this->_data_uren_totaal_per_week['totaal'] = 0;
		$this->_data_uren_totaal_per_week['weken'] = $weken_array;
		
		foreach( $this->_set_inleners as $id )
		{
			$this->_data_uren_inlener_per_week[$id]['totaal'] = 0;
			$this->_data_uren_inlener_per_week[$id]['weken'] = $weken_array;
		}
		
		foreach( $query->result_array() as $row )
		{
			$uren = $row['aantal'];
			$week = $row['week_nr'];
			$inlener_id = $row['inlener_id'];
			$werknemer_id = $row['werknemer_id'];
			
			$this->_data_uren_totaal_per_week['totaal'] += $uren;
			$this->_data_uren_totaal_per_week['weken'][$week] += $uren;
		
			$this->_data_uren_inlener_per_week[$inlener_id]['totaal'] += $uren;
			$this->_data_uren_inlener_per_week[$inlener_id]['weken'][$week] += $uren;
			
			//init werknemer
			if(!isset($this->_data_uren_werknemer_per_week[$werknemer_id]['totaal']))
			{
				$this->_data_uren_werknemer_per_week[$werknemer_id]['totaal'] = 0;
				$this->_data_uren_werknemer_per_week[$werknemer_id]['weken'] = $weken_array;
			}
			
			$this->_data_uren_werknemer_per_week[$werknemer_id]['totaal'] += $uren;
			$this->_data_uren_werknemer_per_week[$werknemer_id]['weken'][$week] += $uren;
			
		}
		
		//0 vervangen door null
		for( $i = 53; $i >= 0; $i-- )
		{
			if( $this->_data_uren_totaal_per_week['weken'][$i] == 0)
				$this->_data_uren_totaal_per_week['weken'][$i] = 'null';
			else
				break;
		}
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren data ophalen
	 *
	 */
	public function getUrenDataTotaalUitzender() :?array
	{
		return $this->_data_uren_totaal_per_week;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren data ophalen
	 *
	 */
	public function getUrenDataInleners() :?array
	{
		return $this->_data_uren_inlener_per_week;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren data ophalen
	 *
	 */
	public function getUrenDataWerknemers() :?array
	{
		return $this->_data_uren_werknemer_per_week;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_error === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}

		if ($this->_error === NULL)
			return false;

		return $this->_error;
	}
}


?>