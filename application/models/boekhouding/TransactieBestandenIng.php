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

class TransactieBestandenIng extends Connector
{
	private $_grekening = array( 'NL93INGB0990333620' );
	private $_bestand_id = NULL;
	private $_bestand = NULL;
	private $_xml = NULL;
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $bestand_id, $bestand )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		$this->_bestand_id = $bestand_id;
		$this->_bestand = $bestand;
		
		//set xnl
		$this->_setXml();
		
		//set info
		$this->_setBestandInfo();
	
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * transacties uit sml halen
	 *
	 */
	public function loadTransacties()
	{
		foreach( $this->_xml->BkToCstmrStmt->Stmt as $xmlObject )
		{
			//entries doorlopen
			foreach( $xmlObject as $key => $stmt )
			{
				//alleen betalingen
				if( $key != 'Ntry' )continue;
				
				//legen
				unset($entrie);
				
				$entrie['bestand_id'] = $this->_bestand_id;
				$entrie['batch'] = 0;
				
				//datum
				if( isset($stmt->BookgDt))
					$entrie['datum'] = (string)$stmt->BookgDt->Dt;
				
				//transactie ID
				if( isset($stmt->NtryRef))
					$entrie['transactie_ref'] = (string)$stmt->NtryRef;
				
				//bedrag
				if( isset($stmt->Amt))
				{
					$entrie['bedrag'] = (string)$stmt->Amt;
					$entrie['bedrag_onverwerkt'] = $entrie['bedrag'];
					$dbct = (string)$stmt->CdtDbtInd;
					if( $dbct == 'DBIT' ) $entrie['bedrag'] = $entrie['bedrag'] *-1;
					
				}
				
				//transactie ID
				if( isset($stmt->NtryRef))
					$entrie['transactie_ref'] = (string)$stmt->NtryRef;
				
				//relatie Credit
				if( isset($stmt->NtryDtls->TxDtls->RltdPties->Cdtr->Nm))
					$entrie['relatie'] = (string)$stmt->NtryDtls->TxDtls->RltdPties->Cdtr->Nm;
				
				//relatie Credit IBAN
				if( isset($stmt->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->IBAN))
					$entrie['relatie_iban'] = (string)$stmt->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->IBAN;
				
				//relatie Debet
				if( isset($stmt->NtryDtls->TxDtls->RltdPties->Dbtr->Nm))
					$entrie['relatie'] = (string)$stmt->NtryDtls->TxDtls->RltdPties->Dbtr->Nm;
				
				//relatie Debet IBAN
				if( isset($stmt->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->IBAN))
					$entrie['relatie_iban'] = (string)$stmt->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->IBAN;
				
				//batch?
				if( isset($stmt->NtryDtls->Btch) )
				{
					$entrie['batch'] = 1;
					
					if( isset($stmt->NtryDtls->Btch->PmtInfId))
						$entrie['omschrijving'] = (string)$stmt->NtryDtls->Btch->PmtInfId;
				}
				
				//omschrijving
				if( isset($stmt->NtryDtls->TxDtls->RmtInf->Ustrd))
					$entrie['omschrijving'] = (string)$stmt->NtryDtls->TxDtls->RmtInf->Ustrd;
				
				//geen dubbele entries
				$query = $this->db_user->query( "SELECT transactie_id FROM bank_transacties WHERE transactie_ref = '".$entrie['transactie_ref']."' AND deleted = 0 LIMIT 1" );
				if( $query->num_rows() == 0 )
				{
					$this->db_user->insert( 'bank_transacties', $entrie );
				}
			}
		}
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * info opslaan indien nodig
	 *
	 */
	private function _setBestandInfo()
	{
		if( $this->_bestand['datum_van'] == NULL )
			$update['datum_van'] = $this->_getDatumVanFromXml();
		
		if( $this->_bestand['datum_tot'] == NULL )
			$update['datum_tot'] = $this->_getDatumTotFromXml();
		
		if( $this->_bestand['grekening'] == NULL )
			$update['grekening'] = $this->_isGrekening();
		
		if( isset($update) && count($update) > 0)
		{
			$this->db_user->where( 'bestand_id', $this->_bestand_id );
			$this->db_user->update( 'bank_transactiebestanden', $update );
		}

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * datum tot uit xml halen
	 *
	 */
	private function _isGrekening()
	{
		if( $this->_bestand['iban'] === NULL )
			return NULL;
			
		if( in_array( $this->_bestand['iban'], $this->_grekening) )
			return 1;
		
		return 0;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * datum tot uit xml halen
	 *
	 */
	private function _getDatumTotFromXml()
	{
		if( isset( $this->_xml->BkToCstmrStmt->Stmt->FrToDt->ToDtTm ) )
		{
			$date = (string)$this->_xml->BkToCstmrStmt->Stmt->FrToDt->ToDtTm;
			return substr( $date, 0, 10);
		}
		
		return NULL;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * datum van uit xml halen
	 *
	 */
	private function _getDatumVanFromXml()
	{
		if( isset( $this->_xml->BkToCstmrStmt->Stmt->FrToDt->FrDtTm ) )
		{
			$date = (string)$this->_xml->BkToCstmrStmt->Stmt->FrToDt->FrDtTm;
			return substr( $date, 0, 10);
		}
		
		return NULL;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * xml laden
	 *
	 */
	private function _setXml()
	{
		
		$file = new File( $this->_bestand );
		if( !file_exists( $file->path() ) )
		{
			$this->_error[] = 'bestand niet gevonden op de server';
			return;
		}
		
		//xml laden
		$this->_xml = simplexml_load_file( $file->path() );
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