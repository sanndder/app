<?php

use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Inlener class
 * Wordt met inlener inloggen geladen en is overal beschikbaar
 *
 *
 */

class Inlener_model extends MY_Model
{
	/*
	 * @var int
	 * inlener id
	 */
	public $inlener_id = NULL;
	public $id = NULL;
	public $_redirect_url = NULL;
	
	private $_overeenkomstvanopdracht_template_id = 6;
	
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
		
		//inlener id
		$this->inlener_id = $_SESSION['logindata']['main']['inlener_id'];
		$this->id = $this->inlener_id;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Zijn er nog documenten/acties die de inlener moet uitvoeren
	 *
	 * @return boolean
	 */
	public function blockAccess()
	{
		//AV geaccepteerd?
		if( $this->acceptedAV() === false )
		{
			$this->_redirect_url = 'welkom/inlener';
			return true;
		}
		
		//alles getekend?
		if( $this->allDocumentsSigned() === false )
		{
			$this->_redirect_url = 'welkom/inlener';
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
				WHERE inlener_id = $this->inlener_id AND documenten.deleted = 0 AND documenten_templates_settings.deleted = 0
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
		//voor uitzenden
		if( $this->user->werkgever_type == 'uitzenden' )
		{
			//Samenwerkingsovereenkomst wanneer nodig
			if( $this->getOvereenkomstvanopdrachtID() === NULL )
				$this->_autoGenerateOvereenkomst( $this->_overeenkomstvanopdracht_template_id );
				
			$result['overeenkomstvanopdracht'] = $this->getOvereenkomstvanopdrachtID();
		}
	
		return $result;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Overeenkomst maken
	 *
	 *@return void
	 */
	private function _autoGenerateOvereenkomst( $template_id )
	{
		$template = new Template( $template_id ); //4 is samenwerkingsovereenkomst
		$document = DocumentFactory::createFromTemplateObject( $template );
		$document->setInlenerID( $this->inlener_id )->build()->pdf();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Overeenkomstvanopdracht ID ophalen
	 *@return int?
	 */
	public function getOvereenkomstvanopdrachtID()
	{
		$sql = "SELECT document_id FROM documenten WHERE inlener_id = $this->inlener_id AND deleted = 0 AND template_id = $this->_overeenkomstvanopdracht_template_id LIMIT 1";
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
		$sql = "SELECT id FROM inleners_av_accepted WHERE inlener_id = $this->inlener_id AND deleted = 0 LIMIT 1";
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
		$insert['inlener_id'] = $this->inlener_id;
		$insert['accepted_by'] = $this->user->user_id;
		$insert['accepted_ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['accepted_device'] = $_SERVER['HTTP_USER_AGENT'];
		
		$this->db_user->insert( 'inleners_av_accepted', $insert );
		
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