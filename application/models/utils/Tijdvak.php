<?php

namespace models\Utils;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Tijdvak class
 *
 * Tijdvak regelt alles wat te maken heeft met periode jaren en data (datums)
 *
 */
class Tijdvak{

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
	 * @var array
	 */
	private $_error = NULL;


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
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
		if( $tijdvak == 'w' || $tijdvak == 'm' || $tijdvak == '4w')
			$this->_tijdvak = $tijdvak;
		else
			$this->_error[] = 'Ongeldig tijdvak: ' . $tijdvak;

		//jaar instellen
		$this->_setJaar( $jaar );

		//laatste week instellen
		$this->_setLaatsteWeek();

		//periode in stellen
		$this->_setPeriode( $periode );

		return $this;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
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

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
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


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * @return int
	 */
	public function getJaar()
	{
		return $this->_jaar;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * @return int
	 */
	public function getPeriode()
	{
		return $this->_periode;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Jaar instellen, huidige jaar als default
	 * @return void
	 */
	private function _setJaar( $jaar )
	{
		$this->_jaar = intval($jaar);
		if( $this->_jaar == 0 )
			$this->_jaar = date('Y'); // default
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Periode instellen, huidige periode als default
	 * @return void
	 */
	private function _setPeriode( $periode )
	{
		$periode = intval($periode);

		// week check
		if( $this->_tijdvak == 'w' )
		{
			//max
			$this->_max_periode = $this->_weken_in_jaar;

			//default
			if( $periode == 0 )
				$this->_periode = date('W');

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
				$this->_periode = ceil(date('W')/4);
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
				$this->_periode = date('n');
		}

		//max check
		if( $this->_periode > $this->_max_periode )
			$this->_periode = $this->_max_periode;

		//voorloop nullen weghalen
		$this->_periode = intval($this->_periode);

		//niet gelukt
		if( $this->_periode === NULL || $this->_periode == 0)
			$this->_error[] = 'Ongeldige periode: ' . $this->_periode;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Periode instellen, huidige periode als default
	 * @return void
	 */
	private function _setLaatsteWeek()
	{
		$date = new \DateTime();
		$date->setISODate($this->_jaar, 53);
		$this->_weken_in_jaar = ($date->format("W") === "53" ? 53 : 52);
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}
}


?>