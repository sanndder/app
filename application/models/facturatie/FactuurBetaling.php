<?php

namespace models\facturatie;

use models\Connector;
use models\email\Email;
use models\file\Pdf;
use models\forms\Valid;
use models\forms\Validator;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurDefault;
use models\pdf\PdfFactuurUren;
use models\uitzenders\Uitzender;
use models\users\UserGroup;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\PlaatsingGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactuurBetaling extends Connector
{
	protected $_valid = false;
	
	protected $_bedrag = NULL;
	protected $_datum = NULL;
	protected $_categorie_id = NULL;
	protected $_iban = NULL;
	protected $_factor_factuur_regel_id = NULL;
	protected $_transactie_id = NULL;
	
	protected $_categorien = NULL;

	protected $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		$this->categorien();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * klopt alles
	 *
	 */
	public function valid() :bool
	{
		
		if( $this->_bedrag === NULL )
			$this->_error[] = 'Bedrag is niet opgegeven of ongeldig';
		
		if( $this->_categorie_id === NULL )
			$this->_error[] = 'Categroie is niet opgegeven of ongeldig';
		
		if( $this->_datum === NULL )
			$this->_error[] = 'Datum is niet opgegeven of ongeldig';
		
		if( $this->_error === NULL )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get insert data
	 *
	 */
	public function getInsertArray() :?array
	{
		$array['bedrag'] = $this->_bedrag;
		$array['categorie_id'] = $this->_categorie_id;
		$array['betaald_op'] = $this->_datum;
		$array['factor_factuur_regel_id'] = $this->_factor_factuur_regel_id;
		$array['transactie_id'] = $this->_transactie_id;

		return $array;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set factoring factuur is
	 *
	 */
	public function factorFactuurRegel( $regel_id = NULL ) :FactuurBetaling
	{
		if( $regel_id === NULL )
			return $this;
		
		$this->_factor_factuur_regel_id = intval($regel_id);
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set transactie ID
	 *
	 */
	public function tansactieID( $id = NULL ) :FactuurBetaling
	{
		if( $id === NULL )
			return $this;
		
		$this->_transactie_id = intval($id);
		
		return $this;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set categorie
	 *
	 */
	public function categorien() :?array
	{
		if( $this->_categorien === NULL )
		{
			$query = $this->db_user->query( "SELECT * FROM facturen_betalingen_categorien WHERE deleted = 0" );
			$this->_categorien = DBhelper::toList( $query, ['categorie_id'=> 'categorie'], 'NULL' );
		}
		
		return $this->_categorien;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set categorie
	 *
	 */
	public function categorie( $categorie_id = NULL ) :FactuurBetaling
	{
		if( $categorie_id === NULL )
			return $this;
		
		if( !array_key_exists($categorie_id, $this->_categorien))
			return $this;
		
		$this->_categorie_id = intval($categorie_id);
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set bedrag
	 *
	 */
	public function bedrag( $bedrag = NULL ) :FactuurBetaling
	{
		if( $bedrag === NULL )
			return $this;
		
		$bedrag = prepareAmountForDatabase( $bedrag );
		
		if( is_numeric($bedrag))
			$this->_bedrag = $bedrag;
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  set datum
	 *
	 */
	public function datum( $datum = NULL ) :FactuurBetaling
	{
		if( $datum === NULL )
			return $this;
		
		$datum = reverseDate( $datum );
		
		if( Valid::date( $datum ))
			$this->_datum = $datum;
		
		return $this;
	}
	
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
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