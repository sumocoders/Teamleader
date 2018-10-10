<?php

namespace SumoCoders\Teamleader;

use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Deals\Deal;
use SumoCoders\Teamleader\Tasks\Task;
use SumoCoders\Teamleader\Client\TeamleaderClient;

/**
 * Teamleader class
 *
 * @author         Bram Hoeyberghs <bram.hoeyberghs@intracto.com>
 * @version        2.0.0
 * @copyright      Copyright (c) Intracto. All rights reserved.
 */
class Teamleader
{
    // internal constant to enable/disable debugging
    const DEBUG = true;
    /**
     * Client
     *
     * @var TeamleaderClient
     */
    private $client;

    /**
     * Create an instance
     *
     * @param string $clientID
     * @param string $clientSecret
     * @param string $username
     * @param string $password
     * @param string $redirectUri
     */
    public function __construct($clientID, $clientSecret, $username, $password, $redirectUri, $token, $refreshToken, $expireDate)
    {
        $this->client = new TeamleaderClient($clientID,$clientSecret,$username,$password,$redirectUri, $token, $refreshToken, $expireDate);
    }

    /**
     * Get the user identity information using the access token.
     */

    public function getUser(){
        $options = array();
        $options['client_id'] = $this->getClientID();
        $options['client_secret'] = $this->getClientSecret();
        $options['code'] = $this->getCode();
        $endPoint = 'users.me';
        return $this->doRequest($endPoint,$options,'GET');
    }

    public function getUserList($amount = 100, $page = 1){
        $options = array();
        $options['page'] = array('size'=>$amount,'number'=>$page);
        $endPoint = 'users.list';
        return $this->client->doCall($endPoint,$options,'GET');
    }

    // CRM methods

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
    public function crmGetContacts($amount = 100, $page = 1, $searchBy = null, $modifiedSince = null, array $customFields = null)
    {
        $fields = array();
        $fields['page'] = array('size'=>$amount,'number'=>$page);

        if ($searchBy !== null) {
            $fields['filter']['email'] = array('type'=>'primary','email'=>$searchBy);
        }
        if ($modifiedSince !== null) {
            $fields['modifiedsince'] = (int) $modifiedSince;
        }
        if ($customFields !== null) {
            $fields['selected_customfields'] = implode(',', $customFields);
        }

        return $this->client->doCall('contacts.list', $fields,'GET');
    }

    /**
     * Fetch information about a contact
     *
     * @param  string     $id The ID of the contact
     * @return Contact
     */
    public function crmGetContact($id)
    {
        $fields = array();
        $fields['id'] = $id;

        $rawData = $this->client->doCall('contacts.info', $fields,'GET');

        // validate response
        if (!is_array($rawData)) {
            throw new Exception($rawData);
        }

        return Contact::initializeWithRawData($rawData);
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
    public function dealsGetDeals($amount = 100, $page = 1, $searchBy = null, $sort = null)
    {
        $fields = array();
        $fields['page'] = array('size'=>$amount,'number'=>$page);

        if ($searchBy !== null) {
            $fields['filter'] = $searchBy;
        }

        if ($sort !== null){
            $fields['sort'] = $sort;
        }

        $rawData = $this->client->doCall('deals.list', $fields,'GET');
        $rawData = json_decode($rawData);

        $return = array();

        if (isset($rawData->data)) {
            foreach ($rawData->data as $row) {
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
     * Adding an new task
     *
     * @param Task $task
     * @return int
     */
    public function calendarAddTask(Task $task){
        $fields = $task->toArrayForApi();
        $endPoint = 'events.create';
        return $this->client->doCall($endPoint, $fields, 'POST');
    }

    /**
     * Adding an new task
     *
     * @param Task $task
     * @return int
     */
    public function crmAddTask(Task $task){
        $fields = $task->toArrayForApiV1();

        $id = $this->client->doCallV1('api/addTask.php', $fields,'POST');
        $task->setId($id);

        return $id;
    }

    public function calendarActivityTypes(){
        $options = array();
        $endPoint = 'activityTypes.list';
        return $this->client->doCall($endPoint,$options,'GET');
    }

    public function calendarWorkTypes(){
        $options = array();
        $endPoint = 'workTypes.list';
        return $this->client->doCall($endPoint,$options,'POST');
    }

    public function getDepartements(){
        $options = array();
        $endPoint = 'departments.list';
        return $this->client->doCall($endPoint,$options,'GET');
    }

    public function getToken(){
        return $this->client->getToken();
    }

    public function getRefreshToken(){
        return $this->client->getRefreshToken();
    }

    public function getExpiredDate(){
        return $this->client->getExpireDate();
    }

    public function getTaskTypes(){
        return $this->client->doCallV1('api/getTaskTypes.php',array(),'POST');
    }
}
