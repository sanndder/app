<?php

namespace models\boekhouding;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Transactie extends Connector
{
	private $_transactie_id = NULL;
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $id = NULL  )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $id !== NULL && intval($id) != 0 )
			$this->setId( $id );
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Details
	 *
	 */
	public function details()
	{
		$query = $this->db_user->query("SELECT * FROM bank_transacties WHERE transactie_id = $this->_transactie_id AND deleted = 0 LIMIT 1");
		
		$data = DBhelper::toRow( $query, 'NULL' );
		
		if( $data === NULL ) return $data;
		
		$data['datum_format'] = reverseDate( $data['datum'] );
		
		return $data;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Opmerking opslaan
	 *
	 */
	public function setVerwerkt( $val )
	{
		if( $this->_transactie_id === NULL )
		{
			$this->_error[] = 'Ongeldig ID';
			return false;
		}
		
		$update['verwerkt'] = $val;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Opmerking opslaan
	 *
	 */
	public function setOpmerking( $opmerking )
	{
		
		if( $this->_transactie_id === NULL )
		{
			$this->_error[] = 'Ongeldig ID';
			return false;
		}
		
		$update['opmerking'] = $opmerking;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Set ID
	 *
	 */
	public function setId( $id )
	{
		return $this->_transactie_id = intval($id);
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