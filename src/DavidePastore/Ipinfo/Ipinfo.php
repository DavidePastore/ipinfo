<?php

namespace DavidePastore\Ipinfo;

use DavidePastore\Ipinfo\Exception\InvalidTokenException;
use DavidePastore\Ipinfo\Exception\IpInfoException;
use DavidePastore\Ipinfo\Exception\RateLimitExceedException;
use DavidePastore\Ipinfo\Exception\WrongIpException;

/**
 * ipinfo.io service wrapper.
 *
 * @author davidepastore
 */
class Ipinfo
{
    /**
     * The base url of the ipinfo service.
     *
     * @var string
     */
    const BASE_URL = 'https://ipinfo.io/';

    /**
     * The ip string.
     *
     * @var string
     */
    const IP = 'ip';

    /**
     * The hostname string.
     *
     * @var string
     */
    const HOSTNAME = 'hostname';

    /**
     * The loc string.
     *
     * @var string
     */
    const LOC = 'loc';

    /**
     * The org string.
     *
     * @var string
     */
    const ORG = 'org';

    /**
     * The city string.
     *
     * @var string
     */
    const CITY = 'city';

    /**
     * The region string.
     *
     * @var string
     */
    const REGION = 'region';

    /**
     * The country string.
     *
     * @var string
     */
    const COUNTRY = 'country';

    /**
     * The phone string.
     *
     * @var string
     */
    const PHONE = 'phone';

    /**
     * The geo string.
     *
     * @var string
     */
    const GEO = 'geo';

    /**
     * The postal string.
     *
     * @var string
     */
    const POSTAL = 'postal';

    /**
     * All the settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * Create an Ipinfo instance.
     *
     * @param array $settings An array with all the settings.
     *                        Supported keys are:
     *                        - token: string the developer token;
     *                        - debug: boolean active or not the debug.
     */
    public function __construct($settings = array())
    {
        //Merge user settings
        $this->settings = array_merge(array(
                'token' => '',
                'debug' => false,
                'curlOptions' => array()
        ), $settings);
    }

    /**
     * Get all the info about your own ip address.
     *
     * @return \DavidePastore\Ipinfo\Host The Host object with all the info.
     * @throws InvalidTokenException
     * @throws RateLimitExceedException
     * @throws WrongIpException
     */
    public function getYourOwnIpDetails()
    {
        $response = $this->makeCurlRequest($this::BASE_URL.'json');
        $response = $this->jsonDecodeResponse($response);

        return new Host($response);
    }

    /**
     * Get all the info about an ip address.
     *
     * @param string $ipAddress The ip address.
     *
     * @return \DavidePastore\Ipinfo\Host The Host object with all the info.
     * @throws InvalidTokenException
     * @throws RateLimitExceedException
     * @throws WrongIpException
     */
    public function getFullIpDetails($ipAddress)
    {
        $response = $this->makeCurlRequest($this::BASE_URL.$ipAddress);
        $response = $this->jsonDecodeResponse($response);

        return new Host($response);
    }

    /**
     * Get a specific field value.
     *
     * @param string $ipAddress The ip address.
     * @param string $field The field.
     *
     * @return string|\DavidePastore\Ipinfo\Host The value of the given field for the given ip.
     *                                           This could returns an Host object if you call it with for the field
     *                                           \DavidePastore\Ipinfo\Ipinfo::GEO.
     * @throws InvalidTokenException
     * @throws RateLimitExceedException
     * @throws WrongIpException
     */
    public function getSpecificField($ipAddress, $field)
    {
        $response = $this->makeCurlRequest($this::BASE_URL.$ipAddress.'/'.$field);
        $response = $this->checkGeo($field, $response);

        return $response;
    }

    /**
     * Get a specific field value of your own ip address.
     *
     * @param string $field The field.
     *
     * @return string|\DavidePastore\Ipinfo\Host The value of the given field for your own ip.
     *                                           This could returns an Host object if you call it with for the field
     *                                           \DavidePastore\Ipinfo\Ipinfo::GEO.
     * @throws InvalidTokenException
     * @throws RateLimitExceedException
     * @throws WrongIpException
     */
    public function getYourOwnIpSpecificField($field)
    {
        $response = $this->makeCurlRequest($this::BASE_URL.$field);
        $response = $this->checkGeo($field, $response);

        return $response;
    }

    /**
     * Use the /geo call to get just the geolocation information, which will often be
     * faster than getting the full response.
     *
     * @param string $ipAddress The ip address.
     *
     * @return \DavidePastore\Ipinfo\Host
     * @throws InvalidTokenException
     * @throws RateLimitExceedException
     * @throws WrongIpException
     */
    public function getIpGeoDetails($ipAddress)
    {
        return $this->getSpecificField($ipAddress, $this::GEO);
    }

    /**
     * Check if the response is GEO and set the parameters accordingly.
     *
     * @param string $field The field value.
     * @param string $response The response from the server.
     *
     * @return \DavidePastore\Ipinfo\Host|string Returns an Host object if the request is
     *                  of the GEO type, a string otherwise. If the field value is different from the GEO type, it will
     *                  delete the last character ('\n').
     * @throws InvalidTokenException
     * @throws RateLimitExceedException
     * @throws WrongIpException
     */
    private function checkGeo($field, $response)
    {
        if ($field == $this::GEO) {
            $response = $this->jsonDecodeResponse($response);
            $response = new Host($response);
        } else {
            $this->checkErrors($response);
            $response = substr($response, 0, -1);
        }

        return $response;
    }

    /**
     * Make a curl request.
     *
     * @param string $address The address of the request.
     *
     * @return string Returns the response from the request.
     */
    private function makeCurlRequest($address)
    {
        $curl = curl_init();

        if (!empty($this->settings['token'])) {
            $address .= '?token='.$this->settings['token'];
        }

        if ($this->settings['debug']) {
            echo 'Request address: '.$address."\n";
        }

        curl_setopt_array(
            $curl,
            array_replace(
                $this->settings['curlOptions'],
                array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $address
                )
            )
        );

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $errorMessage = curl_error($curl);

            if ($this->settings['debug']) {
                echo "The error is".$errorMessage;
            }
        }

        if (isset($errorMessage)) {
            throw new IpInfoException('cURL error', $errorMessage);
        }

        curl_close($curl);

        return $response;
    }

    /**
     * Returns the json decoded associative array.
     * @param  string $response Response from the http call.
     * @return array           Returns the associative array with the response.
     * @throws RateLimitExceedException    If you exceed the rate limit.
     * @throws InvalidTokenException If the used token is invalid.
     * @throws WrongIpException If the used token is invalid.
     */
    private function jsonDecodeResponse($response)
    {
        $output = array();
        if ($response) {
            // Check if the response contains an error message
            $this->checkErrors($response);
            $output = json_decode($response, true);
            if ($output === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new IpInfoException("Wrong response", $response);
            }
        }
        return $output;
    }

    /**
     * Check if the given response has some kind of errors.
     * @param string $response The response to check.
     * @throws RateLimitExceedException    If you exceed the rate limit.
     * @throws InvalidTokenException If the used token is invalid.
     * @throws WrongIpException If the used token is invalid.
     */
    private function checkErrors($response)
    {
        if (strpos($response, 'Rate limit exceeded.') !== false) {
            throw new RateLimitExceedException("You exceed the rate limit.", $response);
        } elseif (strpos($response, 'Unknown token') !== false) {
            throw new InvalidTokenException("The used token is invalid.", $response);
        } elseif (strpos($response, 'Wrong ip') !== false) {
            throw new WrongIpException("The used IP address is not valid.", $response);
        }
    }
}
