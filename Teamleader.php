<?php

namespace SumoCoders\Teamleader;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Opportunities\Sale;

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
     * @return mixed
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

        // try to detect errors
        if ($json === null && $response != '') {
            throw new Exception($response);
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

    // CRM methods

    /**
     * Add a contact
     *
     * @param Contact    $contact
     * @param null|array $tagsToAdd        Pass one or more tags. Existing tags
     *                                     will be reused, other tags will be
     *                                     automatically created for you and
     *                                     added to the contact.
     * @param bool       $newsletter
     * @param bool       $autoMergeByName  If true, Teamleader will merge this
     *                                     info into an existing contact with
     *                                     the same forename and surname, if it
     *                                     finds any.
     * @param bool       $autoMergeByEmail If true, Teamleader will merge this
     *                                     info into an existing contact with
     *                                     the same email address, if it finds
     *                                     any.
     * @return int
     */
    public function crmAddContact(
        Contact $contact,
        array $tagsToAdd = null,
        $newsletter = false,
        $autoMergeByName = false,
        $autoMergeByEmail = false
    ) {
        $fields = $contact->toArrayForApi();
        if ($tagsToAdd) {
            $fields['add_tag_by_string'] = implode(',', $tagsToAdd);
        }
        if ($newsletter) {
            $fields['newsletter'] = 1;
        }
        if ($autoMergeByName) {
            $fields['automerge_by_name'] = 1;
        }
        if ($autoMergeByEmail) {
            $fields['automerge_by_email'] = 1;
        }

        return $this->doCall('addContact.php', $fields);
    }

    /**
     * Update a contact
     *
     * @todo    find a way to update the tags as the api expects
     *
     * @param Contact    $contact
     * @param bool       $trackChanges  If true, all changes are logged and
     *                                  visible to users in the web-interface.
     * @param null|array $tagsToAdd     Pass one or more tags. Existing tags
     *                                  will be reused, other tags will be
     *                                  automatically created for you and added
     *                                  to the contact.
     * @param null|array $tagsToRemove  Pass one or more tags. These tags will
     *                                  be removed from the contact.
     * @return bool
     */
    public function crmUpdateContact(
        Contact $contact,
        $trackChanges = true,
        array $tagsToAdd = null,
        array $tagsToRemove = null
    ) {
        $fields = $contact->toArrayForApi();
        $fields['contact_id'] = $contact->getId();
        $fields['track_changes'] = ($trackChanges) ? 1 : 0;
        if ($tagsToAdd) {
            $fields['add_tag_by_string'] = implode(',', $tagsToAdd);
        }
        if ($tagsToRemove) {
            $fields['remove_tag_by_string'] = implode(',', $tagsToRemove);
        }

        $rawData = $this->doCall('updateContact.php', $fields);

        return ($rawData == 'OK');
    }

    /**
     * Search for contacts
     *
     * @param int         $amount        The amount of contacts returned per
     *                                   request (1-100)
     * @param int         $page          The current page (first page is 0)
     * @param string|null $searchBy      A search string. Teamleader will try
     *                                   to match each part of the string to
     *                                   the forename, surname, company name
     *                                   and email address.
     * @param int|null    $modifiedSince Teamleader will only return contacts
     *                                   that have been added or modified
     *                                   since that timestamp.
     * @return array of Contact
     */
    public function crmGetContacts($amount = 100, $page = 0, $searchBy = null, $modifiedSince = null)
    {
        $fields = array();
        $fields['amount'] = (int) $amount;
        $fields['pageno'] = (int) $page;

        if ($searchBy !== null) {
            $fields['searchby'] = (string) $searchBy;
        }
        if ($modifiedSince !== null) {
            $fields['modifiedsince'] = (int) $modifiedSince;
        }

        $rawData = $this->doCall('getContacts.php', $fields);
        $return = array();

        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                $return[] = Contact::initializeWithRawData($row);
            }
        }

        return $return;
    }

    /**
     * Fetch information about a contact
     *
     * @param int $id The ID of the contact
     * @return Contact
     */
    public function crmGetContact($id)
    {
        $fields = array();
        $fields['contact_id'] = (int) $id;

        $rawData = $this->doCall('getContact.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Contact::initializeWithRawData($rawData);
    }

    /**
     * Add a contact
     *
     * @param Company    $company
     * @param null|array $tagsToAdd          Pass one or more tags. Existing
     *                                       tags will be reused, other tags
     *                                       will be automatically created for
     *                                       you and added to the contact.
     * @param bool       $autoMergeByName    If true, Teamleader will merge
     *                                       this info into an existing
     *                                       company with the same name, if it
     *                                       finds any.
     * @param bool       $autoMergeByEmail   If true, Teamleader will merge
     *                                       this info into an existing company
     *                                       with the same email address, if it
     *                                       finds any.
     * @param bool       $autoMergeByVatCode If true, Teamleader will merge
     *                                       this info into an existing company
     *                                       with the same VAT code, if it
     *                                       finds any.
     * @return int
     */
    public function crmAddCompany(
        Company $company,
        array $tagsToAdd = null,
        $autoMergeByName = false,
        $autoMergeByEmail = false,
        $autoMergeByVatCode = false
    ) {
        $fields = $company->toArrayForApi();
        if ($tagsToAdd) {
            $fields['add_tag_by_string'] = implode(',', $tagsToAdd);
        }
        if ($autoMergeByName) {
            $fields['automerge_by_name'] = 1;
        }
        if ($autoMergeByEmail) {
            $fields['automerge_by_email'] = 1;
        }
        if ($autoMergeByVatCode) {
            $fields['automerge_by_vat_code'] = 1;
        }

        return $this->doCall('addCompany.php', $fields);
    }

    /**
     * Update a company
     *
     * @todo    find a way to update the tags as the api expects
     *
     * @param Company    $company
     * @param bool       $trackChanges  If true, all changes are logged and
     *                                  visible to users in the web-interface.
     * @param null|array $tagsToAdd     Pass one or more tags. Existing tags
     *                                  will be reused, other tags will be
     *                                  automatically created for you and added
     *                                  to the contact.
     * @param null|array $tagsToRemove  Pass one or more tags. These tags will
     *                                  be removed from the contact.
     * @return bool
     */
    public function crmUpdateCompany(
        Company $company,
        $trackChanges = true,
        array $tagsToAdd = null,
        array $tagsToRemove = null
    ) {
        $fields = $company->toArrayForApi();
        $fields['company_id'] = $company->getId();
        $fields['track_changes'] = ($trackChanges) ? 1 : 0;
        if ($tagsToAdd) {
            $fields['add_tag_by_string'] = implode(',', $tagsToAdd);
        }
        if ($tagsToRemove) {
            $fields['remove_tag_by_string'] = implode(',', $tagsToRemove);
        }

        $rawData = $this->doCall('updateCompany.php', $fields);

        return ($rawData == 'OK');
    }

    /**
     * Search for companies
     *
     * @param int         $amount        The amount of companies returned per
     *                                   request (1-100)
     * @param int         $page          The current page (first page is 0)
     * @param string|null $searchBy      A search string. Teamleader will try
     *                                   to match each part of the string to
     *                                   the company name
     *                                   and email address.
     * @param int|null    $modifiedSince Teamleader will only return companies
     *                                   that have been added or modified
     *                                   since that timestamp.
     * @return array of Company
     */
    public function crmGetCompanies($amount = 100, $page = 0, $searchBy = null, $modifiedSince = null)
    {
        $fields = array();
        $fields['amount'] = (int) $amount;
        $fields['pageno'] = (int) $page;

        if ($searchBy !== null) {
            $fields['searchby'] = (string) $searchBy;
        }
        if ($modifiedSince !== null) {
            $fields['modifiedsince'] = (int) $modifiedSince;
        }

        $rawData = $this->doCall('getCompanies.php', $fields);
        $return = array();

        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                $return[] = Company::initializeWithRawData($row);
            }
        }

        return $return;
    }

    /**
     * Fetch information about a company
     *
     * @param int $id The ID of the company
     * @return Contact
     */
    public function crmGetCompany($id)
    {
        $fields = array();
        $fields['company_id'] = (int) $id;

        $rawData = $this->doCall('getCompany.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Company::initializeWithRawData($rawData);
    }

    /**
     * Adds an opportunity
     *
     * @param Sale $sale
     * @return int
     */
    public function opportunitiesAddSale(Sale $sale)
    {
        $fields = $sale->toArrayForApi();

        return $this->doCall('addSale.php', $fields);
    }
}
