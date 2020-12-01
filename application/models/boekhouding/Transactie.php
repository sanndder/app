<?php

namespace models\boekhouding;

use models\Connector;
use models\facturatie\Factuur;
use models\facturatie\FactuurBetaling;
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
	private $_transactie = NULL;
	private $_categorie_id = NULL;
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
		$sql = "SELECT bank_transacties.*, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender
				FROM bank_transacties
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = bank_transacties.inlener_id
				LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = bank_transacties.uitzender_id
				WHERE bank_transacties.transactie_id = $this->_transactie_id AND bank_transacties.deleted = 0 
					AND ( inleners_bedrijfsgegevens.deleted = 0 OR bank_transacties.inlener_id IS NULL )
					AND ( uitzenders_bedrijfsgegevens.deleted = 0 OR bank_transacties.uitzender_id IS NULL )
				LIMIT 1";

		$query = $this->db_user->query( $sql );
		$this->_transactie = DBhelper::toRow( $query, 'NULL' );
		
		//afbreken wanneer niet gevonden
		if( $this->_transactie === NULL ) return $this->_transactie;
		
		//datum al aaanpassen voor javascript
		$this->_transactie['datum_format'] = reverseDate( $this->_transactie['datum'] );
		$this->_categorie_id = $this->_transactie['categorie_id'];
		$this->_transactie['factuur_nrs'] = '';
		
		//factuurnummers uit de omschrijving vissen
		if( $this->_transactie['omschrijving'] !== NULL && $this->_transactie['omschrijving'] != '' )
		{
			preg_match_all('!\d+!', $this->_transactie['omschrijving'], $matches);
			$this->_transactie['factuur_nrs'] = implode(',', $matches[0]);
			/*
			$parts = explode( ' ', $this->_transactie['omschrijving'] );
			if( is_array($parts) && count($parts) > 0 )
			{
				foreach( $parts as $word )
				{
					if( is_numeric($word) )
						$nr[] = $word;
				}
				
				$this->_transactie['factuur_nrs'] = implode(',', $nr);
			}*/
		}
		
		//kan er al gekoppeld worden?
		$this->_searchKoppeling();
		
		return $this->_transactie;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * probeer transactie te koppelen
	 *
	 */
	private function _searchKoppeling()
	{
		//koppeling maar 1x proberen
		if( $this->_transactie['auto_koppeling'] !== NULL )
			return false;
		
		$query = $this->db_user->query( "SELECT relatie_iban, categorie_id FROM bank_transacties_koppeling WHERE deleted = 0 AND relatie_iban = ? ", array( $this->_transactie['relatie_iban']) );
		
		//niks gevonden
		if( $query->num_rows() == 0 ) return false;
		
		$categorien = [];
		
		foreach( $query->result_array() as $row )
			$categorien[$row['categorie_id']] = 1;

		//wannneer er maar 1 categorie is, die instellen
		if( count($categorien) != 1 ) return false;
		
		$update['categorie_id'] = key($categorien);
		$update['auto_koppeling'] = 1;
		
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			$this->_categorie_id = $this->_transactie['categorie_id'] = $update['categorie_id'];
			
			//wanneer we de categorie weten verder kijken
			$this->koppelTransactie();
			
			return true;
		}
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen aan transactie koppelen
	 *
	 */
	public function koppelFacturen( $facturen )
	{
		//details laden
		$this->details();
		
		$response = [];
		foreach( $facturen as $factuur_id => $bedrag )
		{
			$factuur = new Factuur($factuur_id);
			
			$betaling = new FactuurBetaling();
			$betaling->bedrag( $bedrag )->categorie( $this->_getBetalingCategorie( $this->_transactie['categorie_id']) )->datum( reverseDate($this->_transactie['datum']) );
			if( $betaling->valid() )
			{
				$factuur->addBetaling( $betaling );
				$factuur->delBetaling( $factuur->getBetalingID() );
				$this->_log('Factuur ' .  $factuur_id . ' gekoppeld');
				$response[$factuur_id]['status'] = 'success';
			}
			else
			{
				$response[$factuur_id]['status'] = 'error';
				$response[$factuur_id]['factuur_nr'] = $factuur->nr();
				$response[$factuur_id]['errors'] = $betaling->errors();
			}
		}
		
		return $response;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * probeer transactie te koppelen
	 *
	 */
	private function _getBetalingCategorie( $transactieCategorie )
	{
		//gewone betaling
		if( $transactieCategorie == 1)
			return 1;
		
		//marge betaling
		if( $transactieCategorie == 4)
			return 9;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * probeer transactie te koppelen
	 *
	 */
	public function koppelTransactie()
	{
		//details laden
		if( $this->_transactie === NULL )
			$this->details();
		
		//factoring
		if( $this->_categorie_id == 2 )
			return $this->_koppelFactoringFactuur();
		
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * haal factoring factuur op
	 *
	 */
	public function getFactoringFactuur()
	{
		$query = $this->db_user->query( "SELECT factuur_id, file_name_display, factuur_totaal FROM factoring_facturen WHERE transactie_id = $this->_transactie_id AND deleted = 0 LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL');
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * probeer factoringfatcuur te koppelen
	 *
	 */
	private function _koppelFactoringFactuur()
	{
		//nr uit omschrijving halen
		$nr = explode( ' ', $this->_transactie['omschrijving'] );
		
		if( !is_array($nr) || count($nr) == 0 )
			return false;
		
		//zoek factoring factuur
		$sql = "SELECT factuur_id FROM factoring_facturen WHERE deleted = 0 AND ( ";
		
		foreach( $nr as $part )
			$sql .= " factuur_nr LIKE '%".$part."%' OR ";
		
		$sql = substr( $sql, 0, -3);
		$sql .= " ) ";
		
		$query = $this->db_user->query( $sql );
		
		//factuur gevonden
		if( $query->num_rows() == 1 )
		{
			$factoringfactuur = $query->row_array();
			
			$update['transactie_id'] = $this->_transactie_id;
			$this->db_user->where( 'factuur_id', $factoringfactuur['factuur_id'] );
			$this->db_user->where( 'transactie_id', NULL );
			$this->db_user->update( 'factoring_facturen', $update );
			
			$this->_saveKoppeling();
			
			return true;
		}

		return false;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Koppeling opslaan voor later
	 *
	 */
	private function _saveKoppeling( $info = NULL )
	{
		if( $this->_transactie === NULL ) $this->details();

		$query = $this->db_user->query( "SELECT * FROM bank_transacties_koppeling WHERE deleted = 0 AND relatie_iban = ? AND categorie_id = ?", array( $this->_transactie['relatie_iban'], $this->_categorie_id ) );

		//koppeling is al gemaakt
		if( $query->num_rows() > 0 )
			return NULL;
		
		$insert['relatie'] = $this->_transactie['relatie'];
		$insert['relatie_iban'] = $this->_transactie['relatie_iban'];
		$insert['categorie_id'] = $this->_categorie_id;

		if( is_array($info) && count($info) > 0 )
		{
			foreach ( $info as $key => $value )
				$insert[$key] = $value;
		}
		
		$this->db_user->insert( 'bank_transacties_koppeling', $insert );
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
	 * relatie opslaan
	 *
	 */
	public function setRelatie( $type, $id ) :bool
	{
		if( $type != 'inlener' && $type != 'uitzender' )
			return false;

		$update[$type . '_id'] = intval($id);
		$this->db_user->where( 'transactie_id', $this->_transactie_id);
		$this->db_user->update('bank_transacties', $update);

		//koppeling maken
		$this->_saveKoppeling( $update );

		return true;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * categorie opslaan
	 *
	 */
	public function setCategorie( $categorie_id ) :bool
	{
		if( $this->_transactie_id === NULL )
		{
			$this->_error[] = 'Ongeldig ID';
			return false;
		}
		
		$update['categorie_id'] = $this->_categorie_id = intval($categorie_id);
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			//koppeling maken
			$this->koppelTransactie();
			
			return true;
		}
		
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
	 *
	 * log  actie
	 *
	 */
	private function _log( $action = NULL, $info = NULL ): void
	{
		if( $action === NULL )
			return;
		
		$insert['transactie_id'] = $this->_transactie_id;
		$insert['action'] = $action;
		//$insert['info'] = $info;
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'bank_transacties_log', $insert );
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