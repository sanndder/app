<?php

namespace models\file;


use SimpleXMLElement;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Pdf class
 *
 * File handlig for pdf file types
 *
 */
class Xml extends File{
	
	protected $_xml = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 */
	public function __construct( $input = NULL )
	{
		parent::__construct( $input );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe xml met eerste tag
	 *
	 */
	public function new( $tag = '' ) :Xml
	{
		$this->_xml = new SimpleXMLElement( $tag );
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe xml met eerste tag
	 *
	 */
	public function addChild( $parent, $child, $value = NULL ) :Xml
	{
		if( $parent !== NULL ) $parent = str_replace( ' ', '' , $parent);
		if( $child !== NULL ) $child = str_replace( ' ', '' , $child);
		
		if( $parent === NULL )
		{
			if( $value !== NULL )
				$this->$child = $this->_xml->addChild($child, $value);
			else
				$this->$child = $this->_xml->addChild($child);
		}
		else
		{
			if( $value !== NULL )
				$this->$child = $this->$parent->addChild($child, $value);
			else
				$this->$child = $this->$parent->addChild($child);
		}
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe xml met eerste tag
	 *
	 */
	public function addAttr( $parent, $name, $value = NULL ) :Xml
	{
		$this->$parent->addAttribute( $name, $value );
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * xml naar scherm
	 *
	 */
	public function print()
	{
		header( "Content-Type:text/xml" );
		echo( $this->_xml->asXML() );
		die();
	}


}


?>