<?php

namespace models\forms;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Validatie class
 *
 * Voor het valideren van input
 *
 */
class Formbuilder extends Json {

	/*
	 * @var array
	 */
	private $_errors = array();

	/*
	 * @var array
	 * Data from class tahts needs to go into the form
	 */
	private $_data = array();

	/*
	 * @var array
	 * Data from class tahts needs to go into the form
	 */
	private $_formdata = array();

	/*
	 * @var mixed
	 * Errors from form validatiob
	 */
	private $_validation_errors = false;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function __construct()
	{
		//call parent
		parent::__construct();
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set data
	 *
	 */
	public function data( $data )
	{
		$this->_data = $data;
		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * add errors
	 *
	 */
	public function errors( $errors = false )
	{
		if( $errors == false || $errors == NULL || $errors == 0 || count($errors) == 0 )
			$errors = false;

		$this->_validation_errors = $errors;
		return $this;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * build the form from multilayer array
	 *
	 */
	public function buildFromArray()
	{
		//copy data to local var
		$data_array = $this->_data;

		//init local formdata
		$form_data = array();

		foreach( $data_array as $key => $array )
		{
			$this->data($array);
			$form_data[$key] = $this->build();
		}

		//back to global
		$this->_data = $data_array;
		$this->_formdata = $form_data;

		//cleanup
		unset($data_array);
		unset($form_data);

		return $this->_formdata;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * build the form
	 *
	 */
	public function build()
	{
		//reset outpu
		$this->_formdata = array();

		foreach( $this->json->field as $field => $info )
		{
			//set label
			$this->_formdata[$field]['label'] = $info->label;

			//set value, if no value, than empty
			$this->_formdata[$field]['value'] = $this->_data[$field] ?? '';

			//maybe an error?
			if( isset($this->_validation_errors[$field]) )
				$this->_formdata[$field]['error'] = $this->_validation_errors[$field];

			//radio button?
			if( isset($info->radio) )
			{
				$this->_formdata[$field]['radio'] = json_decode(json_encode($info->radio), true);
			}
			
			//select list?
			if( isset($info->list) )
			{
				$this->_formdata[$field]['list'] = json_decode(json_encode($info->list), true);
			}

		}
		//show($this->_formdata);
		//show($this->json);

		return $this->_formdata;
	}

}


?>