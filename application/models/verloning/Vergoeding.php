<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class Vergoeding extends Connector
{
	
	/*
	 * @var array
	 */
	private $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Add vergoeding
	 *
	 */
	public function add()
	{
		$validator = new Validator();
		$validator->table( 'vergoedingen' )->input( $_POST )->run();
		
		$input = $validator->data();
		
		//geen fouten, nieuwe insert doen
		if( $validator->success() )
		{
			//bestaat vergoeding al?
			$sql = "SELECT vergoeding_id FROM vergoedingen WHERE deleted = 0 AND naam = ? LIMIT 1";
			$query = $this->db_user->query( $sql, array( $input['naam'] ) );
			
			if( $query->num_rows() > 0 )
			{
				$this->_error[] = 'Vergoeding bestaat al';
				return false;
			}
			
			$input['user_id'] = $this->user->user_id;
			$this->db_user->insert( 'vergoedingen', $input );
		} //fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * delete vergoeding
	 *
	 */
	public function delete( $vergoeding_id )
	{
		//Urentype ID 1 mag nooit weg!
		return $this->delete_row( 'vergoedingen', array( 'vergoeding_id' => $vergoeding_id ) );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * validate input for inlener
	 *
	 */
	public function validateInlenerVergoeding( $post = NULL )
	{
		if( $post === NULL )
			$post = $_POST;
		
		//chekc of ID bestaat
		$result = $this->select_row( 'vergoedingen', array( 'vergoeding_id' => intval( $post['vergoeding_id'] ) ) );
		
		if( $result === NULL )
			$this->_error['vergoeding_id'] = 'Er is een ongeldige vergoeding gekozen';
		
		//is doorbelasten gekozen
		if( !isset( $post['doorbelasten'] ) )
			$this->_error['doorbelasten'] = 'Maak een keuze a.u.b.';
		
		//is type gekozen
		if( !isset( $post['vergoeding_type'] ) || ( $post['vergoeding_type'] != 'vast' && $post['vergoeding_type'] != 'variabel' ) )
			$this->_error['vergoeding_type'] = 'Maak een keuze a.u.b.';
		
		//is bij vast type ook bedrag
		if( isset( $post['vergoeding_type'] ) && $post['vergoeding_type'] == 'vast' )
		{
			$post['bedrag_per_uur'] = prepareAmountForDatabase( $post['bedrag_per_uur'] );
			if( !isset( $post['bedrag_per_uur'] ) || $post['bedrag_per_uur'] == '' || $post['bedrag_per_uur'] == 0 || !is_numeric( $post['bedrag_per_uur'] ) )
				$this->_error['bedrag_per_uur'] = 'Voer een geldig bedrag in';
		}
		
		//is uitkeren gekozen
		if( !isset( $post['uitkeren_werknemer'] ) || ( $post['uitkeren_werknemer'] != 0 && $post['uitkeren_werknemer'] != 1 ) )
			$this->_error['uitkeren_werknemer'] = 'Maak een keuze a.u.b.';
		
		//show($result);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * add vergoeding input for inlener
	 *
	 */
	public function addVergoedingToInlener( $inlener_id, $post ) :?bool
	{
		//eerst controleren
		$this->validateInlenerVergoeding( $post );
		
		//stoppen bij error
		if( $this->_error !== NULL )
			return false;
			
		//cleanup
		$insert['inlener_id'] = $inlener_id;
		$insert['vergoeding_id'] = $post['vergoeding_id'];
		$insert['vergoeding_type'] = $post['vergoeding_type'];
		$insert['uitkeren_werknemer'] = $post['uitkeren_werknemer'];
		$insert['user_id'] = $this->user->user_id;
		
		if( $post['doorbelasten'] == '0' )
			$insert['doorbelasten'] = NULL;
		else
			$insert['doorbelasten'] = $post['doorbelasten'];
		
		if( $post['vergoeding_type'] == 'vast' )
			$insert['bedrag_per_uur'] = prepareAmountForDatabase( $post['bedrag_per_uur'] );

		$this->db_user->insert( 'inleners_vergoedingen', $insert );
		
		$inlener_vergoeding_id = $this->db_user->insert_id();
		
		if( $inlener_vergoeding_id < 1 )
		{
			$this->_error[] = 'Er gaat was mis bij het toevoegen van de vergoeding';
			return false;
		}
		
		//toevoegen aan werknemers
		$this->addVergoedingToWerknemers( $inlener_id, $inlener_vergoeding_id, $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * delete inlener vergoeding
	 *
	 */
	public function deleteInlenerVergoeding( $vergoeding_id ) :void
	{
		$this->db_user->query( "UPDATE inleners_vergoedingen SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE deleted = 0 AND inlener_vergoeding_id = ?", array( $this->user->user_id, $vergoeding_id ) );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Add Vergoeding to werknemers, new and old
	 * insert moet al gevalideerd zijn
	 */
	public function addVergoedingToWerknemers( $inlener_id, $inlener_vergoeding_id, $vergoeding ): void
	{
		//welke werknemers werken voor deze inlener
		$werknemers = WerknemerGroup::inlener( $inlener_id );
		
		//afbreken als er nog geen werknemers zijn
		if( $werknemers === NULL )
			return;
		
		foreach( $werknemers as $werknemer_id => $row )
		{
			$insert['inlener_vergoeding_id'] = $inlener_vergoeding_id;
			$insert['inlener_id'] = $inlener_id;
			$insert['werknemer_id'] = $werknemer_id;
			$insert['vergoeding_active'] = 1;
			$insert['vergoeding_id'] = $vergoeding['vergoeding_id'];
			$insert['user_id'] = $this->user->user_id;
			
			$insert_batch[] = $insert;
		}
		
		$this->db_user->insert_batch( 'werknemers_vergoedingen', $insert_batch );
	}

	
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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