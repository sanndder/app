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

	private $_set_tijdvak = NULL;
	private $_set_periode = NULL;
	
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
	 * alle data
	 *
	 */
	public function getUrenAlleUitzenders() :?array
	{
		$sql = "SELECT uitzender_id, SUM(aantal) AS uren, WEEK(datum,3) AS week FROM invoer_uren
				WHERE factuur_id IS NOT NULL AND YEAR(datum) = YEAR(CURDATE()) AND uitzender_id > 0
				GROUP BY uitzender_id, WEEK(datum,3)";
		$query = $this->db_user->query( $sql );

		if( $query->num_rows() == 0 )
			return NULL;
		
		//weken aanmaken
		$tijdvak = new Tijdvak( 'w' );
		$weken_array = $tijdvak->wekenArray( $this->_set_jaar, 0 );
		
		foreach( $query->result_array() as $row )
		{
			if(!isset($data[$row['uitzender_id']]['weken']))
				$data[$row['uitzender_id']]['weken'] = $weken_array;
			
			$data[$row['uitzender_id']]['weken'][$row['week']] = round($row['uren']);
		}
		
	
		//0 vervangen door null
		foreach($data as $uitzender_id => &$arr )
		{
			for( $i = 53; $i >= 0; $i-- )
			{
				if( !isset( $arr['weken'][$i] ) ) //week 53 bestaat niet elk jaar
					continue;
				
				if( $arr['weken'][$i] == 0 )
					$arr['weken'][$i] = 'null';
				else
					break;
			}
			
			$data[$uitzender_id]['string'] = '['.implode(',',$arr['weken']) .']';
		}

		return $data;
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
	 * set  tijdvak
	 *
	 */
	public function tijdvak( $val = NULL ) :MargeGroup
	{
		if( $val == 'w' || $val == '4w' || $val == 'm')
			$this->_set_tijdvak = $val;
		
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  tijdvak
	 *
	 */
	public function periode( $val = NULL ) :MargeGroup
	{
		$this->_set_periode = intval($val);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  jaar
	 *
	 */
	public function infoInleners() :array
	{
		$sql = "SELECT DISTINCT facturen.inlener_id, inleners_bedrijfsgegevens.bedrijfsnaam
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON facturen.inlener_id = inleners_bedrijfsgegevens.inlener_id
				WHERE facturen.concept = 0 AND facturen.deleted = 0 AND facturen.uitzender_id = $this->_uitzender_id AND facturen.jaar = $this->_set_jaar
				AND inleners_bedrijfsgegevens.deleted = 0
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
	public function weekoverzicht() :?array
	{
		$sql = "SELECT facturen.factuur_id, facturen.factuur_nr, facturen.inlener_id, ib.bedrijfsnaam AS inlener, facturen.periode, facturen.tijdvak, row_start, row_end, omschrijving, uren_decimaal, uren_aantal, uitkeren_werknemer, subtotaal_verkoop, subtotaal_kosten,
       				(facturen_regels.subtotaal_verkoop - facturen_regels.subtotaal_kosten) AS marge, facturen_regels.werknemer_id, wg.voornaam, wg.tussenvoegsel, wg.voorletters, wg.achternaam
				FROM facturen_regels
				LEFT JOIN facturen ON facturen_regels.factuur_id = facturen.factuur_id
				LEFT JOIN werknemers_gegevens wg ON facturen_regels.werknemer_id = wg.werknemer_id
				LEFT JOIN inleners_bedrijfsgegevens ib ON facturen.inlener_id = ib.inlener_id
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0 AND facturen.jaar = $this->_set_jaar
				  	AND facturen.uitzender_id = $this->_uitzender_id AND periode = $this->_set_periode AND tijdvak = '$this->_set_tijdvak'
					AND wg.deleted = 0 AND ib.deleted = 0 AND row_start IS NULL AND row_end IS NULL
				GROUP BY regel_id
				ORDER BY facturen_regels.regel_id";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		//init
		$werknemer_template = 	[
			'uren_aantal_verkoop' => 0,
			'uren_aantal_kosten' => 0,
			'uren_bedrag_verkoop' => 0,
			'uren_bedrag_kosten' => 0,
			'vergoedingen_bedrag_verkoop' => 0,
			'vergoedingen_bedrag_kosten' => 0,
			'totaal_bedrag_verkoop' => 0,
			'totaal_bedrag_kosten' => 0,
			'totaal_bedrag_marge' => 0,
			'percentage_marge' => 0
		];
		
		foreach( $query->result_array() as $row )
		{
			$data[] = $row;
			$werknemer_id = $row['werknemer_id'];
			$inlener_id = $row['inlener_id'];
			
			$weekoverzicht[$inlener_id]['inlener'] = $row['inlener'];
			
			if( !isset(	$weekoverzicht[$inlener_id]['werknemers']['totaal']))
				$weekoverzicht[$inlener_id]['werknemers']['totaal'] = $werknemer_template;
			
			//aanmaken
			if( !isset($weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]) )
			{
				$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id] = $werknemer_template;
				$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['naam'] = make_name( $row );;
			}
			
			//uren optellen
			if( $row['uren_aantal'] !== NULL )
			{
				//wanneer verkoop uren
				if( $row['subtotaal_verkoop'] !== NULL && $row['subtotaal_verkoop'] !== 0 )
				{
					$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['uren_aantal_verkoop'] += $row['uren_decimaal'];
					$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['uren_bedrag_verkoop'] += $row['subtotaal_verkoop'];
					$weekoverzicht[$inlener_id]['werknemers']['totaal']['uren_aantal_verkoop'] += $row['uren_decimaal'];
					$weekoverzicht[$inlener_id]['werknemers']['totaal']['uren_bedrag_verkoop'] += $row['subtotaal_verkoop'];
				}
				//zijn het ook kosten?
				if( $row['uitkeren_werknemer'] == 1 )
				{
					$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['uren_aantal_kosten'] += $row['uren_decimaal'];
					$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['uren_bedrag_kosten'] += $row['subtotaal_kosten'];
					$weekoverzicht[$inlener_id]['werknemers']['totaal']['uren_aantal_kosten'] += $row['uren_decimaal'];
					$weekoverzicht[$inlener_id]['werknemers']['totaal']['uren_bedrag_kosten'] += $row['subtotaal_kosten'];
				}
			}
			
			//vergoedingen/kosten
			if( $row['uren_aantal'] === NULL )
			{
				//wanneer verkoop uren
				if( $row['subtotaal_verkoop'] !== NULL && $row['subtotaal_verkoop'] != 0 )
				{
					$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['vergoedingen_bedrag_verkoop'] += $row['subtotaal_verkoop'];
					$weekoverzicht[$inlener_id]['werknemers']['totaal']['vergoedingen_bedrag_verkoop'] += $row['subtotaal_verkoop'];
				}
				//zijn het ook kosten?
				if( $row['uitkeren_werknemer'] == 1 )
				{
					$weekoverzicht[$inlener_id]['werknemers'][$werknemer_id]['vergoedingen_bedrag_kosten'] += $row['subtotaal_kosten'];
					$weekoverzicht[$inlener_id]['werknemers']['totaal']['vergoedingen_bedrag_kosten'] += $row['subtotaal_kosten'];
				}
			}
			
		}
		
		$totaal = $werknemer_template;
		
		//nu percentages uitrekenen
		foreach( $weekoverzicht as $inlener_id => &$inlener )
		{
			foreach( $inlener['werknemers'] as $werknemer_id => &$werknemer )
			{
				$werknemer['totaal_bedrag_verkoop'] = $werknemer['uren_bedrag_verkoop'] + $werknemer['vergoedingen_bedrag_verkoop'];
				$werknemer['totaal_bedrag_kosten'] = $werknemer['uren_bedrag_kosten'] + $werknemer['vergoedingen_bedrag_kosten'];
				$werknemer['totaal_bedrag_marge'] = $werknemer['totaal_bedrag_verkoop'] - $werknemer['totaal_bedrag_kosten'];
				
				$werknemer['percentage_marge'] = round( ( $werknemer['totaal_bedrag_marge'] / $werknemer['totaal_bedrag_verkoop'] ) * 100, 2);
				
				//totaaltelling
				if( $werknemer_id == 'totaal' )
				{
					$totaal['uren_aantal_verkoop'] += $werknemer['uren_aantal_verkoop'];
					$totaal['uren_aantal_kosten'] += $werknemer['uren_aantal_kosten'];
					$totaal['uren_bedrag_verkoop'] += $werknemer['uren_bedrag_verkoop'];
					$totaal['uren_bedrag_kosten'] += $werknemer['uren_bedrag_kosten'];
					$totaal['vergoedingen_bedrag_verkoop'] += $werknemer['vergoedingen_bedrag_verkoop'];
					$totaal['vergoedingen_bedrag_kosten'] += $werknemer['vergoedingen_bedrag_kosten'];
					$totaal['totaal_bedrag_verkoop'] += $werknemer['totaal_bedrag_verkoop'];
					$totaal['totaal_bedrag_kosten'] += $werknemer['totaal_bedrag_kosten'];
					$totaal['totaal_bedrag_marge'] += $werknemer['totaal_bedrag_marge'];
				}
			}
		}
		
		//totaal percentage
		$totaal['percentage_marge'] = round( ( $totaal['totaal_bedrag_marge'] / $totaal['totaal_bedrag_verkoop'] ) * 100, 2);
		$weekoverzicht['totaal'] = $totaal;
	
		if(isset($_GET['s']))
		{
			show($weekoverzicht);
			die();
		}
		
		return $weekoverzicht;
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
			if( !isset( $this->_data_marge_totaal_per_week['weken'][$i] )) //week 53 bestaat niet elk jaar
				continue;
			
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
			if( !isset( $this->_data_marge_totaal_per_week['weken'][$i] )) //week 53 bestaat niet elk jaar
				continue;
			
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