<?php

/**
 * Ninja Blocks API Helper Classes.
 *
 */
class NBAPI {
	public $version = "v0";

	/**
	 * Url for the Ninja Blocks endpoint.
	 * @var string
	 */
	public $apiUrl;

	public $timeout = 300;

	// Constructor
	public function __construct() {

		// Set the API url. Embed the version number straight in.
		$this->apiUrl = "http://localhost/rest/" . $this->version . "/";
	}

	public function GetDevices() {
		return $this->MakeRequest("GET", "devices");

	}

	/**
	 * Forms & makes an API Request
	 */
	public function MakeRequest($method, $endpoint, $data = false) {
		// Declare the headers
		$headers = array();
		array_push($headers, 'Accepts: application/json');
		if ($method != 'GET') {
			array_push($headers, 'Content-Type: application/json');
		}

		// Initialize Curl
		$curl = curl_init();

		// Switch out the methods for communicating with the API
		switch ($method) {
			case "GET":
				if ($data) {
					$urlData = "&".http_build_query($data);
				}
				break;
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					array_push($headers, 'Content-Length: ' . strlen($data));
				}
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
				if ($data) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					array_push($headers, 'Content-Length: ' . strlen($data));
				}
				break;
			case "DELETE":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if ($data) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					array_push($headers, 'Content-Length: ' . strlen($data));
				}

				break;
		}

		// Generate the final endpoint url to be called
		$url = "{$this->apiUrl}{$endpoint}/?{$urlData}";

		// Set the curl options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_VERBOSE, false);
		curl_setopt($curl, CURLOPT_PORT, 8000);

		// Make the call
		$response = curl_exec($curl);
		//echo print_r(curl_getinfo($curl));

		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $headerSize);
		$body = substr($response, $headerSize);
		//echo $body;

		$curlInfo = curl_getinfo($curl);
		//echo print_r($curlInfo);

		// Close the connection
		curl_close($curl);

		// Convert to JSON object
		$responseJSON = json_decode($body);

		return $responseJSON;
	}


}

class Device {

	public $nbapi;

	function __construct() {

		$this->nbapi = new NBAPI();

	}

	/**
	 * Lists the devices associated
	 * @return object 
	 */
	public function getDevices() {
		return $this->nbapi->MakeRequest("GET", "devices");
	}

	/**
	 * Get a device metadata
	 * @param  string $guid The GUID of the device to actuate
	 * @return object       
	 */
	public function getMeta($guid) {
		return $this->nbapi->MakeRequest("GET", "device/{$guid}");
	}

	/**
	 * Actuates a device
	 * @param  string $guid The GUID of the device to actuate
	 * @param  object $da   The data object to pass to the device
	 * @return object       
	 */
	public function actuate($guid, $da) {
		return $this->nbapi->MakeRequest("PUT", "device/{$guid}", json_encode($da));
	}

	/**
	 * Subscribes a callback to the specified device
	 * @param  string $guid the GUID of the device to subscribe to
	 * @param  string $url  The url that will be called as a callback
	 * @return object       
	 */
	public function subscribe($guid, $url) {

		$urlObject = (object) array('url' => $url);
		return $this->nbapi->MakeRequest("POST", "device/{$guid}/callback", json_encode($urlObject));
	}

	/**
	 * Unsubscribes a callback from the specified device
	 * @param  string $guid The GUID of the device to subscribe to
	 * @return object       
	 */
	public function unsubscribe($guid) {
		return $this->nbapi->MakeRequest("DELETE", "device/{$guid}/callback");
	}

	public function data($guid, $from, $to) {

		$dataScope = (object) array('from' => $from, 'to' => $to);
		return $this->nbapi->MakeRequest("GET", "device/{$guid}/data", $dataScope);
	}

	public function lastHeartbeat($guid) {
		return $this->nbapi->MakeRequest("GET", "device/{$guid}/heartbeat");
	}
}	
