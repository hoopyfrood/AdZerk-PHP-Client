<?php

/**
 * Adzerk API thin client
 * Jul 2012 - @hubail
 * 
 * Usage Tips, Advertisers as example, consistent behavior for all endpoints:
 * 
 * List Advertisers
 * $adzerk->advertiser(); // no arguments
 * 
 * Create Advertiser
 * $adzerk->advertiser(null, array()); // arg1: null, arg2: array of data (plain-text associative PHP array)
 * 
 * Update Advertiser
 * $adzerk->advertiser($AdvertiserId, array()); // arg1: (int) Advertiser ID, arg2: array of data (plain-text associative PHP array)
 * 
 * All responses are returned by default, as an associative PHP array
 * 
 */
class Adzerk {
	
	private $apiKey;
	private $apiBase = 'http://api.adzerk.net/v1/';
	
	function __construct($key){
		$this->apiKey = $key;
	}
	// HTTP Requests
	/**
	 * GET
	 */
	function _get($func, $var=null){

		$url = $this->apiBase.$func;
		$url .= is_null($var) ? '' : '/'.$var;
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Adzerk-ApiKey: '.$this->apiKey));
		
		$result = curl_exec($ch);
		if ($result===false) throw new Exception(curl_error($ch), curl_errno($ch));
		curl_close($ch);
		
		return json_decode($result);
	}
	/**
	 * POST [and PUT]
	 */
	function _post($func, $var=null, $data=null, $is_put=false){
		$url = $this->apiBase.$func;
		$url .= is_null($var) ? '' : '/'.$var;
		
		$data = $func.'='.urlencode(json_encode($data));
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Adzerk-ApiKey: '.$this->apiKey));
		if ($is_put==true) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);
		if ($result===false) throw new Exception(curl_error($ch), curl_errno($ch));
		curl_close($ch);
		
		return json_decode($result);
	}
	
	function _put($func, $var=null, $data=null){
		return $this->_post($func, $var, $data, true);
	}
	
	function __call($func, $param){
		if (count($param)<2) return $this->_get($func, !isset($param[0]) ? null : $param[0]); // GET
		if (count($param)>=2 && is_null($param[0])) return $this->_post($func, $param[0], $param[1]); // POST
		if (count($param)>=2 && !is_null($param[0])) return $this->_put($func, $param[0], $param[1]); // PUT
	}
}