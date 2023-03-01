<?php

namespace SumoCoders\Teamleader\Tickets;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;

class Ticket
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var  string
     * client_name
     */
    private $clientName;

    /**
     * @var  string
     * client_email
     */
    private $clientEmail;

    /**
     * @var  string
     * subject
     */
    private $subject;

    /**
     * @var  string
     * description_html
     */
    private $content;


    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getClientName() {
        return $this->clientName;
    }

    public function setClientName($clientName) {
        $this->clientName = $clientName;
    }

    public function getClientEmail() {
        return $this->clientEmail;
    }

    public function setClientEmail($clientEmail) {
        $this->clientEmail = $clientEmail;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Prepares the ticket for an API request
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array(
            'client_name' => $this->getClientName(),
            'client_email' => $this->getclientEmail(),
            'subject' => $this->getSubject(),
            'description_html' => $this->getContent(),
        );

        return $return;
    }

    /**
     * Initialize an Ticket with raw data we got from the API
     *
     * @return Ticket
     */
    public static function initializeWithRawData($data)
    {
        $ticket = new Ticket();

        $ticket->setClientName($data['client_name']);
        $ticket->setClientEmail($data['client_email']);
        $ticket->setSubject($data['subject']);
        $ticket->setContent($data['content']);

        return $ticket;
    }
}
