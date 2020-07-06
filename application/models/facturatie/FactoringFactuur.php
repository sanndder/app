<?php

namespace models\facturatie;

use models\Connector;
use models\forms\Valid;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactoringFactuur extends Connector
{
	
	protected $_factuur_id = NULL;
	protected $_regel_totaal = 0;
	protected $_regel_id = NULL;
	protected $_factuur_compleet = 0;
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $factuur_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
	
		if( $factuur_id !== NULL )
			$this->_factuur_id = intval($factuur_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * calc factor bedrag
	 *
	 */
	static function calcFactoringBedrag( $factuur )
	{
		//factor bedrag
		$factorbedrag = $factuur['bedrag_incl'];
		
		//G geld er af
		if( $factuur['bedrag_grekening'] !== NULL )
			$factorbedrag -= $factuur['bedrag_grekening'];
		
		$voorschot = $factorbedrag*0.1;
		$voorschot = $voorschot *1.21; //BTW erbij
		
		return round($factorbedrag - $voorschot, 2);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * calc factor eindbedrag
	 *
	 */
	static function calcFactoringEindafrekening( $factuur )
	{
		//factor bedrag
		$factorbedrag = $factuur['bedrag_incl'];
		
		//G geld er af
		if( $factuur['bedrag_grekening'] !== NULL )
			$factorbedrag -= $factuur['bedrag_grekening'];
		
		$voorschot = $factorbedrag*0.1;
		$kosten = $factorbedrag*0.022;
		
		$restant = ($voorschot-$kosten) *1.21; //BTW erbij
		
		return round($restant, 2);
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * verwijderen
	 *
	 */
	public function delete()
	{
		$regels = $this->regels();
		
		foreach( $regels as $regel_id => $arr )
			$this->delete_row( 'facturen_betalingen', array('factor_factuur_regel_id' => $regel_id));
			
		$this->delete_row( 'factoring_facturen_regels', array('factuur_id' => $this->_factuur_id ) );
		$this->delete_row( 'factoring_facturen', array('factuur_id' => $this->_factuur_id ) );
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Factuur details ophalen
	 *
	 */
	public function details()
	{
		$query = $this->db_user->query( "SELECT * FROM factoring_facturen WHERE factuur_id = $this->_factuur_id LIMIT 1" );
		$factuur =  DBhelper::toRow( $query, 'NULL' );
		
		//nummer automatisch uitlezen
		if( $factuur['factuur_nr'] === NULL )
			$factuur['factuur_nr'] = $this->_setAankoopNummer( $factuur['file_name_display'] );
		
		if( $factuur['factuur_type'] === NULL )
			$factuur['factuur_type'] = $this->_setType( $factuur['file_name_display'] );
		
		return $factuur;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * regels voor factuur
	 *
	 */
	public function regels()
	{
		$query = $this->db_user->query( "SELECT * FROM factoring_facturen_regels WHERE factuur_id = $this->_factuur_id AND deleted = 0" );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$this->_regel_totaal += $row['bedrag'];
			$this->_regel_totaal -= $row['kosten'];
			
			$data[$row['regel_id']] = $row;
		}
		
		return $data;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * regel verwijderen
	 *
	 */
	public function delRegel( $regel_id )
	{
		$this->delete_row( 'facturen_betalingen', array('factor_factuur_regel_id' => $regel_id));
		$this->delete_row( 'factoring_facturen_regels', array('regel_id' => $regel_id) );
		
		$this->_checkFactuurComplete();
		
		return true;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * regeltoevoegen
	 *
	 */
	public function addRegel( $data )
	{
		//TODO checks
		
		//wanneer factuur nummer ingevuld is dan is het geen kosten regel
		if( isset($data['nr']) && $data['nr'] != '' )
		{
			//voor factuur regel is een bedrag nodig
			if( isset($data['bedrag']) && $data['bedrag'] != '' )
			{
				$factorfactuur = $this->details();
				
				$factuur = new Factuur();
				$factuur->setFactuurNr( $data['nr'] ) ;
				
				//stop
				if( $factuur->factuurID() === NULL )
				{
					$this->_error[] = 'Ongeldig factuur nummer';
					return false;
				}
				
				//stop
				if( $factorfactuur['factuur_datum'] === NULL )
				{
					$this->_error[] = 'Vul eerst een geldige factuurdatum in';
					return false;
				}
				
				
				//details van de factuur
				$details = $factuur->details();

				//check of factuur nr niet vaken dan 2 keer voorkomt
				if( $this->_countFactuurNrInRegels( $data['nr'] ) > 1 )
				{
					$this->_error[] = 'Factuur nr komt al op 2 aankoop- en of eindafrekeningen voor';
					return false;
				}
				
				$insert['factuur_id'] = $data['factuur_id'];
				$insert['inlener_id'] = $details['inlener_id'];
				$insert['omschrijving'] = $data['omschrijving'];
				$insert['factuur_nr'] = $data['nr'];
				$insert['bedrag'] = prepareAmountForDatabase($data['bedrag']);
				$insert['kosten'] = NULL;
				
				$this->db_user->insert( 'factoring_facturen_regels', $insert );
				$this->_regel_id = $this->db_user->insert_id();
				
				//nu betaling aan factuur toevoegen
				$betaling['type'] = $factorfactuur['factuur_type'];
				$betaling['bedrag'] = $insert['bedrag'];
				$betaling['datum'] = reverseDate($factorfactuur['factuur_datum']);
				$betaling['factor_factuur_regel_id'] = $this->_regel_id;

				$factuur->addBetaling( $betaling );
				
				$this->_checkFactuurComplete();
				
				return true;
			}
			else
			{
				$this->_error[] = 'Bedrag is onjuist';
				$this->_regel_id = $this->db_user->insert_id();
				return false;
			}
		}
		//kostenregel
		else
		{
			if( (isset($data['omschrijving']) && $data['omschrijving'] != '' && isset($data['kosten']) && $data['kosten'] != '') )
			{
				$insert['factuur_id'] = $data['factuur_id'];
				$insert['omschrijving'] = $data['omschrijving'];
				$insert['kosten'] = prepareAmountForDatabase($data['kosten']);
				
				$this->db_user->insert( 'factoring_facturen_regels', $insert );
				
				$this->_checkFactuurComplete();
				
				return true;
			}
			{
				$this->_error[] = 'Onvoldoende gegevens om kostenregel toe te voegen';
				return false;
			}
		}
		
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 *  controleer aantal fatcuur nr p de regels
	 *
	 */
	private function _countFactuurNrInRegels( $nr )
	{
		$query = $this->db_user->query( "SELECT count(regel_id) AS aantal FROM factoring_facturen_regels WHERE factuur_nr = ".intval($nr)." AND deleted = 0" );
		$data = DBhelper::toRow($query);

		return $data['aantal'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * checken of factuur compleet is
	 *
	 */
	public function _checkFactuurComplete()
	{
		$details = $this->details();
		$this->regels();
		
		$update['compleet'] = 1;
		
		//er moet een nummer datum en totaal bedrag zijn
		if( $details['factuur_nr'] === NULL || $details['factuur_datum'] === NULL || $details['factuur_totaal'] === NULL )
			$update['compleet'] = 0;
		
		//regeltotaal moet factuur totaal zijn
		if( $this->_regel_totaal == 0 || abs( ( $this->_regel_totaal - $details['factuur_totaal'] ) / $details['factuur_totaal'] ) > 0.0001 )
			$update['compleet'] = 0;
		
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'factoring_facturen', $update );
		
		$this->_factuur_compleet = $update['compleet'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * insert ID
	 *
	 */
	public function factuurCompleet()
	{
		return $this->_factuur_compleet;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * insert ID
	 *
	 */
	public function insert_id()
	{
		return $this->_regel_id;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * regeltotaal
	 *
	 */
	public function regeltotaal()
	{
		return $this->_regel_totaal;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * datum instellen
	 *
	 */
	public function setDatum( $datum )
	{
		if( $datum === '' )
			return false;
		
		$update['factuur_datum'] = reverseDate( $datum );
		
		if( !Valid::date($update['factuur_datum']))
		{
			$this->_error[] = 'Ongeldige datum';
			return false;
		}
		
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'factoring_facturen', $update );

		if( $this->db_user->affected_rows() < 0 )
		{
			$this->_error[] = 'update mislukt';
			return false;
		}
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * datum instellen
	 *
	 */
	public function setTotaalbedrag( $bedrag )
	{
		if( $bedrag === '' )
			return false;
		
		$update['factuur_totaal'] = str_replace( array('€', ' '), '', $bedrag );
		$update['factuur_totaal'] = prepareAmountForDatabase($update['factuur_totaal']);
		
		if( !is_numeric($update['factuur_totaal']))
		{
			$this->_error[] = 'Ongeldige bedrag';
			return false;
		}
		
		if( $update['factuur_totaal'] == 0 )
		{
			$this->_error[] = 'Bedrag moet groter zijn dan 0';
			return false;
		}
		
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'factoring_facturen', $update );
		
		if( $this->db_user->affected_rows() < 0 )
		{
			$this->_error[] = 'update mislukt';
			return false;
		}
		
		return true;
	}

	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Nummer uit bestandsnaam halen
	 *
	 */
	private function _setAankoopNummer( $name )
	{
		$update['factuur_nr'] = str_replace( array('Aankoopafrekening', 'Eindafrekening', ' ', '.pdf', '.PDF'), '', $name );
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'factoring_facturen', $update );
		
		return $update['factuur_nr'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Type uit bestandsnaam halen
	 *
	 */
	private function _setType( $name )
	{
		$update['factuur_type'] = NULL;
		
		if( strpos( $name, 'ankoop' ) )
			$update['factuur_type'] = 'aankoop';
		
		if( strpos( $name, 'indaf' ) )
			$update['factuur_type'] = 'eind';
		
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'factoring_facturen', $update );
		
		return $update['factuur_type'];
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