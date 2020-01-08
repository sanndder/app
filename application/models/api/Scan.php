<?php

namespace models\api;

use models\Connector;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * File class
 *
 * File handlig for all file types
 *
 */

class Scan extends Connector
{


	/*
	 * @var array
	 */
	protected $_file = NULL;
	protected $_file_path = NULL;


	/*
	 * @var array
	 */
	protected $_error = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param file array
	 * @return $this
	 */
	public function __construct()
	{
		parent::__construct();

	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * String to info to vars
	 * @return void
	 */
	public function setFile($array)
	{

		$this->_file_path = UPLOAD_DIR . '/werkgever_dir_' . $this->user->werkgever_id . '/' . $array['file_dir'] . '/' . $array['file_name'];

		if (!file_exists($this->_file_path))
			die('bestand niet gevonden');

		$this->_file = file_get_contents($this->_file_path);

		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		/*
		 * String to info to vars
		 * @return void
		 */
	public function call()
	{
		$curl = curl_init();

		$params = array("scan_image_base64" => base64_encode(file_get_contents($this->_file_path)),"card_type" => "1");

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://accurascan.com/v2/api",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "UTF-8",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_HTTPHEADER => array(
				"Api-key: 1565097168llz97OTgzklvHRKc3jmnguUrwq1SigniHd8gMkjo",
				"Content-Type: application/x-www-form-urlencoded",
				"Postman-Token: 63929a9f-a1e2-44fc-abba-3bb5cfb303b9",
				"cache-control: no-cache",
				"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
			),
		));

		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			show("cURL Error #:" . $err);
		} else {
			$data = json_decode($response,true);
			show($data);
			show($data);
			$data2['voorletters'] = str_replace( '<','', $data['data'][0]['mrz']);
			$data2['voorletters'] = str_replace( '>','', $data['data'][0]['mrz']);
			$data2['voorletters'] = str_replace( ' ','.', $data['data'][0]['mrz']);
			show($data2);
			$data2['achternaam'] = ucfirst(strtolower($data['data'][0]['surname']));
			show($data2);
			$data2['voornaam'] = ucfirst(strtolower($data['data'][0]['given_names']));


			show($data2);
			return $data2;
		}
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