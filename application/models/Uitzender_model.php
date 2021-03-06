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
	
	public $systeeminstellingen = NULL;
	
	//templates van documenten die getekend moeten worden
	private $_blocked_template_ids = NULL;

	
	/*
	 * @var array
	 * entiteiteb
	 */

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * construct
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		//uitzender id
		if( isset( $_SESSION['logindata']['override']['uitzender_id'] ) )
			$this->uitzender_id = $_SESSION['logindata']['override']['uitzender_id'];
		else
			$this->uitzender_id = $_SESSION['logindata']['main']['uitzender_id'];
		
		$this->id = $this->uitzender_id;
		
		//load systeem instellingen
		$this->_loadSysteeminstellingen();
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Systeeminstellingen laden
	 *
	 * @return void
	 */
	private function _loadSysteeminstellingen()
	{
		//default
		$this->systeeminstellingen = new \stdClass();
		$this->systeeminstellingen->facturen_wachtrij = 0;
		
		$query = $this->db_user->query( "SELECT facturen_wachtrij FROM uitzenders_systeeminstellingen WHERE uitzender_id = ? AND deleted = 0", array($this->id) );
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		
		$this->systeeminstellingen->facturen_wachtrij = $data['facturen_wachtrij'];
		
		return NULL;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Statusbolletjes
	 *
	 * @return boolean
	 */
	public function statusCount( $type = NULL ) :?int
	{
		if( $type == 'facturen_wachtrij' )
		{
			if( $this->systeeminstellingen->facturen_wachtrij == 1 )
				$query = $this->db_user->query( "SELECT count(factuur_id) AS aantal FROM facturen WHERE deleted = 0 AND concept = 0 AND wachtrij = 1 AND wachtrij_akkoord = 0 AND uitzender_id = ?", array($this->id) );
			else
				return NULL;
		}
		else
			return NULL;
		
		$data = $query->row_array();
		return $data['aantal'];
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
				  AND documenten.inlener_id IS NULL AND documenten.werknemer_id IS NULL AND documenten.zzp_id IS NULL
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
	 * Blocked = 1 ophalend
	 *
	 * @return void
	 */
	public function _getBlockingTemplates()
	{
		$sql = "SELECT template_id, template_name FROM documenten_templates_settings WHERE owner = 'uitzender' AND block_access = 1 AND deleted = 0";
		$query = $this->db_user->query( $sql );
		
		$this->_blocked_template_ids = DBhelper::toArray( $query, 'template_id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Kijken welke document noodzakelijk zijn om verder te mogen (blok access = 1) en deze aanmaken
	 * 
	 * @return boolean
	 */
	public function autoGenerateDocuments()
	{
		$this->_getBlockingTemplates();
		
		//geen documten die blokkeren? dan terug
		if( $this->_blocked_template_ids === NULL )
			return false;
		
		//alle documenten nu maken indien nodig
		foreach( $this->_blocked_template_ids as $template_id => &$template )
		{
			if( $this->getDocumentID( $template_id ) === NULL )
				$this->_autoGenerateOvereenkomst( $template_id );
			
			$template['document_id'] = $this->getDocumentID( $template_id );
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * document info terug
	 *
	 * @return array|void
	 */
	public function blockingDocuments()
	{
		return $this->_blocked_template_ids;
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
	 * document ID ophalen
	 *
	 *@return int?
	 */
	public function getDocumentID( $template_id )
	{
		$sql = "SELECT document_id FROM documenten WHERE uitzender_id = $this->uitzender_id AND deleted = 0 AND template_id = $template_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		$row = DBhelper::toRow( $query, 'NULL' );
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