<?php

namespace models\documenten;
use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Aanmaken en beheer PDF templates
 *
 */
class Template extends Connector {

	
	/*
	 * @var array
	 */
	protected $_error = NULL;
	
	private $_template_id = NULL;
	private $_entiteit_id = NULL;
	
	private $_body = NULL;
	private $_titel = NULL;
	private $_settings = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init template if ID is known
	 *
	 * @return object
	 */
	public function __construct( $template_id = NULL )
	{
		parent::__construct();
		
		//set default entiteit
		$this->setEntiteitID();
		
		//set template ID if known
		if( $template_id !== NULL )
		{
			$this->setID( $template_id );
			$this->load();
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 *
	 * @return void
	 */
	public function load()
	{
		//settings
		$sql = "SELECT documenten_templates_settings.*, documenten_categorieen.categorie, documenten_categorieen.dir
				FROM documenten_templates_settings
				LEFT JOIN documenten_categorieen ON documenten_categorieen.categorie_id = documenten_templates_settings.categorie_id
				WHERE documenten_templates_settings.template_id = $this->_template_id AND documenten_templates_settings.deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		$this->_settings = DBhelper::toRow( $query );
		
		//titel en body
		$sql = "SELECT * FROM documenten_templates_html WHERE template_id = $this->_template_id AND deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		$row = DBhelper::toRow( $query );
		
		if( $row !== NULL )
		{
			$this->_titel = $row['titel'];
			$this->_body = $row['html'];
		}
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
	 * set template id
	 *
	 * @return object
	 */
	public function setID( $id )
	{
		$this->_template_id = intval($id);
		return $this;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set titel
	 *
	 * @return null
	 */
	public function setBodyAndTitel()
	{
		if( $_POST['titel'] != $this->_titel )
			$this->_titel = strip_tags( $_POST['titel'] );
		
		if( $_POST['editor'] != $this->_body )
			$this->_body = $_POST['editor'];
		
		$insert['titel'] = $this->_titel;
		$insert['html'] = $this->_body;
		
		if( isset($insert) )
		{
			//oude weggooien
			$sql = "UPDATE documenten_templates_html SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE deleted = 0 AND template_id = $this->_template_id";
			$this->db_user->query( $sql );
			
			//nieuwe insert
			$insert['user_id'] = $this->user->user_id;
			$insert['template_id'] = $this->_template_id;
			$this->db_user->insert( 'documenten_templates_html', $insert );
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get categorie
	 *
	 * @return string|null
	 */
	public function categorie()
	{
		if( isset($this->_settings['categorie']) )
			return $this->_settings['categorie'];
		
		return NULL;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get categorie
	 *
	 * @return string|null
	 */
	public function naam()
	{
		if( isset($this->_settings['template_name']) )
			return $this->_settings['template_name'];
		
		return NULL;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get body
	 *
	 * @return string|null
	 */
	public function body()
	{
		return $this->_body;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get titel
	 *
	 * @return string|null
	 */
	public function titel()
	{
		return $this->_titel;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get settings
	 *
	 * @return string|null
	 */
	public function settings()
	{
		return $this->_settings;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Validate template input
	 *
	 * @return array|boolean
	 */
	public function validateNewTemplateInput( $post = NULL )
	{
		if( $post === NULL ) $post = $_POST;
		
		//categorie
		if( !isset($post['categorie_id']) || $post['categorie_id'] == '' )
			$this->_error['categorie_id'] = 'Ongelidge categorie';
		
		//categorie
		if( !isset($post['owner']) || $post['owner'] == '' )
			$this->_error['owner'] = 'Ongelidge gebruiker';
		
		//naam
		if( !isset($post['template_name']) || $post['template_name'] == '' )
			$this->_error['template_name'] = 'Ongeldige naam';
		else
		{
			if( strlen($post['template_name']) < 3 )
				$this->_error['template_name'] = 'Naam is te kort';
			if( strlen($post['template_name']) > 250 )
				$this->_error['template_name'] = 'Naam is te lang';
		}
		
		//naam
		if( !isset($post['lang']) || $post['lang'] == '' )
			$this->_error['lang'] = 'Ongeldige taal';
		else
		{
			if( strlen($post['lang']) != 2 )
				$this->_error['lang'] = 'Taalcode moet 2 tekens zijn';
		}
		
		
		//chekc of code bestaat
		if( isset($post['template_code']) && $post['template_code'] != '' )
		{
			$result = $this->select_row( 'documenten_templates_settings', array( 'template_code' => $post['template_code'] ) );
			if( $result !== NULL )
				$this->_error['template_code'] = 'Er bestaat al een document met dit nummer';
			
			if( strlen($post['template_code']) > 30 )
				$this->_error['template_code'] = 'Nummer is te lang';
		}
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe template aanmeken
	 *
	 * @return boolean
	 */
	public function new( $post = NULL )
	{
		//controle
		$this->validateNewTemplateInput( $post );
		if( $this->errors() != false )
			return false;
		
		//nieuwe ID bepalen
		$max_template_id = $this->max( 'documenten_templates_settings', 'template_id' );
			
		$insert['entiteit_id'] = $this->_entiteit_id;
		$insert['template_id'] = $max_template_id+1;
		$insert['categorie_id'] = $post['categorie_id'];
		$insert['owner'] = $post['owner'];
		$insert['lang'] = strtoupper($post['lang']);
		$insert['template_name'] = $post['template_name'];
		if( isset($post['template_code']) && $post['template_code'] != '' )
			$insert['template_code'] = $post['template_code'];
		
		$this->db_user->insert( 'documenten_templates_settings', $insert );
		
		if( $this->db_user->insert_id() > 0 )
		{
			$this->setID( $insert['template_id'] );
			return true;
		}
		
		$this->_error[] = 'Database insert mislukt';
		return  false;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * return ID
	 * @return ?int
	 */
	public function id()
	{
		return $this->_template_id;
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