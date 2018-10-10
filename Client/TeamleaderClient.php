<?php
namespace SumoCoders\Teamleader\Client;

use GuzzleHttp\Exception\GuzzleException;

use League\OAuth2\Client\Provider\GenericProvider;

use GuzzleHttp\Client;
use SumoCoders\Teamleader\Exception;

class TeamleaderClient
{
    // internal constant to enable/disable debugging
    const DEBUG = true;

    // base endpoint
    const AUTH_URL = 'https://app.teamleader.eu';

    const API_URL = 'https://api.teamleader.eu';

    // port
    const API_PORT = 443;

    // current version
    const VERSION = '2.0.0';

    const GRANT_TYPES = ['code','authorization_code','refresh_token'];

    const AUTHORIZE_URL = self::AUTH_URL.'/oauth2/authorize';

    const TOKEN_URL = self::AUTH_URL.'/oauth2/access_token';
    /**
     * client id
     *
     * @var string
     */
    private $clientID;

    /**
     * client secret
     *
     * @var string
     */
    private $clientSecret;

    /**
     * username
     *
     * @var string
     */
    private $username;

    /**
     * password
     *
     * @var string
     */
    private $password;

    /**
     * code
     *
     * @var string
     */
    private $code;

    /**
     * tokenUrl
     *
     * @var string
     */
    private $redirectUrl;

    /**
     * provider
     *
     * @var League\OAuth2\Client\Provider\GenericProvider
     */
    private $provider;

    /**
     * token
     *
     * @var string
     */
    private $token;

    /**
     * refresh token
     *
     * @var string
     */

    private $refreshToken;

    /**
     * expired in
     *
     * @var string
     */
    private $expireDate;

    /**
     * Constructor
     *
     * @param int    $id   The department id
     * @param string $name The department name
     */
    public function __construct($clientID,$clientSecret,$username,$password,$redirectUrl,$token,$refreshToken,$expireDate)
    {
        $this->setClientID($clientID);
        $this->setClientSecret($clientSecret);
        $this->setPassword($password);
        $this->setUsername($username);
        $this->setRedirectUrl($redirectUrl);
        $this->setToken($token);
        $this->setRefreshToken($refreshToken);
        $this->setExpireDate($expireDate);

        $this->createProvider();
    }

    private function createProvider(){
        $this->provider = new GenericProvider([
            'clientId'                => $this->clientID,    // The client ID assigned to you by the provider
            'clientSecret'            => $this->clientSecret,   // The client password assigned to you by the provider
            'redirectUri'             => $this->redirectUrl,
            'urlAuthorize'            => self::AUTHORIZE_URL,
            'urlAccessToken'          => self::TOKEN_URL,
            'urlResourceOwnerDetails' => self::API_URL.'/users.me'
        ]);
    }


    private function getAuthorizeCode()
    {
        // Fetch the authorization URL from the provider; this returns the
        // urlAuthorize option and generates and applies any necessary parameters
        // (e.g. state).
        $authorizationUrl = $this->provider->getAuthorizationUrl();

        // Get the state generated for you and store it to the session.
        $_SESSION['oauth2state'] = $this->provider->getState();

        // Redirect the user to the authorization URL.
        header('Location: ' . $authorizationUrl);
        exit;
    }

    private function getAccessToken(){
        try {
            // Try to get an access token using the authorization code grant.
            $accessToken = $this->provider->getAccessToken(self::GRANT_TYPES[1], ['code' => $_GET['code']]);
            // We have an access token, which we may use in authenticated
            // requests against the service provider's API.
            $this->setToken($accessToken->getToken());
            $this->setRefreshToken($accessToken->getRefreshToken());
            $this->setExpireDate($accessToken->getExpires());

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token or user details.
            exit($e->getMessage());
        }

        return $accessToken;
    }

    private function resetToken(){
        try {
            // Try to get an access token using the authorization code grant.
            $accessToken = $this->provider->getAccessToken(self::GRANT_TYPES[2], ['refresh_token' => $this->getRefreshToken(), 'access_token' => $this->getToken()]);
            // We have an access token, which we may use in authenticated
            // requests against the service provider's API.
            $this->setToken($accessToken->getToken());
            $this->setRefreshToken($accessToken->getRefreshToken());
            $this->setExpireDate($accessToken->getExpires());

        } catch (\Exception $e) {
            // Failed to get the access token or user details.
            exit($e->getMessage());
        }

        return $accessToken;
    }

    public function doCall($endpoint,$options = array(),$method = 'GET'){
        // If we don't have an authorization code then get one
        if (!isset($_GET['code']) && $this->expireDate == null) {
            $this->getAuthorizeCode();
        } else {
            // Check if token is expired
            if(isset($_GET['code']) && $this->expireDate == null) {
                $this->getAccessToken();
            }elseif(strtotime(date('Y-m-d H:m:s')) > $this->expireDate) {
                $this->resetToken();
            }

            // The provider provides a way to get an authenticated API request for
            // the service, using the access token; it returns an object conforming
            // to Psr\Http\Message\RequestInterface.
            $client = new Client(['base_uri'=>self::API_URL]);
            $headers = [
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Accept'        => 'application/json',
            ];
            try {
                $res = $client->request($method, $endpoint, ['headers' => $headers,'form_params' => $options]);
            } catch (GuzzleException $e) {
                die($e->getMessage());
            }
            if($res->getStatusCode() == 200){
                return $res->getBody()->getContents();
            } else {
                //TODO fault handle
            }
        }
    }

    public function doCallV1($endpoint,$options = array(),$method = 'GET'){
        // If we don't have an authorization code then get one
        if (!isset($_GET['code']) && $this->expireDate == null) {
            $this->getAuthorizeCode();
        } else {
            // Check if token is expired
            if(isset($_GET['code']) && $this->expireDate == null) {
                $this->getAccessToken();
            }elseif(strtotime(date('Y-m-d H:m:s')) > $this->expireDate) {
                $this->resetToken();
            }

            // The provider provides a way to get an authenticated API request for
            // the service, using the access token; it returns an object conforming
            // to Psr\Http\Message\RequestInterface.
            $client = new Client(['base_uri'=>self::AUTH_URL]);
            $headers = [
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Accept'        => 'application/json',
            ];
            try {
                $res = $client->request($method, $endpoint, ['headers' => $headers,'form_params' => $options]);
            } catch (GuzzleException $e) {
                die($e->getMessage());
            }
            if($res->getStatusCode() == 200){
                return $res->getBody()->getContents();
            } else {
                //TODO fault handle
            }
        }
    }

    /**
     * Get client secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientID()
    {
        return $this->clientID;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set client id
     *
     * @param string $clientID
     */
    public function setClientID($clientID)
    {
        $this->clientID = (string) $clientID;
    }

    /**
     * Set client secret
     *
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = (string) $clientSecret;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username){
        $this->username = (string) $username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password){
        $this->password = (string) $password;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = (string) $code;
    }

    /**
     * Set redirect url
     *
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl){
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Set Token
     *
     * @param string $token
     */
    public function setToken($token){
        $this->token = $token;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getToken(){
        return $this->token;
    }

    /**
     * Set Refresh token
     *
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken){
        $this->refreshToken = $refreshToken;
    }

    /**
     * Get Refresh token
     *
     * @return string
     */
    public function getRefreshToken(){
        return $this->refreshToken;
    }

    /**
     * Set Expire date
     *
     * @param string $expireDate
     */
    public function setExpireDate($expireDate){
        $this->expireDate = $expireDate;
    }

    /**
     * Get Expire date
     *
     * @return string
     */
    public function getExpireDate(){
        return $this->expireDate;
    }
}