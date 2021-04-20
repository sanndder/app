<?php

namespace models\file;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Pdf class
 *
 * File handlig for pdf file types
 *
 */
class Sepa extends File{

	protected $_sepa = NULL; //totaalbedrag
	
	protected $_sepa_totaal = 0; //totaalbedrag
	protected $_sepa_entries = 0; //totaal overboekingen
	protected $_sepa_id = null; //totaal overboekingen
	
	protected $_iban = null; //totaal overboekingen
	protected $_bedrijfsnaam = null; //totaal overboekingen
	
	protected $_sepa_batch = 'true';
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 * @param file array
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct( $input );
		
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		$this->_iban = str_replace( ' ', '', $bedrijfsgegevens['iban'] );
		$this->_bedrijfsnaam = $bedrijfsgegevens['bedrijfsnaam'];
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * enkelvoudig betaalbestand of totaal
	 *
	 */
	public function enkelvoudig() :Sepa
	{
		$this->_sepa_batch = 'false';
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set unique id
	 *
	 */
	public function uniqueID( $prefix = NULL ) :Sepa
	{
		$this->_sepa_id = uniqid();
		
		if( $prefix !== NULL )
			$this->_sepa_id = $prefix . $this->_sepa_id;
		
		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sepa starten
	 *
	 */
	public function addBetaling( $naam, $bedrag, $iban, $omschijving = '' ) :Sepa
	{
		$this->_sepa_entries++;
		$this->_sepa_totaal  = $this->_sepa_totaal + round($bedrag,2);
		
		$this->_sepa->addChild( 'PmtInf', 'CdtTrfTxInf' );
		$this->_sepa->addChild( 'CdtTrfTxInf', 'PmtId' );
		$this->_sepa->addChild( 'PmtId', 'EndToEndId', $this->_sepa_id . '-1-1');
		
		$this->_sepa->addChild( 'CdtTrfTxInf', 'Amt' );
		$this->_sepa->addChild( 'Amt', 'InstdAmt' , round($bedrag,2) );
		$this->_sepa->addAttr( 'InstdAmt', 'Ccy', 'EUR' );
		
		$this->_sepa->addChild( 'CdtTrfTxInf', 'Cdtr' );
		$this->_sepa->addChild( 'Cdtr', 'Nm', $naam );
		
		$this->_sepa->addChild( 'CdtTrfTxInf', 'CdtrAcct' );
		$this->_sepa->addChild( 'CdtrAcct', 'Id' );
		$this->_sepa->addChild( 'Id', 'IBAN',  str_replace( ' ', '', $iban));
		
		$this->_sepa->addChild( 'CdtTrfTxInf', 'RmtInf' );
		$this->_sepa->addChild( 'RmtInf', 'Ustrd', $omschijving );
	
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sepa starten
	 *
	 */
	public function initSepa() :Sepa
	{
		//start xml
		$this->_sepa = new Xml();
		$this->_sepa->new( '<?xml version="1.0" encoding="UTF-8"?><Document xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="urn:iso:std:iso:20022:tech:xsd:pain.001.001.03"></Document>' );
		$this->_sepa->addChild( NULL, 'CstmrCdtTrfInitn' );
		$this->_sepa->addChild( 'CstmrCdtTrfInitn', 'GrpHdr');
		$this->_sepa->addChild( 'GrpHdr', 'MsgId', $this->_sepa_id );
		$this->_sepa->addChild( 'GrpHdr', 'CreDtTm', date('Y-m-d') .'T' . date('H:i:s') );
		
		$this->_sepa->addChild( 'GrpHdr', 'NbOfTxs', 1 );
		$this->_sepa->addChild( 'GrpHdr', 'CtrlSum', 0.01 );
		
		$this->_sepa->addChild( 'GrpHdr', 'InitgPty' );
		$this->_sepa->addChild( 'InitgPty', 'Nm', $this->_bedrijfsnaam );
		
		$this->_sepa->addChild( 'CstmrCdtTrfInitn', 'PmtInf');
		$this->_sepa->addChild( 'PmtInf', 'PmtInfId', $this->_sepa_id . '-1' );
		$this->_sepa->addChild( 'PmtInf', 'PmtMtd', 'TRF' );
		
		$this->_sepa->addChild( 'PmtInf', 'BtchBookg', $this->_sepa_batch );
		
		$this->_sepa->addChild( 'PmtInf', 'PmtTpInf');
		$this->_sepa->addChild( 'PmtTpInf', 'SvcLvl');
		$this->_sepa->addChild( 'SvcLvl', 'Cd', 'SEPA' );
		
		$this->_sepa->addChild( 'PmtInf', 'ReqdExctnDt', date( 'Y-m-d' ) );
		
		$this->_sepa->addChild( 'PmtInf', 'Dbtr' );
		$this->_sepa->addChild( 'Dbtr', 'Nm', $this->_bedrijfsnaam );
		
		$this->_sepa->addChild( 'PmtInf', 'DbtrAcct' );
		$this->_sepa->addChild( 'DbtrAcct', 'Id' );
		$this->_sepa->addChild( 'Id', 'IBAN', $this->_iban );
		
		$this->_sepa->addChild( 'PmtInf', 'DbtrAgt' );
		$this->_sepa->addChild( 'DbtrAgt', 'FinInstnId' );
		$this->_sepa->addChild( 'FinInstnId', 'BIC', 'INGBNL2A' );
		
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sepa afsluiten
	 *
	 */
	public function finalizeSepa() :Sepa
	{
		if( $this->_sepa_batch == 'true' )
		{
			$this->_sepa->addChild( 'PmtInf', 'NbOfTxs', $this->_sepa_entries );
			$this->_sepa->addChild( 'PmtInf', 'CtrlSum', $this->_sepa_totaal );
		}
		
		
		$this->_sepa->print();
		
		return $this;
	}
	

}

?>