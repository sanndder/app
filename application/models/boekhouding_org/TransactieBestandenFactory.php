<?php

namespace models\boekhouding;

use models\Connector;
use models\file\File;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class TransactieBestandenFactory extends Connector
{
	private $_bestand_id = NULL;
	private $_bestand = NULL;
	private $_xml = NULL;
	private $_iban = NULL;
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $bestand_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		$this->setId( $bestand_id );
		
		//bestand ophalen
		$this->_bestand = $this->getFile();
		
		//iban ophalen, uit bestand of database
		$this->getIban( $this->_bestand );
		$this->_bestand['iban'] = $this->_iban;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bank type terug
	 *
	 */
	public function getBestandByBankType() :object
	{
		//New object
		if( $this->bank() == 'INGB' )
			return new TransactieBestandenIng( $this->_bestand_id, $this->_bestand );
		
		return $this;
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bank type
	 *
	 */
	public function bank() :?string
	{
		if( $this->_iban === NULL )
			return NULL;
		
		return substr( $this->_iban, 4,4 );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get Iban uit databse of bestand
	 *
	 */
	public function getIban( $bestand ) :void
	{
		//moet het bestand nog uitgelezen worden
		if( $bestand['iban'] === NULL )
		{
			$file = new File( $bestand );
			
			if( !file_exists( $file->path() ) )
			{
				$this->_error[] = 'bestand niet gevonden op de server';
				return;
			}
			
			//xml laden
			$this->_xml = simplexml_load_file( $file->path() );
			
			//iban er uit
			$this->setIbanFromXml();
		}
		else
		{
			$this->_iban = $bestand['iban'];
		}
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * iban uit bestand halen
	 *
	 */
	public function setIbanFromXml() :void
	{
		if( isset( $this->_xml->BkToCstmrStmt->Stmt->Acct->Id->IBAN ) )
			$this->_iban = (string)$this->_xml->BkToCstmrStmt->Stmt->Acct->Id->IBAN;
		
		$this->_iban = strtoupper($this->_iban);
		
		if( $this->_iban !== NULL )
		{
			
			$update['iban'] = $this->_iban;
			$this->db_user->where( 'bestand_id', $this->_bestand_id );
			$this->db_user->update( 'bank_transactiebestanden', $update );
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bestand laden
	 *
	 */
	public function setId( $bestand_id ) :void
	{
		$this->_bestand_id = intval($bestand_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bestand laden
	 *
	 */
	public function getFile() :?array
	{
		return  $this->select_row( 'bank_transactiebestanden', array('bestand_id' => $this->_bestand_id) );
	}
}


?>