<?php

namespace SumoCoders\Teamleader;

use SumoCoders\Teamleader\Exception;

/**
 * Teamleader class
 *
 * @author         Tijs Verkoyen <php-teamleader@sumocoders.be>
 * @version        1.0.0
 * @copyright      Copyright (c) SumoCoders. All rights reserved.
 * @license        BSD License
 */
class Teamleader
{
    // internal constant to enable/disable debugging
    const DEBUG = true;

    // base endpoint
    const API_URL = 'https://www.teamleader.be/api';

    // port
    const API_PORT = 443;

    // current version
    const VERSION = '1.0.0';

    /**
     * The apiGroup to use
     *
     * @var string
     */
    private $apiGroup;

    /**
     * The apiKey to use
     *
     * @var string
     */
    private $apiSecret;

    /**
     * The timeout
     *
     * @var int
     */
    private $timeOut = 60;

    /**
     * The user agent
     *
     * @var string
     */
    private $userAgent;

    // class methods
    /**
     * Create an instance
     *
     * @param string $apiGroup  The apiGroup to use.
     * @param string $apiSecret The apiKey to use.
     */
    public function __construct($apiGroup, $apiSecret)
    {
        $this->setApiGroup($apiGroup);
        $this->setApiSecret($apiSecret);
    }

    /**
     * Get the timeout that will be used
     *
     * @return int
     */
    public function getTimeOut()
    {
        return (int) $this->timeOut;
    }

    /**
     * Get the apiGroup
     *
     * @return string
     */
    public function getApiGroup()
    {
        return $this->apiGroup;
    }

    /**
     * Get the apiKey
     *
     * @return string
     */
    public function getApiSecret()
    {
        return (string) $this->apiSecret;
    }

    /**
     * Get the useragent that will be used.
     * Our version will be prepended to yours.
     * It will look like: "PHP Teamleader/<version> <your-user-agent>"
     *
     * @return string
     */
    public function getUserAgent()
    {
        return (string) 'PHP Teamleader/' . self::VERSION . ' ' . $this->userAgent;
    }

    /**
     * Set the timeout
     * After this time the request will stop.
     * You should handle any errors triggered by this.
     *
     * @param $seconds int timeout in seconds.
     */
    public function setTimeOut($seconds)
    {
        $this->timeOut = (int) $seconds;
    }

    /**
     * Set the apiGroup
     *
     * @param string $apiGroup
     */
    public function setApiGroup($apiGroup)
    {
        $this->apiGroup = $apiGroup;
    }

    /**
     * Set the apiKey
     *
     * @param string $token
     */
    public function setApiSecret($token)
    {
        $this->apiSecret = (string) $token;
    }

    /**
     * Set the user-agent for you application
     * It will be appended to ours, the result will look like:
     * "PHP Teamleader/<version> <your-user-agent>"
     *
     * @param $userAgent string user-agent, it should look like
     *                   <app-name>/<app-version>.
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = (string) $userAgent;
    }

    /**
     * Make the call
     *
     * @param string $endPoint The endpoint.
     * @param array  $fields   The fields that should be passed.
     * @return array
     */
    private function doCall($endPoint, array $fields = null)
    {
        // add credentials
        $fields['api_group'] = $this->getApiGroup();
        $fields['api_secret'] = $this->getApiSecret();

        $option = array();
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $fields;

        // prepend
        $url = self::API_URL . '/' . $endPoint;

        // set options
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_PORT] = self::API_PORT;
        $options[CURLOPT_USERAGENT] = $this->getUserAgent();
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_TIMEOUT] = (int) $this->getTimeOut();

        // init
        $curl = curl_init();

        // set options
        curl_setopt_array($curl, $options);

        // execute
        $response = curl_exec($curl);
        $headers = curl_getinfo($curl);

        // fetch errors
        $errorNumber = curl_errno($curl);
        $errorMessage = curl_error($curl);

        // error?
        if ($errorNumber != '') {
            throw new Exception($errorMessage, $errorNumber);
        }

        // we expect JSON so decode it
        $json = @json_decode($response, true);

        // validate json
        if ($json === false) {
            throw new Exception('Invalid JSON-response');
        }

        // return
        return $json;
    }

    /**
     * Just a simple Hello World call
     *
     * @return string
     */
    public function helloWorld()
    {
        return $this->doCall('helloWorld.php');
    }
}
