<?php

use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Uitzender class
 * Wordt met uitzender inloggen geladen en is overal beschikbaar
 *
 *
 */

class Uitzender_model extends MY_Model
{
	/*
	 * @var int
	 * uitzender id
	 */
	public $uitzender_id = NULL;
	public $id = NULL;
	public $_redirect_url = NULL;
	
	private $_samenwerkingsovereenkomst_template_id = 4;
	private $_verwerkingsovereenkomst_template_id = 5;
	
	/*
	 * @var array
	 * entiteiteb
	 */
	
	private $_error = NULL;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * construct
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		//uitzender id
		$this->uitzender_id = $_SESSION['logindata']['main']['uitzender_id'];
		$this->id = $this->uitzender_id;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Zijn er nog documenten/acties die de uitzender moet uitvoeren
	 *
	 * @return boolean
	 */
	public function blockAccess()
	{
		//AV geaccepteerd?
		if( $this->acceptedAV() === false )
		{
			$this->_redirect_url = 'welkom/uitzender';
			return true;
		}
		
		//alles getekend?
	
		if( $this->allDocumentsSigned() === false )
		{
			$this->_redirect_url = 'welkom/uitzender';
			return true;
		}
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * alle documenten getekend?
	 *
	 * @return bool
	 */
	public function allDocumentsSigned()
	{
		$sql = "SELECT documenten.document_id
				FROM documenten
				LEFT JOIN documenten_templates_settings ON documenten_templates_settings.template_id = documenten.template_id
				WHERE uitzender_id = $this->uitzender_id AND documenten.deleted = 0 AND documenten_templates_settings.deleted = 0
				AND documenten.signed = 0 AND documenten_templates_settings.block_access = 1
				";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
			return false;
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * waar moeten we dan heen als er nog wat te doen is
	 *
	 * @return string
	 */
	public function redirectUrl()
	{
		return $this->_redirect_url;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * TODO: betere afhandeling, geen hardcode ID;s
	 * Samenwerkingsovereenkomst en TODO: verwerkingsovereenkomst maken
	 * @return array
	 */
	public function generateDocuments()
	{
		//Samenwerkingsovereenkomst wanneer nodig
		if( $this->getSamenwerkingsovereenkomstID() === NULL )
			$this->_autoGenerateOvereenkomst( $this->_samenwerkingsovereenkomst_template_id );
		
		//Samenwerkingsovereenkomst wanneer nodig
		if( $this->getVerwerkingsovereenkomstID() === NULL )
			$this->_autoGenerateOvereenkomst( $this->_verwerkingsovereenkomst_template_id );
		
		$result['samenwerkingsovereenkomst'] = $this->getSamenwerkingsovereenkomstID();
		$result['verwerkingsovereenkomst'] = $this->getVerwerkingsovereenkomstID();
		
		return $result;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Samenwerkingsovereenkomst maken
	 *@return void
	 */
	private function _autoGenerateOvereenkomst( $template_id )
	{
		$template = new Template( $template_id ); //4 is samenwerkingsovereenkomst
		
		$document = DocumentFactory::createFromTemplateObject( $template );
		$document->setUitzenderID( $this->uitzender_id )->build()->pdf();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Samenwerkingsovereenkomst ID ophalen
	 *@return int?
	 */
	public function getSamenwerkingsovereenkomstID()
	{
		$sql = "SELECT document_id FROM documenten WHERE uitzender_id = $this->uitzender_id AND deleted = 0 AND template_id = $this->_samenwerkingsovereenkomst_template_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		$row = DBhelper::toRow( $query );
		if( $row === NULL )
			return $row;
		
		return $row['document_id'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Verwerkingsovereenkomst ID ophalen
	 *@return int?
	 */
	public function getVerwerkingsovereenkomstID()
	{
		$sql = "SELECT document_id FROM documenten WHERE uitzender_id = $this->uitzender_id AND deleted = 0 AND template_id = $this->_verwerkingsovereenkomst_template_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		$row = DBhelper::toRow( $query );
		if( $row === NULL )
			return $row;
		
		return $row['document_id'];
	}


		
		
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Algemene voorwaarden accepteren
	 *
	 * @return boolean
	 */
	public function acceptedAV()
	{
		$sql = "SELECT id FROM uitzenders_av_accepted WHERE uitzender_id = $this->uitzender_id AND deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return false;
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Algemene voorwaarden accepteren
	 *
	 * @return boolean
	 */
	public function acceptAV()
	{
		$insert['av_id'] = $this->werkgever->AVID();
		$insert['uitzender_id'] = $this->uitzender_id;
		$insert['accepted_by'] = $this->user->user_id;
		$insert['accepted_ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['accepted_device'] = $_SERVER['HTTP_USER_AGENT'];
		
		$this->db_user->insert( 'uitzenders_av_accepted', $insert );
		
		if( $this->db_user->insert_id() > 0 )
			return true;
		return false;
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