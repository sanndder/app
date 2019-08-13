<?php

namespace models\Instellingen;

use models\Connector;
use models\Forms\Validator;
use models\Utils\Dbhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Minimumloonc class
 *
 *
 *
 */

class Minimumloon extends Connector
{
	/*
	 * @var array
	 */
	private $_maand_loon = array();

	/*
	 * @var array
	 */
	public $table = array();

	/*
	 * @var array
	 */
	private $_error = NULL;



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

		$this->_get_row();

		$this->_build_table();
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * maandloon ophalen en per leeftijd groeperen
	 */
	private function _get_row()
	{
		$sql = "SELECT * FROM settings_minimumloon WHERE deleted = 0 LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		 return false;

		$row = $query->row_array();

		for ( $i = 21; $i > 14; $i--)
			$this->_maand_loon[$i] = $row[$i.'_jaar'];
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * array met maandloon en uurlonene per leeftijd
	 */
	private function _build_table()
	{
		show($this->_maand_loon);

		//eerste laag
		$this->table['maand'] = array();
		$this->table['40'] = array();
		$this->table['38'] = array();
		$this->table['36'] = array();

		foreach ( $this->table as $categorie => $array )
		{
			for ( $i = 21; $i > 14; $i--)
			{
				if( $categorie == 'maand' )
					$this->table[$categorie][$i] = $this->_maand_loon[$i];
				else
					$this->table[$categorie][$i] = $this->_calc_uurloon( $this->_maand_loon[$i], $categorie);
			}
		}

		show($this->table);
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uurloon aan de hand van maandloon berekenen
	 *
	 * @return float uurloon
	 */
	private function _calc_uurloon( float $maand_loon, float $uren_werkweek ) :float
	{

	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
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