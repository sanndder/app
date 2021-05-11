<?php

namespace models\utils;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Tijdvak class
 *
 * Tijdvak regelt alles wat te maken heeft met periode jaren en data (datums)
 *
 */

class Tijdvak
{
	
	/*
	 * @var string
	 */
	private $_tijdvak = NULL;
	
	/*
	 * @var int
	 */
	private $_jaar = NULL;
	
	/*
	 * @var int
	 */
	private $_periode = NULL;
	
	/*
	 * Heeft dit jaar 52 of 53 weken
	 * @var string
	 */
	private $_weken_in_jaar = 52;
	
	/*
	 * maximale periode
	 * @var string
	 */
	private $_max_periode = NULL;
	
	/*
	 * Mooie naam voor menselijke gebruikers
	 * @var string
	 */
	private $_naam = NULL;
	
	/*
	 * @var array
	 */
	private $_dagen_in_periode = array();
	
	/*
	 * datum eerste dag
	 * @var date
	 */
	private $_periode_start = NULL;
	
	/*
	 * datum laatste dag
	 * @var date
	 */
	private $_periode_einde = NULL;
	
	/*
	 * voor hergebruik
	 * @var date
	 */
		private $_weken_array = NULL;
	
	/*
	 * @var array
	 */
	private $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor set gelijk ook het tijdvak, het jaar en de periode. Alleen tijdvak is genoeg om huidige periode in te stellen
	 *
	 *
	 * @param tijdvak
	 * @param jaar
	 * @param periode
	 * @return $this
	 */
	public function __construct( $tijdvak, $jaar = '', $periode = '' )
	{
		//tijdvak instellen
		if( $tijdvak == 'w' || $tijdvak == 'm' || $tijdvak == '4w' )
			$this->_tijdvak = $tijdvak;
		else
			$this->_error[] = 'Ongeldig tijdvak: ' . $tijdvak;
		
		//jaar instellen
		$this->_setJaar( $jaar );
		
		//laatste week instellen
		$this->_setLaatsteWeek();
		
		//periode in stellen
		$this->_setPeriode( $periode );
		
		//array van dagen
		$this->_buildDagenArray();
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * is een datum een zaterdag of zondag
	 * @return bool
	 */
	static function isWeekend( $datum )
	{
		return (date('N', strtotime($datum)) >= 6);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * afkortingen
	 * @return bool
	 */
	static function dagAfkorting( $datum )
	{
		switch(date('N', strtotime($datum)))
		{
			case 1:	return 'ma';
			case 2:	return 'di';
			case 3:	return 'wo';
			case 4:	return 'do';
			case 5:	return 'vr';
			case 6:	return 'za';
			case 7:	return 'zo';
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * maand
	 * @return bool
	 */
	static function maandNaam( $maand_nr )
	{
		switch($maand_nr)
		{
			case 1:	return 'januari';
			case 2:	return 'februari';
			case 3:	return 'maart';
			case 4:	return 'april';
			case 5:	return 'mei';
			case 6:	return 'juni';
			case 7:	return 'juli';
			case 8:	return 'augustus';
			case 9:	return 'september';
			case 10: return 'oktober';
			case 11: return 'november';
			case 12: return 'december';
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * weken array aanmaken
	 *
	 */
	public function wekenArray( $jaar, $return_value = array() )
	{
		$this->_setJaar($jaar);
		$this->_setLaatsteWeek();
		
		for( $i=1;$i<=$this->_weken_in_jaar;$i++)
			$this->_weken_array[$i] = $return_value;
		
		return $this->_weken_array;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * afkortingen
	 * @return bool
	 */
	static function weeknr( $datum )
	{
		return date("W", strtotime($datum));
	}
	

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * dagen in periode naar array
	 * @return $this
	 */
	private function _buildDagenArray()
	{
		$this->_setStartDatum();
		$this->_setEindDatum();
		
		//array opbouwen
		$interval = new \DateInterval('P1D');
		
		//omzet naar object
		$start = new \DateTime( $this->_periode_start );
		$einde = new \DateTime( $this->_periode_einde );
		$daterange = new \DatePeriod( $start, $interval, $einde );
		
		$i = 1;
		foreach($daterange as $date){
			$this->_dagen_in_periode[$i] = $date->format('Y-m-d');
			$i++;
		}
		
		$this->_dagen_in_periode[$i] =  $this->_periode_einde;
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * dagen in periode
	 * @return array
	 */
	public function dagen()
	{
		return $this->_dagen_in_periode;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * startdatum periode
	 * @return $this
	 */
	private function _setStartDatum()
	{
		if( $this->_tijdvak == 'w' )
			$this->_periode_start = date( 'Y-m-d', strtotime( $this->_jaar . 'W' . str_pad( $this->_periode, 2, '0', STR_PAD_LEFT ) ) );
		
		if( $this->_tijdvak == '4w' )
			$this->_periode_start = date( 'Y-m-d', strtotime( $this->_jaar . 'W' . str_pad( ($this->_periode*4)-3, 2, '0', STR_PAD_LEFT ) ) );
		
		if( $this->_tijdvak == 'm' )
			$this->_periode_start =  $this->_jaar . '-' . str_pad( $this->_periode, 2, '0', STR_PAD_LEFT) . '-01';
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * startdatum periode opvragen
	 * @return string
	 */
	public function startDatum()
	{
		return $this->_periode_start;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * einddatum periode
	 * @return $this
	 */
	private function _setEindDatum()
	{
		if( $this->_tijdvak == 'w' )
		{
			$date = new \DateTime( $this->_periode_start );
			$this->_periode_einde = $date->modify( '+6 days' )->format('Y-m-d');
		}
		
		if( $this->_tijdvak == '4w' )
		{
			//einde hangt af van week 53
			if( $this->_periode == 13 && $this->_weken_in_jaar == 53 )
				$add = 34;
			else
				$add = 27;
			
			$date = new \DateTime( $this->_periode_start );
			$this->_periode_einde = $date->modify( "+$add days" )->format('Y-m-d');
		}
		
		
		//https://stackoverflow.com/questions/1686724/how-to-find-the-last-day-of-the-month-from-date
		if( $this->_tijdvak == 'm' )
		{
			$date = new \DateTime( $this->_periode_start );
			$date->modify('last day of this month');
			$this->_periode_einde = $date->format('Y-m-d');
		}

		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * eindatum periode opvragen
	 * @return string
	 */
	public function eindDatum()
	{
		return $this->_periode_einde;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * naar volgende periode
	 * @return $this
	 */
	public function next()
	{
		// periode +1
		$this->_periode++;
		
		//eerst volgende periode bepalen
		if( $this->_periode > $this->_max_periode )
		{
			$this->_periode = 1;
			$this->_jaar++;
			
			//update laatste week en max periode bij week
			if( $this->_tijdvak == 'w' )
			{
				$this->_setLaatsteWeek();
				$this->_max_periode = $this->_weken_in_jaar;
			}
		}
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * naar vorige periode
	 * @return $this
	 */
	public function prev()
	{
		// periode +1
		$this->_periode--;
		
		//eerst volgende periode bepalen
		if( $this->_periode < 1 )
		{
			//jaartje minder
			$this->_jaar--;
			
			//update laatste week en max periode bij week, VOOR periode bepaling
			if( $this->_tijdvak == 'w' )
			{
				$this->_setLaatsteWeek();
				$this->_max_periode = $this->_weken_in_jaar;
			}
			
			$this->_periode = $this->_max_periode;
		}
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * @return int
	 */
	public function getJaar()
	{
		return $this->_jaar;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * @return int
	 */
	public function getPeriode()
	{
		return $this->_periode;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Jaar instellen, huidige jaar als default
	 * @return void
	 */
	private function _setJaar( $jaar )
	{
		$this->_jaar = intval( $jaar );
		if( $this->_jaar == 0 )
			$this->_jaar = date( 'Y' ); // default
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Periode instellen, huidige periode als default
	 * @return void
	 */
	private function _setPeriode( $periode )
	{
		$periode = intval( $periode );
		
		// week check
		if( $this->_tijdvak == 'w' )
		{
			//max
			$this->_max_periode = $this->_weken_in_jaar;
			
			//default
			if( $periode == 0 )
				$this->_periode = date( 'W' );
			
			//custom
			if( $periode > 0 && $periode )
				$this->_periode = $periode;
			
		}
		
		// 4 weken check
		if( $this->_tijdvak == '4w' )
		{
			//max
			$this->_max_periode = 13;
			
			//custom
			if( $periode > 0 )
				$this->_periode = $periode;
			
			//default
			if( $periode == 0 )
				$this->_periode = ceil( date( 'W' ) / 4 );
		}
		
		// maand check
		if( $this->_tijdvak == 'm' )
		{
			//max
			$this->_max_periode = 12;
			
			//custom
			if( $periode > 0 )
				$this->_periode = $periode;
			
			//default
			if( $periode == 0 )
				$this->_periode = date( 'n' );
		}
		
		//max check
		if( $this->_periode > $this->_max_periode )
			$this->_periode = $this->_max_periode;
		
		//voorloop nullen weghalen
		$this->_periode = intval( $this->_periode );
		
		//niet gelukt
		if( $this->_periode === NULL || $this->_periode == 0 )
			$this->_error[] = 'Ongeldige periode: ' . $this->_periode;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Periode instellen, huidige periode als default
	 * @return void
	 */
	private function _setLaatsteWeek()
	{
		$date = new \DateTime();
		$date->setISODate( $this->_jaar, 53 );
		$this->_weken_in_jaar = ( $date->format( "W" ) === "53" ? 53 : 52 );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 * @return array | boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset( $_GET['debug'] ) )
		{
			if( $this->_error === NULL )
				show( 'Geen errors' );
			else
				show( $this->_error );
		}
		
		if( $this->_error === NULL )
			return false;
		
		return $this->_error;
	}
}

?>