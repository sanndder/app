<?php

namespace models\documenten;
use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Aanmaken en beheer PDF templates
 *
 */
class TemplateGroup extends Connector {

	/*
	 * @vars
	 */
	private $_entiteit_id = NULL;
	
	/*
	 * @var array
	 */
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init parent
	 */
	public function __construct()
	{
		parent::__construct();
		
		//set default entiteit
		$this->setEntiteitID();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set entiteit ID
	 *
	 * @return void
	 */
	public function setEntiteitID( $id = NULL )
	{
		if( $id !== NULL )
			$this->_entiteit_id = $id;
		else
			$this->_entiteit_id = $_SESSION['entiteit_id'];
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle templates ophalen
	 *
	 * @return array|null
	 */
	public function all()
	{
		$sql = "SELECT documenten_templates_settings.*, documenten_categorieen.categorie
				FROM documenten_templates_settings
				LEFT JOIN documenten_categorieen ON documenten_templates_settings.categorie_id = documenten_categorieen.categorie_id
				WHERE documenten_templates_settings.deleted = 0 AND documenten_categorieen.deleted = 0
				AND documenten_templates_settings.entiteit_id = $this->_entiteit_id
				ORDER BY categorie, template_name";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'template_id' );
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle catergorieen documenten ophalen
	 * @return array
	 */
	public function categorieen()
	{
		return $this->select_all( 'documenten_categorieen', 'categorie_id' );
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