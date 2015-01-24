<?php

namespace DavidePastore\Ipinfo;

/**
 * ipinfo.io service wrapper.
 * @author davidepastore
 *
 */
class Ipinfo {
	
	/**
	 * The base url of the ipinfo service.
	 * @var string
	 */
	const BASE_URL = "http://ipinfo.io/";
	
	/**
	 * The ip string.
	 * @var string
	 */
	const IP = "ip";
	
	/**
	 * The hostname string.
	 * @var string
	 */
	const HOSTNAME = "hostname";
	
	/**
	 * The loc string.
	 * @var string
	 */
	const LOC = "loc";
	
	/**
	 * The org string.
	 * @var string
	 */
	const ORG = "org";
	
	/**
	 * The city string.
	 * @var string
	 */
	const CITY = "city";
	
	/**
	 * The region string.
	 * @var string
	 */
	const REGION = "region";
	
	/**
	 * The country string.
	 * @var string
	 */
	const COUNTRY = "country";
	
	/**
	 * The phone string.
	 * @var string
	 */
	const PHONE = "phone";
	
	/**
	 * The geo string.
	 * @var string
	 */
	const GEO = "geo";
    
    /**
	 * The postal string.
	 * @var string
	 */
	const POSTAL = "postal";
	
	/**
	 * All the settings
	 * @var array
	 */
	protected $settings;
	
	/**
	 * Create an Ipinfo instance.
	 * @param array $settings An array with all the settings.
	 * Supported keys are:
	 * - token: string the developer token;
	 * - debug: boolean active or not the debug.
	 */
	public function __construct($settings = array()){
		//Merge user settings
		$this->settings = array_merge(array(
				'token' => '',
				'debug' => false
		), $settings);
	}
	
	
	/**
	 * Get all the info about your own ip address.
	 * @return \DavidePastore\Ipinfo\Host The Host object with all the info.
	 */
	public function getYourOwnIpDetails(){
		$response = $this->makeCurlRequest($this::BASE_URL . "json");
		$response = json_decode($response, true);
		return new Host($response);
	}
	
	/**
	 * Get all the info about an ip address.
	 * @param string $ipAddress The ip address.
	 * @return \DavidePastore\Ipinfo\Host The Host object with all the info.
	 */
	public function getFullIpDetails($ipAddress){
		$response = $this->makeCurlRequest($this::BASE_URL . $ipAddress);
		$response = json_decode($response, true);
		return new Host($response);
	}
	
	/**
	 * Get a specific field value.
	 * @param string $ipAddress The ip address.
	 * @param string $field The field.
	 * @return string|\DavidePastore\Ipinfo\Host The value of the given field for the given ip.
	 * This could returns an Host object if you call it with for the field
	 * \DavidePastore\Ipinfo\Ipinfo::GEO.
	 */
	public function getSpecificField($ipAddress, $field){
		$response = $this->makeCurlRequest($this::BASE_URL . $ipAddress . "/" . $field);
		$response = $this->checkGeo($field, $response);
		return $response;
	}
	
	/**
	 * Get a specific field value of your own ip address.
	 * @param string $field The field.
	 * @return string|\DavidePastore\Ipinfo\Host The value of the given field for your own ip.
	 * This could returns an Host object if you call it with for the field
	 * \DavidePastore\Ipinfo\Ipinfo::GEO.
	 */
	public function getYourOwnIpSpecificField($field){
		$response = $this->makeCurlRequest($this::BASE_URL . $field);
		$response = $this->checkGeo($field, $response);
		return $response;
	}
	
	/**
	 * Use the /geo call to get just the geolocation information, which will often be 
	 * faster than getting the full response.
	 * 
	 * @param string $ipAddress The ip address.
	 * @return \DavidePastore\Ipinfo\Host
	 */
	public function getIpGeoDetails($ipAddress)
	{
		return $this->getSpecificField($ipAddress, $this::GEO);
	}
	
	/**
	 * Check if the response is GEO and set the parameters accordingly.
	 * @param string $field The field value.
	 * @param string $response The response from the server.
	 * @return Ambigous <\DavidePastore\Ipinfo\Host, string> Returns an Host object if the request is
	 * of the GEO type, a string otherwise. If the field value is different from the GEO type, it will
	 * delete the last character ('\n').
	 */
	private function checkGeo($field, $response){
		if($field == $this::GEO){
			$response = json_decode($response, true);
			$response = new Host($response);
		}
		else{
			$response = substr($response, 0, -1);
		}
		
		return $response;
	}
	
	/**
	 * Make a curl request.
	 * @param string $address The address of the request.
	 * @return string Returns the response from the request.
	 */
	private function makeCurlRequest($address){
		$curl = curl_init();
		
		if(!empty($this->settings['token'])){
			$address .= "?token=" . $this->settings['token'];
		}
		
		if($this->settings['debug']){
			echo "Request address: " . $address . "\n";
		}
		
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $address
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		return $response;
	}
}
