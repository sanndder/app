<?php

namespace models\instellingen;

use models\Connector;
use models\forms\Valid;
use models\forms\Validator;
use models\utils\DBhelper;

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
	 * @var string
	 */
	private $_gb_datum = NULL;

	/*
	 * @var int
	 */
	private $_leefijd = NULL;

	/*
	 * @var float
	 */
	private $_uren_werkweek = NULL;

	/*
	 * @var array
	 */
	private $_maand_loon = array();

	/*
	 * @var array
	 */
	private $_row = NULL;


	/*
	 * @var array
	 */
	public $table = array();
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//databse call
		$this->_get_row();

		//array opbouwen
		$this->_build_table();

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * minimumloon naar database
	 *
	 */
	public function updateMinimumloon() :array
	{
		$validator = new Validator();
		$validator->table( 'settings_minimumloon' )->input( $_POST )->run();
		
		$input = $validator->data();
		
		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//zijn er daadwerkelijk wijzigingen?
			if( inputIsDifferent( $input, $this->getData() ))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE settings_minimumloon SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0";
				$this->db_user->query($sql);
				
				//alleen wanneer de update lukt om dubbele entries te voorkomen
				if ($this->db_user->affected_rows() != -1)
				{
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert('settings_minimumloon', $input);
				}
				else
				{
					$this->_error[] = 'Database error: update mislukt';
				}
				
			}
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
		
		return $input;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * databse row teruggeven
	 *
	 */
	public function getData() :?array
	{
		return $this->_row;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * gb datum instellen
	 *
	 */
	public function setGbDatum( string $gb_datum, ?string $datum = NULL) :object
	{
		//check date
		if( !Valid::date($gb_datum) || ($datum !== NULL && !Valid::date($datum)))
		{
			$this->_error[] = 'Ongeldige datum';
			return $this;
		}
		$this->_gb_datum = $gb_datum;
		$leeftijd = getAge($gb_datum, $datum);
		$this->setLeeftijd($leeftijd);

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * leeftijd instellen
	 *
	 */
	public function setLeeftijd( int $leeftijd ) :object
	{
		//maximaal 21
		if( $leeftijd > 21 )
			$leeftijd = 21;

		$this->_leefijd = $leeftijd;
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * leeftijd instellen
	 *
	 */
	public function setUrenWerkweek( float $uren_werkweek ) :object
	{
		$this->_uren_werkweek = $uren_werkweek;
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * leeftijd instellen
	 *
	 */
	public function getUurloon() :?float
	{
		//check
		if( $this->_leefijd === NULL || $this->_uren_werkweek === NULL )
			return NULL;

		//bij 36, 38 of 40 uren uit de tabel halen
		if( $this->_uren_werkweek == 36 || $this->_uren_werkweek == 38 || $this->_uren_werkweek == 40)
			return $this->table[$this->_uren_werkweek][$this->_leefijd];
		//bij ander aantal uren uitrekenen
		else
		{
			return $this->_calcUurloonFromMaandloon( $this->table['maand'][$this->_leefijd],$this->_uren_werkweek);
			
		}

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * maandloon ophalen en per leeftijd groeperen
	 *
	 */
	private function _get_row()
	{
		$sql = "SELECT * FROM settings_minimumloon WHERE deleted = 0 LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		 return false;

		$this->_row = $query->row_array();

		for ( $i = 21; $i > 14; $i--)
			$this->_maand_loon[$i] = $this->_row[$i.'_jaar'];
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * array met maandloon en uurlonene per leeftijd
	 *
	 */
	private function _build_table()
	{
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
					$this->table[$categorie][$i] = $this->_calcUurloonFromMaandloon( $this->_maand_loon[$i], $categorie);
			}
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uurloon aan de hand van maandloon berekenen
	 *
	 * @return float uurloon
	 */
	private function _calcUurloonFromMaandloon( float $maand_loon, float $uren_werkweek ) :float
	{
		//eerst weekloon
		$week_loon = ($maand_loon * (12/52));

		//uurloon uitrekenen en naar boven afronden
		$uurloon = ceil(($week_loon / $uren_werkweek)*100)/100;

		return $uurloon;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 *
	 * @return array | bool
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