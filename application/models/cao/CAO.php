<?php

namespace models\cao;
use models\Connector;
use models\utils\DBhelper;
use mysql_xdevapi\Exception;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ID class
 *
 * regelt alles omtrent ID bewijs van werknemer of freelancer
 *
 */
class CAO extends Connector {

	/*
	 * @vars
	 */
	private $_cao_id =  NULL;
	private $_salary_table_id = NULL;
	private $_salary_table_info = NULL;
	private $_jobs = NULL;
	private $_job = NULL;
	private $_max_available_age = NULL;
	private $_salary_matrix = array();
	private $_periodieken = NULL;
	private $_periodiek = NULL;
	private $_schalen = NULL;
	private $_schaal = NULL;
	private $_leeftijd =  NULL;
	private $_use_leeftijd =  NULL;
	
	/*
	 * @var array
	 */
	protected $_error = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * @return $this
	 */
	public function __construct( $cao_id = NULL )
	{
		parent::__construct();
		
		if( $cao_id !== NULL )
			$this->setID( $cao_id );
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set CAO ID. ID is intern, cao ID van coaloon is geen int (v.b. 417-2019)
	 * @return object
	 *
	 */
	public function setID( $cao_id )
	{
		$this->_cao_id = intval( $cao_id );
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set leeftijd
	 * @return object
	 *
	 */
	public function setLeeftijd( $leeftijd )
	{
		$this->_leeftijd = intval( $leeftijd );
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * List loontabellen bij cao
	 * @return array
	 *
	 */
	public function loontabellen()
	{
		if( $this->_leeftijd === NULL )
		{
			$this->_error[] = 'Geen leeftijd ingesteld';
			return NULL;
		}
		
		$sql = "SELECT salary_table_id, REPLACE(LCASE(cao_salary_table.short_name), '_', ' ') AS short_name, description
				FROM cao_salary_table WHERE cao_id_intern = $this->_cao_id AND max_age >= $this->_leeftijd AND min_age <= $this->_leeftijd ORDER BY active_per";
		$query = $this->db_user->query($sql);
		
		return DBhelper::toArray( $query, 'short_name' );
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * List jobs bij cao
	 * @return array
	 *
	 */
	public function jobs()
	{
		$jobs_for_salary_table = str_replace( array('[',']'), '', $this->_salary_table_info['jobs']);
		
		if( $jobs_for_salary_table == '' )
		{
			$this->_error[] = 'Ongeldige loontabel ID';
			return NULL;
		}
		
		$sql = "SELECT * FROM cao_jobs WHERE cao_id_intern = $this->_cao_id AND job_id IN ($jobs_for_salary_table) ORDER BY name ASC";
		$query = $this->db_user->query($sql);
		
		$this->_jobs = DBhelper::toArray( $query, 'id' );
		return $this->_jobs;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * functie instellen
	 * @return object
	 *
	 */
	public function setJob( $job_id )
	{
		//ander beroep dan uit de lijst
		if( !isset($this->_jobs[$job_id]) )
		{
			$this->_error[] = 'Ongeldig functie ID';
			return NULL;
		}
		
		$this->_job = $this->_jobs[$job_id];
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Loontabel instellen
	 * @return object
	 *
	 */
	public function setLoontabel( $salary_table_id )
	{
		$salary_table_id = intval($salary_table_id);
		
		$sql = "SELECT * FROM cao_salary_table WHERE cao_id_intern = $this->_cao_id AND salary_table_id = $salary_table_id ORDER BY active_per DESC LIMIT 1 ";
		$query = $this->db_user->query( $sql );
		
		$this->_salary_table_info = DBhelper::toRow( $query );
		
		$this->_salary_table_id = $this->_salary_table_info['id'];
		
		/*
		$sql = "SELECT * FROM cao_salary_table_salaries WHERE salary_table_id_intern = $this->_salary_table_id";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$this->_schalen[] = $row['payscale'];
			$this->_periodieken[] = $row['periodical'];
			$this->_leeftijden[] = $row['age'];
		}
		
		show($this->_schalen);
		show($this->_periodieken);
		show($this->_leeftijden);
		*/
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Loontabel voor een functie
	 * @return array
	 *
	 */
	public function salaryTable()
	{
		return $this->_salary_table_info;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * schalen voor een functie, gelijk salary matrix opbouwen
	 * @return array
	 *
	 */
	public function schalen()
	{
		if($this->_job === NULL )
		{
			$this->_error[] = 'Ongeldige functie gekozen';
			return NULL;
		}
		
		//mogelijke schalen
		$beschikbare_schalen_string = str_replace( array('[',']'),'', $this->_job['payscales']);
		
		$sql = "SELECT * FROM cao_salary_table_salaries
				WHERE cao_id_intern = $this->_cao_id
				AND salary_table_id = $this->_salary_table_id AND payscale IN ($beschikbare_schalen_string)
				ORDER BY payscale, age, periodical";
		
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			//max leeftijd van de loontabel onthouden
			if( $row['age'] > $this->_max_available_age ) $this->_max_available_age = $row['age'];
			
			//salary matrix opbouwen
			$this->_salary_matrix[$row['payscale']][$row['age']][$row['periodical']] = $row;
			
			//array van schalen om uit te kiezen, deze als return terug
			$this->_schalen[$row['payscale']] = $row['payscale'];
		}
		
		return $this->_schalen;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * schaal instellen
	 * @return object
	 *
	 */
	public function setSchaal( $schaal )
	{
		$this->_schaal = $schaal;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * periodieken voor tabel
	 * @return array
	 *
	 */
	public function periodieken()
	{
		//welke leeftijd moet er gebruikt worden
		if( $this->_max_available_age === NULL )
		{
			$this->_use_leeftijd = ''; //geen leeftijd in de matrix
		}
		else
		{
			if( $this->_leeftijd > $this->_max_available_age )
				$this->_use_leeftijd = number_format($this->_max_available_age,2);
			else
				$this->_use_leeftijd = number_format($this->_leeftijd,2);
		}
		
		//schaal niet uit de lijst
		if( !isset($this->_salary_matrix[$this->_schaal]) )
		{
			$this->_error[] = 'Ongeldige schaal';
			return  NULL;
		}
		
		//foreach gebruiken ipv array_keys zodat key en value gelijk zijn
		foreach( $this->_salary_matrix[$this->_schaal][$this->_use_leeftijd] as $k => $v )
			$this->_periodieken[$k] = $k;
		return $this->_periodieken;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * periodiek instellen
	 * @return object
	 *
	 */
	public function setPeriodiek( $periodiek )
	{
		$this->_periodiek = $periodiek;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uurloon ophalen
	 * @return float
	 *
	 */
	public function uurloon()
	{
		//er is geen periodiek, of maar 1
		if($this->_periodiek === NULL )
		{
			if( $this->_periodieken !== NULL && count($this->_periodieken) == 1 )
			{
				if( key($this->_periodieken) != '' )
				$this->_periodiek = key($this->_periodieken);
			}
		}
		
		if( isset($this->_salary_matrix[$this->_schaal][$this->_use_leeftijd][$this->_periodiek]) )
			return $this->_salary_matrix[$this->_schaal][$this->_use_leeftijd][$this->_periodiek]['salary'];
		
		$this->_error[] = 'Uurloon niet gevonden';
		
		return  NULL;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * aan inlener koppelen
	 * @return
	 *
	 */
	public function addCAOToInlener( $inlener_id )
	{
		if( $this->_cao_id === NULL )
		{
			$this->_error[] = 'Er is geen CAO ID ingesteld';
			return $this;
		}
		
		$inlener_id = intval($inlener_id);
		
		//alle no_cao weghalen
		$this->db_user->query( "UPDATE inleners_cao SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND inlener_id = $inlener_id AND no_cao = 1" );
		
		//bestaat koppeling al?
		$sql = "SELECT id FROM inleners_cao WHERE inlener_id = $inlener_id AND cao_id_intern = $this->_cao_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		if( $query->num_rows() > 0 )
		{
			$this->_error[] = 'CAO is al toegevoegd aan inlener';
			return $this;
		}
		
		$insert['inlener_id'] = $inlener_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['cao_id_intern'] = $this->_cao_id;
		
		$this->db_user->insert( 'inleners_cao', $insert );
		
		return $this;
	}



	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete CAO from inlener
	 *
	 * @return
	 *
	 */
	public function delCAOFromInlener( $inlener_id )
	{
		if( $this->_cao_id === NULL )
		{
			$this->_error[] = 'Er is geen CAO ID ingesteld';
			return $this;
		}
		
		$inlener_id = intval($inlener_id);
		
		$update['deleted'] = 1;
		$update['deleted_by'] = $this->user->user_id;
		
		$this->db_user->where( 'inlener_id', $inlener_id );
		$this->db_user->where( 'cao_id_intern', $this->_cao_id  );
		$this->db_user->update( 'inleners_cao', $update );
		
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
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}
}


?>