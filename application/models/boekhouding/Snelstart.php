<?php

namespace models\boekhouding;

use models\Connector;
use models\file\Excel;
use models\inleners\InlenerGroup;
use models\uitzenders\UitzenderGroup;
use models\utils\DBhelper;
use models\zzp\ZzpGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Snelstart extends Connector
{
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * grootboekrekeningen opslaan
	 *
	 */
	public function setSettings( $data ) :bool
	{
		unset($data['set_settings']);
		
		//alle ID mee
		//$insert['user_id'] = $this->user->user_id;
		
		foreach( $data as $name => $rekening )
		{
			if( !is_numeric($rekening) || $rekening > 9999 )
			{
				$this->_error[] = 'Uw invoer bevat ongeldige waardes';
				return false;
			}
			
			$insert['name'] = $name;
			$insert['rekening'] = intval($rekening);
			
			$insert_batch[] = $insert;
		}

		//alles legen
		$this->delete_all( 'settings_grootboek' );
		
		//nieuwe er in
		$this->db_user->insert_batch( 'settings_grootboek', $insert_batch );
		
		if( $this->db_user->insert_id() > 0 )
			return true;
		
		$this->_error[] = 'Er ging wat mis bij het opslaan, uw wijzigingen zijn niet doorgevoerd';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * grootboekrekeningen ophalen
	 *
	 */
	public function settings() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM settings_grootboek WHERE deleted = 0" );
		return DBhelper::toArray( $query, 'name', 'NULL' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * download crediteuren of debiteuren
	 *
	 */
	public function downloadRelaties( $type )
	{
		if( $type == 'debiteuren' )
		{
			$relaties = $this->_listDebiteuren();
			$header = ['Debiteurnummer','Bedrijfsnaam', 'fldKrediettermijn'];
			$name = 'debiteuren_' . date('Y_m_d_His');
		}
		
		if( $type == 'crediteuren' )
		{
			$relaties = $this->_listCrediteuren();
			$header = ['FldRelatiecode','FldNaam'];
			$name = 'crediteuren_' . date('Y_m_d_His');
		}

		
		$excel = new Excel();
		$excel->writeRow( $header );
		
		if( $relaties !== NULL)
		{
			foreach( $relaties as $id => $r )
			{
				if( $type == 'debiteuren' )
					$excel->writeRow( array( $id, $r['bedrijfsnaam'], $r['termijn'] ) );
				if( $type == 'crediteuren' )
					$excel->writeRow( array( $id, $r['bedrijfsnaam'] ) );
			}
		}
		
		$excel->setAutoWidth();
		$excel->inline( $name );
		
		//show($relaties);
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * lijst met debiteuren
	 *
	 */
	private function _listDebiteuren()
	{
		$inlenerGroup = new InlenerGroup();
		return $inlenerGroup->snelstartExport();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * lijst met crediteuren
	 *
	 */
	private function _listCrediteuren()
	{
		$uitzenderGroup = new UitzenderGroup();
		$uitzenders = $uitzenderGroup->snelstartExport();
		
		foreach( $uitzenders as &$arr )
			$arr['termijn'] = 21;
		
		return $uitzenders;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * export naar database
	 *
	 */
	public function exportBoekingenZzp( $tm )
	{
		$dagboeken = $this->settings();
		
		//zzp facturen
		$sql = "SELECT zzp_facturen.verval_datum, factuur_id, factuur_nr, zzp_id, bedrag_excl, bedrag_incl, bedrag_btw
				FROM zzp_facturen
				WHERE zzp_facturen.deleted = 0 AND bedrag_incl != 0 AND timestamp >= '2021-01-01 00:00:00' AND timestamp <= '2021-04-01 00:00:01'";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return false;
		
		$zzpers = ZzpGroup::list();
		
		foreach( $query->result_array() as $row )
		{
			if( $row['zzp_id'] !== NULL && $row['zzp_id'] != 0)
				$row['zzp'] = $zzpers[$row['zzp_id']];
			
			$data[] = $row;
		}
		
		
		$excel = new Excel();
		$excel->writeRow( ['fldDagboek','fldBoekingcode','fldDatum','fldGrootboeknummer','fldDebet','fldCredit','fldOmschrijving','fldRelatiecode','fldFactuurnummer'] );
		
		$i = 1;
		
		if( $data !== NULL)
		{
			foreach( $data as $factuur )
			{
				unset($rij_0);
				unset($rij_1);
				unset($rij_2);
				
				$creditdebet = 'debet';
				
				if( $factuur['bedrag_excl'] < 0)
					$creditdebet = 'credit';
				
				$factuur['bedrag_excl'] = abs($factuur['bedrag_excl']);
				$factuur['bedrag_btw'] = abs($factuur['bedrag_btw']);
				$factuur['bedrag_incl'] = abs($factuur['bedrag_incl']);
				
				//zzp verkoop
				if( $creditdebet == 'debet' )
				{
					$rij_0 = array( 1500, $i, $factuur['verval_datum'], 1500, 0, $factuur['bedrag_incl'], $factuur['zzp'], $factuur['zzp_id'], $factuur['factuur_nr'] );
					
					//met BTW
					if( $factuur['bedrag_btw'] != 0 )
					{
						$rij_1 = array( 1500, $i, $factuur['verval_datum'], 7020, $factuur['bedrag_excl'],0, $factuur['zzp'], $factuur['zzp_id'], $factuur['factuur_nr'] );
						$rij_2 = array( 1500, $i, $factuur['verval_datum'], 1612, $factuur['bedrag_btw'], 0, $factuur['zzp'], $factuur['zzp_id'], $factuur['factuur_nr'] );
					} //btw verlegd
					else
					{
						$rij_1 = array( 1500, $i, $factuur['verval_datum'], 7010, $factuur['bedrag_excl'], 0,  $factuur['zzp'], $factuur['zzp_id'], $factuur['factuur_nr'] );
					}
				}
				
				if(isset($rij_0))
				{
					$excel->writeRow( $rij_0 );
					$excel->writeRow( $rij_1 );
					if( isset( $rij_2 ) )
						$excel->writeRow( $rij_2 );
					
					$i++;
				}
			}
		}
		
		$excel->setAutoWidth();
		$excel->inline( 'export_boekingen_zzp_' . date('Y_m_d_His') );
		
		die();
		//return $this->db_user->insert_id();
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * export naar database
	 *
	 */
	public function exportBoekingen( $tm )
	{
		$dagboeken = $this->settings();
		
		$max_id = $this->_laatstVerwerktefactuur();
		
		$sql = "SELECT facturen.factuur_datum, factuur_id, factuur_nr, inlener_id, uitzender_id, bedrag_excl, bedrag_incl, bedrag_btw, marge
				FROM facturen
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND bedrag_incl != 0 AND factuur_nr > 3467";
		
		//show($sql);
		//bemiddeling
		/*
		$sql = "SELECT facturen.factuur_datum, factuur_id, factuur_nr, inlener_id, uitzender_id, bedrag_excl, bedrag_incl, bedrag_btw, marge
				FROM facturen
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND bedrag_incl != 0 AND factuur_nr > 24 AND factuur_datum < '2021-01-01' ";*/
				
		if( $max_id !== NULL )
			$sql .= " AND factuur_id > $max_id";

		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return false;
		
		$inleners = InlenerGroup::list();
		$uitzenders = UitzenderGroup::list();
		
		foreach( $query->result_array() as $row )
		{
			if( $row['inlener_id'] !== NULL && $row['inlener_id'] != 0)
				$row['inlener'] = $inleners[$row['inlener_id']];
			if( $row['uitzender_id'] !== NULL && $row['uitzender_id'] != 0)
				$row['uitzender'] = $uitzenders[$row['uitzender_id']];
			
			$data[] = $row;
		}
		

		$excel = new Excel();
		$excel->writeRow( ['fldDagboek','fldBoekingcode','fldDatum','fldGrootboeknummer','fldDebet','fldCredit','fldOmschrijving','fldRelatiecode','fldFactuurnummer'] );
		
		$i = 1;
		
		if( $data !== NULL)
		{
			foreach( $data as $factuur )
			{
				unset($rij_0);
				unset($rij_1);
				unset($rij_2);
				
				$creditdebet = 'debet';
				
				if( $factuur['bedrag_excl'] < 0 && $factuur['marge'] == 0 )
					$creditdebet = 'credit';
				
				if( $factuur['bedrag_excl'] > 0 && $factuur['marge'] == 1 )
					$creditdebet = 'credit';
					
				$factuur['bedrag_excl'] = abs($factuur['bedrag_excl']);
				$factuur['bedrag_btw'] = abs($factuur['bedrag_btw']);
				$factuur['bedrag_incl'] = abs($factuur['bedrag_incl']);
				
				//verkoop
				if( $factuur['marge'] == 0 )
				{
					if( $creditdebet == 'debet')
					{
						$rij_0 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['verkoop']['rekening'], $factuur['bedrag_incl'], 0, $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
						
						//met BTW
						if( $factuur['bedrag_btw'] != 0 )
						{
							$rij_1 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['omzet_btw_hoog']['rekening'], 0, $factuur['bedrag_excl'], $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
							$rij_2 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['btw_afdragen_hoog']['rekening'], 0, $factuur['bedrag_btw'], $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
						} //btw verlegd
						else
						{
							$rij_1 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['omzet_btw_verlegd']['rekening'], 0, $factuur['bedrag_excl'], $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
						}
					}
					else
					{
						$rij_0 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['verkoop']['rekening'], 0, $factuur['bedrag_incl'], $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
						
						//met BTW
						if( $factuur['bedrag_btw'] != 0 )
						{
							$rij_1 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['omzet_btw_hoog']['rekening'],$factuur['bedrag_excl'], 0, $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
							$rij_2 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['btw_afdragen_hoog']['rekening'],$factuur['bedrag_btw'], 0, $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
						} //btw verlegd
						else
						{
							$rij_1 = array( $dagboeken['verkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['omzet_btw_verlegd']['rekening'], $factuur['bedrag_excl'],0 , $factuur['inlener'], $factuur['inlener_id'], $factuur['factuur_nr'] );
						}
					}
				}
				//marge
				else
				{
					if( $creditdebet == 'debet' )
					{
						$rij_0 = array( $dagboeken['inkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['inkoop']['rekening'], 0, $factuur['bedrag_incl'], $factuur['uitzender'], $factuur['uitzender_id'], $factuur['factuur_nr'] );
						$rij_1 = array( $dagboeken['inkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['marge_uitzenders']['rekening'], $factuur['bedrag_excl'], 0, $factuur['uitzender'], $factuur['uitzender_id'], $factuur['factuur_nr'] );
						$rij_2 = array( $dagboeken['inkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['btw_vorderen_hoog']['rekening'], $factuur['bedrag_btw'], 0, $factuur['uitzender'], $factuur['uitzender_id'], $factuur['factuur_nr'] );
					}
					else
					{
						$rij_0 = array( $dagboeken['inkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['inkoop']['rekening'], $factuur['bedrag_incl'],0 , $factuur['uitzender'], $factuur['uitzender_id'], $factuur['factuur_nr'] );
						$rij_1 = array( $dagboeken['inkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['marge_uitzenders']['rekening'],0, $factuur['bedrag_excl'], $factuur['uitzender'], $factuur['uitzender_id'], $factuur['factuur_nr'] );
						$rij_2 = array( $dagboeken['inkoop']['rekening'], $i, $factuur['factuur_datum'], $dagboeken['btw_vorderen_hoog']['rekening'],0, $factuur['bedrag_btw'], $factuur['uitzender'], $factuur['uitzender_id'], $factuur['factuur_nr'] );
					}
				}
				
				if(isset($rij_0))
				{
					$excel->writeRow( $rij_0 );
					$excel->writeRow( $rij_1 );
					if( isset( $rij_2 ) )
						$excel->writeRow( $rij_2 );
					
					$i++;
				}
			}
		}
		
		$excel->setAutoWidth();
		$excel->inline( 'export_boekingen_' . date('Y_m_d_His') );
		
		die();
		//return $this->db_user->insert_id();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * laatst verwerkte factuur ophalen
	 *
	 */
	private function _laatstVerwerktefactuur()
	{
		$query = $this->db_user->query( "SELECT MAX(factuur_id) AS max_id FROM export_snelstart_file WHERE deleted = 0" );
		
		$data = DBhelper::toRow( $query, 'NULL' );
		return $data['max_id'];
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