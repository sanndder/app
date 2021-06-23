<?php

namespace models\werknemers;

use models\cao\CAOGroup;
use models\Connector;
use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\inleners\Inlener;
use models\utils\DBhelper;
use models\verloning\Urentypes;
use models\verloning\UrentypesGroup;
use models\verloning\Vergoeding;
use models\verloning\VergoedingGroup;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class Plaatsing extends Connector
{
	/**
	 * @var int
	 */
	private $_plaatsing_id;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $plaatsing_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		if( $plaatsing_id !== NULL )
			$this->setID( $plaatsing_id );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($plaatsing_id)
	{
		$this->_plaatsing_id = intval($plaatsing_id);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factor plaatsing aanpassen
	 *
	 */
	public function setFactor( $factor_id ) :bool
	{
		$update['factor_id'] = intval($factor_id);
		
		$this->db_user->where( 'plaatsing_id', $this->_plaatsing_id );
		$this->db_user->update( 'werknemers_inleners', $update );
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get details
	 */
	public function details() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM werknemers_inleners WHERE plaatsing_id = $this->_plaatsing_id" );
		return DBhelper::toRow( $query, 'NULL' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get details
	 */
	public function info() :?array
	{
		$sql = "SELECT werknemers_inleners.*, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener,
       					werknemers_gegevens.gb_datum, werknemers_gegevens.achternaam, werknemers_gegevens.tussenvoegsel, werknemers_gegevens.voorletters, werknemers_gegevens.voornaam,
       					cao.name AS cao, cao_jobs.name AS functie, REPLACE(LCASE(cao_salary_table.short_name), '_', ' ') AS loontabel
				FROM werknemers_inleners
				LEFT JOIN inleners_bedrijfsgegevens ON werknemers_inleners.inlener_id = inleners_bedrijfsgegevens.inlener_id
				LEFT JOIN werknemers_gegevens ON werknemers_inleners.werknemer_id = werknemers_gegevens.werknemer_id
				LEFT JOIN cao ON cao.id = werknemers_inleners.cao_id_intern
				LEFT JOIN cao_jobs ON cao_jobs.id = werknemers_inleners.job_id_intern
				LEFT JOIN cao_salary_table ON cao_salary_table.salary_table_id = werknemers_inleners.loontabel_id_intern AND cao_salary_table.cao_id_intern = werknemers_inleners.cao_id_intern
				WHERE werknemers_inleners.deleted = 0 AND inleners_bedrijfsgegevens.deleted = 0 AND werknemers_gegevens.deleted = 0 AND werknemers_inleners.plaatsing_id = $this->_plaatsing_id";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		
		$CAOgroup = new CAOGroup();
		$caos_inlener = $CAOgroup->inlener( $data['inlener_id'] );
		
		//juiste cao erbij
		foreach( $caos_inlener as $cao)
		{
			if( $cao['cao_id_intern'] == $data['cao_id_intern'] )
				$data['cao'] = $cao;
		}

		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * update bruto uurloon
	 * TODO: minimumloon
	 */
	public function setBrutoUurloon( $uurloon )
	{
		$update['bruto_loon'] = prepareAmountForDatabase($uurloon);

		$this->db_user->where( 'plaatsing_id', $this->_plaatsing_id );
		$this->db_user->update( 'werknemers_inleners', $update );

		if( $this->db_user->affected_rows() > 0 )
			return true;

		return false;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uitzendbevestiging maken
	 *
	 */
	public function generateUitzendbevestiging()
	{
		$plaatsing = $this->info();

		$template = new Template( 8 ); //8 is uitzendbevestiging
		$document = DocumentFactory::createFromTemplateObject( $template );
	
		$pdf = $document->setWerknemerID( $plaatsing['werknemer_id'] )->setInlenerId( $plaatsing['inlener_id'] )->setPlaatsing( $plaatsing )->build()->pdf();
		
		if( $document->documentID() !== NULL )
		{
			$update['document_id'] = $document->documentID();
			$this->db_user->where( 'plaatsing_id', $plaatsing['plaatsing_id'] );
			$this->db_user->update( 'werknemers_inleners', $update );
		}

		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uitzendbevestiging maken
	 *
	 */
	public function generateOpdrachtbevestiging()
	{
		$plaatsing = $this->info();
		$uurtarieven = $this->uurtarieven( $plaatsing );
		
		$template = new Template( 18 ); //18 is opdrachtbevestiging
		$document = DocumentFactory::createFromTemplateObject( $template );
		
		$pdf = $document->setWerknemerID( $plaatsing['werknemer_id'] )->setInlenerId( $plaatsing['inlener_id'] )->setPlaatsing( $plaatsing, $uurtarieven )->build()->pdf()->download();
		
		if( $document->documentID() !== NULL )
		{
			$update['document_id'] = $document->documentID();
			$this->db_user->where( 'plaatsing_id', $plaatsing['plaatsing_id'] );
			$this->db_user->update( 'werknemers_inleners', $update );
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Uurtarief standaard uren
	 */
	public function uurtarieven( $plaatsing = NULL )
	{
		if( $plaatsing === NULL )
			$plaatsing = $this->info();
		
		$urentypesGroup = new UrentypesGroup();
		$urentypes =  $urentypesGroup->inlener( $plaatsing['inlener_id'] )->urentypesWerknemer( $plaatsing['werknemer_id'], true );
		
		foreach( $urentypes as $type )
		{
			if( $type['urentype_active'] == 1 AND $type['verkooptarief'] > 0 &&  $type['doorbelasten_uitzender'] == 0 )
				$active[] = $type;
		}

		return $active;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete
	 */
	public function delete()
	{
		$plaatsing = $this->details();
		
		//plaatsing weghalen
		$this->db_user->query( "UPDATE werknemers_inleners SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND plaatsing_id = $this->_plaatsing_id" );
		
		//urentypes weghalen bij werknemer
		if( $this->db_user->affected_rows() > 0 )
		{
			$urentypes = new Urentypes();
			$urentypes->deleteUrentypesWerknemerForInlener( $plaatsing['werknemer_id'], $plaatsing['inlener_id'] );
			
			$vergoedingengroup = new VergoedingGroup();
			$vergoedingengroup->deleteVergoedingenWerknemerForInlener( $plaatsing['werknemer_id'], $plaatsing['inlener_id'] );
		}
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * add plaatsing
	 * TODO: validate input, minimumloon
	 */
	public function add( $data )
	{
		//geen dubbele plaatsing
		$query = $this->db_user->query( "SELECT plaatsing_id FROM werknemers_inleners WHERE werknemer_id = ? AND inlener_id = ? AND deleted = 0", array( intval($data['werknemer_id']), intval($data['inlener_id']) ) );
		if( $query->num_rows() > 0 )
		{
			$this->_error[] = 'Werknemer is al geplaatst bij inlener';
			return false;
		}
		
		//factor ophalen
		$inlener = new Inlener($data['inlener_id']);
		$standaardfactor =  $inlener->standaardfactor();
		
		$input['factor_id'] = $standaardfactor['factor_id'];
		
		$input['werknemer_id'] = $data['werknemer_id'];
		$input['inlener_id'] = $data['inlener_id'];
		$input['cao_id_intern'] = $data['cao_id'];
		$input['loontabel_id_intern'] = $data['loontabel_id'];
		$input['job_id_intern'] = $data['job_id'];
		$input['schaal'] = $data['schaal_id'] ?? null;
		$input['periodiek'] = $data['periodiek_id'] ?? null;
		$input['bruto_loon'] = prepareAmountForDatabase($data['brutoloon']);
		$input['start_plaatsing'] = reverseDate($data['start_plaatsing']);
		
		
		$this->db_user->insert( 'werknemers_inleners', $input );
		
		//TODO bruto uurloon naar de standaard tabel
		
		
		//als het gelukt is dan uretypes koppelen
		if( $this->db_user->insert_id() > 0 )
		{
			$urentypes = new Urentypes();
			$urentypes->addUrentypesWerknemerForInlener($this->db_user->insert_id(), $input['werknemer_id'], $input['inlener_id'] );
			
			$vergoedinggroup = new VergoedingGroup();
			$vergoedinggroup->addVergoedingenWerknemerForInlener( $this->db_user->insert_id(), $input['werknemer_id'], $input['inlener_id'] );

		}
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