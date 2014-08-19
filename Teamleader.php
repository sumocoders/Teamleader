<?php

namespace SumoCoders\Teamleader;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Invoices\Invoice;
use SumoCoders\Teamleader\Invoices\Creditnote;
use SumoCoders\Teamleader\Subscriptions\Subscription;
use SumoCoders\Teamleader\Deals\Deal;

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
    public function __construct($apiGroup, $apiSecret, $sslEnabled = false)
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
    public function setSslEnabled($enabled) {
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
        if(!$this->getSslEnabled()) {
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
     * @param Contact $contact
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

    public function crmGetContactsByCompany($id)
    {
        $fields = array();
        $fields['company_id'] = (int) $id;

        $rawData = $this->doCall('getContactsByCompany.php ', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

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
     * Add a contact
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
     * @return array of Company
     */
    public function crmGetCompanies($amount = 100, $page = 0, $searchBy = null, $modifiedSince = null, $filterByTag = null)
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
        if ($filterByTag !== null) {
            $fields['filter_by_tag'] = (string) $filterByTag;
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
     * @param  int     $id The ID of the company
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
        $i = 1;
        while ($i == 1 || (sizeof($customers['contacts']) != 0 && sizeof($customers['contacts']) % 100 == 0)) {
            foreach ($this->crmGetContacts(100, $i) as $contact) {
                $customers['contacts'][$contact->getId()] = $contact;
            }
            $i++;
        }

        $customers['companies'] = array();
        $i = 1;
        while ($i == 1 || (sizeof($customers['companies']) != 0 && sizeof($customers['companies']) % 100 == 0)) {
            foreach ($this->crmGetCompanies(100, $i) as $company) {
                $customers['companies'][$company->getId()] = $company;
            }
            $i++;
        }

        return $customers;
    }

    public function dealsGetDeal($id) 
    {
        $fields = array();
        $fields['deal_id'] = (int) $id;

        $rawData = $this->doCall('getDeal.php', $fields);

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Deal::initializeWithRawData($rawData);
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
     * @return array
     */
    public function invoicesGetInvoices($dateFrom, $dateTo, $contactOrCompany = null, $deepSearch = false)
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
     * @return 
     */
    public function invoicesDownloadInvoicePDF(Invoice $invoice, $headers = false)
    {
        if ($headers) {
            header('Content-type: application/pdf');
        }
        return $this->doCall('downloadInvoicePDF.php', array('invoice_id', $invoice->getId()));
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
     * @return array
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
     * @return 
     */
    public function invoicesDownloadCreditnotePDF(Creditnote $creditnote, $headers = false)
    {
        if ($headers) {
            header('Content-type: application/pdf');
        }
        return $this->doCall('downloadInvoicePDF.php', array('creditnote_id', $creditnote->getId()));
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
}
