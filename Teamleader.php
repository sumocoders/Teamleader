<?php

namespace SumoCoders\Teamleader;

use SumoCoders\Teamleader\Calls\Call;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Crm\Relationship;
use SumoCoders\Teamleader\Invoices\Invoice;
use SumoCoders\Teamleader\Invoices\Creditnote;
use SumoCoders\Teamleader\Meetings\Meeting;
use SumoCoders\Teamleader\Meetings\MeetingContactAttendee;
use SumoCoders\Teamleader\Subscriptions\Subscription;
use SumoCoders\Teamleader\Deals\Deal;
use SumoCoders\Teamleader\Departments\Department;
use SumoCoders\Teamleader\Users\User;
use SumoCoders\Teamleader\Notes\Note;
use SumoCoders\Teamleader\Products\Product;
use \SumoCoders\Teamleader\CustomFields\CustomField;

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
    const DEBUG = false;

    // base endpoint
    const API_URL = 'https://app.teamleader.eu/api';

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
     * if ssl is enabled
     *
     * @var boolean
     */
    private $sslEnabled;

    /**
     * The timeout
     *
     * @var int
     */
    private $timeOut = 30;

    /**
     * The user agent
     *
     * @var string
     */
    private $userAgent;

    /**
     * Create an instance
     *
     * @param string $apiGroup  The apiGroup to use.
     * @param string $apiSecret The apiKey to use.
     */
    public function __construct($apiGroup, $apiSecret, $sslEnabled = true)
    {
        $this->setApiGroup($apiGroup);
        $this->setApiSecret($apiSecret);
        $this->setSslEnabled($sslEnabled);
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

    public function getTitle()
    {
        return $this->title;
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
     * Get if ssl is enabled
     *
     * @return boolean
     */
    public function getSslEnabled()
    {
        return $this->sslEnabled;
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
     * Set if ssl is enabled
     *
     * @param string $enabled
     */
    public function setSslEnabled($enabled)
    {
        $this->sslEnabled = (boolean) $enabled;
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
     * @param  string $endPoint The endpoint.
     * @param  array  $fields   The fields that should be passed.
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
        if (!$this->getSslEnabled()) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }
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

        // in case we received an error 400 Bad Request an exception should be thrown
        if ($headers['http_code'] == 400) {
            // attempt to extract a reason to show in the exception
            $json = @json_decode($response, true);
            if ($json !== false && isset($json['reason'])) {
                throw new Exception('Teamleader '.$endPoint.' API returned statuscode 400 Bad Request. Reason: '.$json['reason']);
            } else {
                // in case no JSON could be parsed, log the response in the exception
                throw new Exception('Teamleader '.$endPoint.' API returned statuscode 400 Bad Request. Data returned: '.$response);
            }
        }

        // in case we received an error 505 API rate limit reached an exception should be thrown
        if ($headers['http_code'] == 505) {
            throw new Exception('Teamleader '.$endPoint.' API returned statuscode 505 API rate limit reached.');
        }

        if (
            $endPoint === 'downloadInvoicePDF.php' ||
            $endPoint === 'addContactToMeeting.php'
        ) {
            return $response;
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

    /**
     * Fetch departments
     *
     * @return Department[] An array of departments
     */
    public function getDepartments()
    {
        $fields = array();

        $rawData = $this->doCall('getDepartments.php', $fields);

        $departments = array_map(
            function ($department) {
                return Department::initializeWithRawData($department);
            },
            $rawData
        );

        return $departments;
    }

    /**
     * Fetch users
     *
     * @return User[] An array of users
     */
    public function getUsers()
    {
        $fields = array();

        $rawData = $this->doCall('getUsers.php', $fields);

        $users = array_map(
            function ($user) {
                return User::initializeWithRawData($user);
            },
            $rawData
        );

        return $users;
    }

    // CRM methods

    /**
     * Add a contact
     *
     * @param Contact    $contact
     * @param null|array $tagsToAdd Pass one or more tags. Existing tags
     *                                     will be reused, other tags will be
     *                                     automatically created for you and
     *                                     added to the contact.
     * @param bool $newsletter
     * @param bool $autoMergeByName If true, Teamleader will merge this
     *                                     info into an existing contact with
     *                                     the same forename and surname, if it
     *                                     finds any.
     * @param bool $autoMergeByEmail If true, Teamleader will merge this
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

        $id = $this->doCall('addContact.php', $fields);
        $contact->setId($id);

        return $id;
    }

    /**
     * Update a contact
     *
     * @todo    find a way to update the tags as the api expects
     *
     * @param Contact    $contact
     * @param bool       $trackChanges If true, all changes are logged and
     *                                  visible to users in the web-interface.
     * @param null|array $tagsToAdd Pass one or more tags. Existing tags
     *                                  will be reused, other tags will be
     *                                  automatically created for you and added
     *                                  to the contact.
     * @param null|array $tagsToRemove Pass one or more tags. These tags will
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
     * Delete a contact
     *
     * @param int|Contact $contact	can be either an object of type "Contact" or a contact ID
     * @return bool
     */
    public function crmDeleteContact(
        $contact
    ) {
        if ($contact instanceof Contact) {
            $fields = $contact->toArrayForApi();
            $fields['contact_id'] = $contact->getId();
        } else {
            $fields['contact_id'] = (int) $contact;
        }
        $rawData = $this->doCall('deleteContact.php', $fields);

        return (isset($rawData["status"]) && $rawData["status"] === "success");
    }

    /**
     * Search for contacts
     *
     * @param int $amount The amount of contacts returned per
     *                                   request (1-100)
     * @param int         $page     The current page (first page is 0)
     * @param string|null $searchBy A search string. Teamleader will try
     *                                   to match each part of the string to
     *                                   the forename, surname, company name
     *                                   and email address.
     * @param int|null $modifiedSince Teamleader will only return contacts
     *                                   that have been added or modified
     *                                   since that timestamp.
     * @param array|null $customFields An array containig the custom field
     *                                   id's to be included in the result
     * @return Contact[]
     */
    public function crmGetContacts($amount = 100, $page = 0, $searchBy = null, $modifiedSince = null, array $customFields = null)
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
        if ($customFields !== null) {
            $fields['selected_customfields'] = implode(',', $customFields);
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
     * Fetch contacts related to a company
     *
     * @param  int     $id The ID of the company
     * @return Contact[]   An array of contacts related to the company
     */
    public function crmGetContactsByCompany($id)
    {
        $fields = array();
        $fields['company_id'] = (int) $id;

        $rawData = $this->doCall('getContactsByCompany.php', $fields);

        return $rawData;
    }

    /**
     * Fetch information about a contact
     *
     * @param  int     $id The ID of the contact
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
     * Search for relationships between contacts and companies.
     *
     * @param int $amount The amount of relationships returned per
     *                                   request (1-100)
     * @param int         $page     The current page (first page is 0)
     * @return Relationship[]
     */
    public function crmGetRelationships($amount = 100, $page = 0)
    {
        $fields = array();
        $fields['amount'] = (int) $amount;
        $fields['pageno'] = (int) $page;

        $rawData = $this->doCall('getContactCompanyRelations.php', $fields);

        $return = array();

        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                $return[] = Relationship::initializeWithRawData($row);
            }
        }

        return $return;
    }

    /**
     * Add a company
     *
     * @param Company    $company
     * @param null|array $tagsToAdd Pass one or more tags. Existing
     *                                       tags will be reused, other tags
     *                                       will be automatically created for
     *                                       you and added to the contact.
     * @param bool $autoMergeByName If true, Teamleader will merge
     *                                       this info into an existing
     *                                       company with the same name, if it
     *                                       finds any.
     * @param bool $autoMergeByEmail If true, Teamleader will merge
     *                                       this info into an existing company
     *                                       with the same email address, if it
     *                                       finds any.
     * @param bool $autoMergeByVatCode If true, Teamleader will merge
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

        $id = $this->doCall('addCompany.php', $fields);
        $company->setId($id);

        return $id;
    }

    /**
     * Update a company
     *
     * @todo    find a way to update the tags as the api expects
     *
     * @param Company $company
     * @param bool    $trackChanges If true, all changes are logged and
     *                                  visible to users in the web-interface.
     * @param null|array $tagsToAdd Pass one or more tags. Existing tags
     *                                  will be reused, other tags will be
     *                                  automatically created for you and added
     *                                  to the contact.
     * @param null|array $tagsToRemove Pass one or more tags. These tags will
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
     * Delete a company
     *
     * @param int|Company $company	can be either an object of type "Company" or a company Id
     * @return bool
     */
    public function crmDeleteCompany(
        $company
    ) {
        if ($company instanceof Company) {
            $fields = $company->toArrayForApi();
            $fields['company_id'] = $company->getId();
        } else {
            $fields['company_id'] = (int) $company;
        }
        $rawData = $this->doCall('deleteCompany.php', $fields);

        return (isset($rawData["status"]) && $rawData["status"] === "success");
    }

    /**
     * Search for companies
     *
     * @param int $amount The amount of companies returned per
     *                                   request (1-100)
     * @param int         $page     The current page (first page is 0)
     * @param string|null $searchBy A search string. Teamleader will try
     *                                   to match each part of the string to
     *                                   the company name
     *                                   and email address.
     * @param int|null $modifiedSince Teamleader will only return companies
     *                                   that have been added or modified
     *                                   since that timestamp.
     * @param string|null $filterByTag Teamleader will only return companies with this tag.
     * @param array|null $customFields An array containig the custom field
     *                                   id's to be included in the result
     * @param null $segmentId The ID of a segment created for companies. Teamleader will only return companies that
     *                        have been filtered out by the segment settings.
     * @return Company[]
     * @throws Exception
     */
    public function crmGetCompanies(
        $amount = 100,
        $page = 0,
        $searchBy = null,
        $modifiedSince = null,
        $filterByTag = null,
        array $customFields = null,
        $segmentId = null
    ) {
        $fields = array();
        $fields['amount'] = (int) $amount;
        $fields['pageno'] = (int) $page;

        if ($searchBy !== null) {
            $fields['searchby'] = (string) $searchBy;
        }
        if ($modifiedSince !== null) {
            $fields['modifiedsince'] = (int) $modifiedSince;
        }
        if ($filterByTag !== null) {
            $fields['filter_by_tag'] = (string) $filterByTag;
        }
        if ($customFields !== null) {
            $fields['selected_customfields'] = implode(',', $customFields);
        }
        if ($segmentId !== null) {
            $fields['segment_id'] = (int) $segmentId;
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
     * @param  int $id The ID of the company
     * @return Company
     * @throws Exception
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
     * @param Contact $contact
     * @param Company $company
     * @param string $mode
     * @param null|string $function
     * @return mixed
     */
    public function crmLinkContactToCompany(Contact $contact, Company $company, $mode = 'link', $function = null)
    {
        $fields = array();
        $fields['contact_id'] = $contact->getId();
        $fields['company_id'] = $company->getId();
        $fields['mode'] = $mode;
        if ($function) {
            $fields['function'] = $function;
        }

        return $this->doCall('linkContactToCompany.php', $fields);
    }

    /**
     * Get all existing customers
     *
     * @return array
     */
    public function crmGetAllCustomers()
    {
        $customers = array();

        $customers['contacts'] = array();
        $i = 0;
        while ($i == 0 || (sizeof($customers['contacts']) != 0 && sizeof($customers['contacts']) % 100 == 0)) {
            $contacts = $this->crmGetContacts(100, $i);
            if (empty($contacts)) {
                break;
            }
            foreach ($contacts as $contact) {
                $customers['contacts'][$contact->getId()] = $contact;
            }
            $i++;
        }

        $customers['companies'] = array();
        $i = 0;
        while ($i == 0 || (sizeof($customers['companies']) != 0 && sizeof($customers['companies']) % 100 == 0)) {
            $companies = $this->crmGetCompanies(100, $i);
            if (empty($companies)) {
                break;
            }
            foreach ($companies as $company) {
                $customers['companies'][$company->getId()] = $company;
            }
            $i++;
        }

        return $customers;
    }

    /**
     * Get all Custom fields by type: contact, company, sale, project, invoice, ticket, milestone, todo
     *
     * @return CustomField[]
     */
    public function crmGetAllCustomFields()
    {
        $custom_fields = array();
        $types = array('contact', 'company', 'sale', 'project', 'invoice', 'ticket', 'milestone', 'todo', 'product');

        foreach ($types as $for) {
            $custom_fields[$for] = $this->crmGetCustomField($for);
        }

        return $custom_fields;
    }

     /**
     * Fetch information about custom field
     *
     * @param  string   $for custom field type
     * @return CustomField
     */
    public function crmGetCustomField($for)
    {
        $for_custom = array();
        $for_custom['for'] = $for;
        $rawData = $this->doCall('getCustomFields.php', $for_custom);

        $return = array();

        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                $return[] = CustomField::initializeWithRawData($row);
            }
        }
        return $return;
    }

    /**
     * @param $id
     * @return Deal
     */
    public function dealsGetDeal($id)
    {
        $fields = array();
        $fields['deal_id'] = (int) $id;

        $rawData = $this->doCall('getDeal.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        // This is a bugfix: The api doesn't return the deal's id when we ask for a specific deal using this endpoint.
        // To be able to return a complete Deal Entity, it needs to have its id set.
        // We will just fake the id by inserting it ourselves. This if block may be removed when the api returns an id,
        // and everything should keep working.
        if (!isset($rawData['id'])) {
            $rawData['id'] = (int) $id;
        }

        return Deal::initializeWithRawData($rawData);
    }

    /**
     * Search for deals
     *
     * @param int    $amount    The amount of deals returned per request (1-100)
     * @param int    $page      The current page (first page is 0)
     * @param string $searchBy  A search string. Teamleader will try to search deals matching this string.
     * @param int    $segmentId Teamleader will only return deals in this segment.
     * @param int    $phaseId   Teamleader will return only deals that are in this phase right now.
     * @param array  $customFields An array containig the custom field id's to be included in the result
     *
     * @return Deal[]
     */
    public function dealsGetDeals($amount = 100, $page = 0, $searchBy = null, $segmentId = null, $phaseId = null, array $customFields = null)
    {
        $fields = array();
        $fields['amount'] = (int) $amount;
        $fields['pageno'] = (int) $page;

        if ($searchBy !== null) {
            $fields['searchby'] = (string) $searchBy;
        }
        if ($segmentId !== null) {
            $fields['segment_id'] = (int) $segmentId;
        }
        if ($phaseId !== null) {
            $fields['filter_by_phase_id'] = (int) $phaseId;
        }
        if ($customFields !== null) {
            $fields['selected_customfields'] = implode(',', $customFields);
        }

        $rawData = $this->doCall('getDeals.php', $fields);
        $return = array();

        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                $return[] = Deal::initializeWithRawData($row);
            }
        }

        return $return;
    }

    /**
     * Adds an opportunity
     *
     * @param  Deal $deal
     * @return int
     */
    public function opportunitiesAddSale(Deal $deal)
    {
        $this->dealsAddDeal($deal);
    }

    /**
     * Adds an opportunity
     *
     * @param  Deal $deal
     * @return int
     */
    public function dealsAddDeal(Deal $deal)
    {
        $fields = $deal->toArrayForApi();

        return $this->doCall('addSale.php', $fields);
    }

    /**
     * Updates a deal
     *
     * @param Deal $deal
     * @return void
     */
    public function dealsUpdateDeal(Deal $deal)
    {
        $fields = $deal->toArrayForApi(false);
        $fields['deal_id'] = (int) $deal->getId();

        $this->doCall('updateDeal.php', $fields);

        return;
    }

    /**
     * Adds an invoice
     *
     * @param  Invoice $invoice
     * @return int
     */
    public function invoicesAddInvoice(Invoice $invoice)
    {
        $fields = $invoice->toArrayForApi();

        $id = $this->doCall('addInvoice.php', $fields);
        $invoice->setId($id);

        return $id;
    }

    /**
     * Search for invoices
     *
     * @param int $dateFrom
     * @param int $dateTo
     * @param Contact|Company|null $contactOrCompany
     * @param bool $deepSearch
     * @param array $customFields An array containig the custom field
     *                                   id's to be included in the result
     *
     * @return Invoice[]
     */
    public function invoicesGetInvoices($dateFrom, $dateTo, $contactOrCompany = null, $deepSearch = false, array $customFields = null)
    {
        $fields = array();
        $fields['date_from'] = date('d/m/Y', $dateFrom);
        $fields['date_to'] = date('d/m/Y', $dateTo);

        if ($contactOrCompany !== null) {
            switch (gettype($contactOrCompany)) {
                case 'Contact':
                    $fields['contact_or_Company'] = 'contact';
                    break;
                case 'Company':
                    $fields['contact_or_Company'] = 'company';
                    break;
                default:
                    throw new Exception(
                        'Variable $contactOrComany must be an instance of either a Contact or a Company'
                    );
            }
            $fields['contact_or_company_id'] = $contactOrCompany->getId();
        }

        if ($deepSearch) {
            $fields['deep_search'] = 1;
        }
        if ($customFields !== null) {
            $fields['selected_customfields'] = implode(',', $customFields);
        }

        $rawData = $this->doCall('getInvoices.php', $fields);
        $return = array();

        if (!empty($rawData)) {
            $allCustomers = $this->crmGetAllCustomers();
            foreach ($rawData as $row) {
                $return[] = Invoice::initializeWithRawData($row, $this, $allCustomers);
            }
        }

        return $return;
    }

    /**
     * Get a specific invoice by id
     *
     * @param int $id
     * @return Invoice
     */
    public function invoicesGetInvoice($id)
    {
        $fields = array();
        $fields['invoice_id'] = (int) $id;

        $rawData = $this->doCall('getInvoice.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Invoice::initializeWithRawData($rawData, $this);
    }

    /**
     * Get update an invoice
     *
     * @param Invoice $invoice
     * @return bool
     */
    public function invoicesUpdateInvoice(Invoice $invoice)
    {
        $fields = $invoice->toArrayForApi();
        $fields['invoice_id'] = $invoice->getId();

        $rawData = $this->doCall('updateInvoice.php', $fields);

        return ($rawData == 'OK');
    }

    /**
     * Sets the invoice's payment status to paid
     *
     * @param  Invoice $invoice
     * @return bool
     */
    public function invoicesSetInvoicePaid(Invoice $invoice)
    {
        $fields['invoice_id'] = $invoice->getId();
        $rawData = $this->doCall('setInvoicePaid.php', $fields);

        if ($rawData == 'OK') {
            $invoice->setPaid(true);

            return true;
        }

        return false;
    }

    /**
     * Download a pdf of the invoice
     *
     * @param Invoice $invoice
     * @param bool $headers
     * @return mixed
     */
    public function invoicesDownloadInvoicePDF(Invoice $invoice, $headers = false)
    {
        if ($headers) {
            header('Content-type: application/pdf');
        }

        return $this->doCall('downloadInvoicePDF.php', array('invoice_id' => $invoice->getId()));
    }

    /**
     * Adds a credit note to an invoice
     *
     * @param  Invoice $invoice
     * @return int
     */
    public function invoicesAddCreditnote(Creditnote $creditnote)
    {
        $fields = $creditnote->toArrayForApi();

        $id = $this->doCall('addCreditnote.php', $fields);
        $creditnote->setId($id);

        return $id;
    }

    /**
     * Search for creditnotes
     *
     * @param int $dateFrom
     * @param int $dateTo
     * @param Contact|Company|null $contactOrCompany
     * @param bool $deepSearch
     * @return Creditnote[]
     */
    public function invoicesGetCreditnotes(
        $dateFrom,
        $dateTo,
        $contactOrCompany = null,
        $deepSearch = false
    ) {
        $fields = array();
        $fields['date_from'] = date('d/m/Y', $dateFrom);
        $fields['date_to'] = date('d/m/Y', $dateTo);

        if ($contactOrCompany !== null) {
            switch (gettype($contactOrCompany)) {
                case 'Contact':
                    $fields['contact_or_Company'] = 'contact';
                    break;
                case 'Company':
                    $fields['contact_or_Company'] = 'company';
                    break;
                default:
                    throw new Exception(
                        'Variable $contactOrCompany must be an instance of either a Contact or a Company'
                    );
            }
            $fields['contact_or_company_id'] = $contactOrCompany->getId();
        }

        if ($deepSearch) {
            $fields['deep_search'] = 1;
        }

        $rawData = $this->doCall('getCreditnotes.php', $fields);
        $return = array();

        if (!empty($rawData)) {
            $allCustomers = $this->crmGetAllCustomers();
            foreach ($rawData as $row) {
                $return[] = Creditnote::initializeWithRawData($row, $this, $allCustomers);
            }
        }

        return $return;
    }

    /**
     * Get a specific creditnote by id
     *
     * @param int $id
     * @return Creditnote
     */
    public function invoicesGetCreditnote($id)
    {
        $fields = array();
        $fields['creditnote_id'] = (int) $id;

        $rawData = $this->doCall('getCreditnote.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Creditnote::initializeWithRawData($rawData, $this);
    }

    /**
     * Download a pdf of the creditnote
     *
     * @param Creditnote $creditnote
     * @param bool $headers
     * @return mixed
     */
    public function invoicesDownloadCreditnotePDF(Creditnote $creditnote, $headers = false)
    {
        if ($headers) {
            header('Content-type: application/pdf');
        }

        return $this->doCall('downloadInvoicePDF.php', array('creditnote_id' => $creditnote->getId()));
    }

    /**
     * Sends an email invoice reminder
     *
     * @param Invoice $invoice
     * @param string $to
     * @param string $subject
     * @param string $text
     */
    public function invoicesSendInvoice(Invoice $invoice, $to, $subject, $text)
    {
        return $this->doCall(
            'sendInvoice.php',
            array(
                'invoice_id' => $invoice->getId(),
                'email_to' => $to,
                'email_subject' => $subject,
                'email_text' => $text,
            )
        );
    }

    /**
     * Getting information about bookkeeping accounts
     *
     * @param int $sys_department_id
     * @return array
     */
    public function invoicesGetBookkeepingAccounts($sys_department_id)
    {
        return $this->doCall(
            'getBookkeepingAccounts.php',
            array(
                'sys_department_id' => $sys_department_id,
            )
        );
    }

    /**
     * Adds a subscription
     *
     * @param  Subscription $subscription
     * @return int
     */
    public function subscriptionsAddSubscription(Subscription $subscription)
    {
        $fields = $subscription->toArrayForApi();

        $id = $this->doCall('addSubscription.php', $fields);
        $subscription->setId($id);

        return $id;
    }

    /**
     * Add a note
     *
     * @param Note $note
     * @return bool
     */
    public function notesAddNote(Note $note)
    {
        $fields = $note->toArrayForApi();
        $rawData = $this->doCall('addNote.php', $fields);

        return ($rawData == 'OK');
    }

    /**
     * Add a call
     *
     * @param Call $call
     *
     * @return bool
     */
    public function callsAddCall(Call $call)
    {
        return $this->doCall('addCallback.php', $call->toArrayForApi()) === 'OK';
    }

    /**
     * Get the notes for a type
     *
     * @param string $objectType contact, company or sale
     * @param string $objectId ID of the object
     * @param int $pageNumber the current page (the first page is 0)
     *
     * @return Note[]
     */
    public function notesGetNotes($objectType, $objectId, $pageNumber = 0)
    {
        $rawNotes = (array) $this->doCall(
            'getNotes.php',
            ['object_type' => $objectType, 'object_id' => $objectId, 'pageno' => $pageNumber]
        );

        $notes = array();
        foreach ($rawNotes as $rawNote) {
            $notes[] = Note::initializeWithRawData($rawNote);
        }

        return $notes;
    }

    // methods for products

    /**
     * Add a product
     *
     * @param Product    $product
     * @return int
     */
    public function addProduct(Product $product)
    {
        $fields = $product->toArrayForApi();

        $id = $this->doCall('addProduct.php', $fields);
        $product->setId($id);

        return $id;
    }

    /**
     * Update a product
     *
     * @param Product $product
     * @return bool
     */
    public function updateProduct(Product $product)
    {
        $fields = $product->toArrayForApi();
        $fields['product_id'] = $product->getId();

        $rawData = $this->doCall('updateProduct.php', $fields);

        return ($rawData == 'OK');
    }

    /**
     * Delete a product
     *
     * @param int|Product $product	can be either an object of type "Product" or a product Id
     * @return bool
     */
    public function deleteProduct($product)
    {
        $fields = array();
        if ($product instanceof Product) {
            $fields = $product->toArrayForApi();
            $fields['product_id'] = $product->getId();
        } else {
            $fields['product_id'] = (int) $product;
        }
        $rawData = $this->doCall('deleteProduct.php', $fields);

        return (isset($rawData["status"]) && $rawData["status"] === "success");
    }

    /**
     * Search for products
     *
     * @param int $amount The amount of products returned per
     *                                   request (1-100)
     * @param int         $page     The current page (first page is 0)
     * @param string|null $searchBy A search string. Teamleader will try
     *                                   to match each part of the string to
     *                                   the product name
     *                                   and email address.
     * @param int|null $modifiedSince Teamleader will only return products
     *                                   that have been added or modified
     *                                   since that timestamp.
     * @return Product[]
     */
    public function getProducts($amount = 100, $page = 0, $searchBy = null, $modifiedSince = null)
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
        $rawData = $this->doCall('getProducts.php', $fields);
        $return = array();

        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                $return[] = Product::initializeWithRawData($row);
            }
        }

        return $return;
    }

    /**
     * Fetch information about a product
     *
     * @param $id
     * @return Product
     * @throws Exception
     */
    public function getProduct($id)
    {
        $fields = array();
        $fields['product_id'] = (int) $id;

        $rawData = $this->doCall('getProduct.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Product::initializeWithRawData($rawData);
    }

    /**
     * @param Meeting $meeting
     * @return int
     */
    public function meetingsAddMeeting(Meeting $meeting)
    {
        $fields = $meeting->toArrayForApi();

        $id = $this->doCall('addMeeting.php', $fields);
        $meeting->setId($id);

        return $id;
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function meetingGetMeeting($id)
    {
        $fields = array();
        $fields['meeting_id'] = (int) $id;

        $rawData = $this->doCall('getMeeting.php', $fields);

        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Meeting::initializeWithRawData($rawData);
    }

    /**
     * @param $meeting
     * @return bool
     */
    public function meetingDeleteMeeting($meeting)
    {
        $fields = array();
        if ($meeting instanceof Meeting) {
            $fields['meeting_id'] = $meeting->getId();
        } else {
            $fields['meeting_id'] = (int) $meeting;
        }

        $this->doCall('deleteMeeting.php', $fields);

        /*
         * The endpoint deleteMeeting.php doesn't return any usable information as to whether the call succeeded or not.
         * No http code or usable message. But it does work!
         *
         * @todo use $this->meetingGetMeeting() to perform some sort of validation
         */
        return true;
    }

    /**
     * @param MeetingContactAttendee $attendee
     * @return array
     * @throws Exception
     */
    public function meetingAddAttendee(MeetingContactAttendee $attendee)
    {
        $fields = $attendee->toArrayForApi();

        $rawData = $this->doCall('addContactToMeeting.php', $fields);

        return ($rawData == 'OK');
    }

    /**
     * @param MeetingContactAttendee $attendee
     * @return array
     * @throws Exception
     */
    public function meetingDeleteAttendee(MeetingContactAttendee $attendee)
    {
        $fields = $attendee->toArrayForApi();

        $rawData = $this->doCall('removeContactFromMeeting.php', $fields);

        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return $rawData;
    }

    /**
     * Retrieves a list of Meeting objects. But be cautious as this Teamleader endpoint does not return the user_id for
     * meetings.
     *
     * @param int $amount Value from 1 to 100.
     * @param int $page Paging starts at 0.
     * @param string|null $dateFrom Provide a date in dd/mm/yyyy format or leave null.
     * @param string|null $dateTo Provide a date in dd/mm/yyyy format or leave null.
     * @param int|null $projectId
     * @return Meeting[]
     * @throws Exception
     */
    public function meetingGetAll($amount = 100, $page = 0, $dateFrom = null, $dateTo = null, $projectId = null)
    {
        $fields = array(
            'amount' => $amount,
            'pageno' => $page,
        );

        if (isset($dateFrom)) {
            $fields['date_from'] = $dateFrom;
        }

        if (isset($dateTo)) {
            $fields['date_to'] = $dateTo;
        }

        if (isset($projectId)) {
            $fields['project_id'] = $projectId;
        }

        $rawData = $this->doCall('getMeetings.php', $fields);

        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        $meetings = array();
        foreach ($rawData as $rawMeeting) {
            $meetings[] = Meeting::initializeWithRawData($rawMeeting);
        }

        return $meetings;
    }
}
