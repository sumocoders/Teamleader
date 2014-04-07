<?php

namespace SumoCoders\Teamleader\tests;

require_once '../../../autoload.php';
require_once 'config.php';

use SumoCoders\Teamleader\Crm\Company;
use \SumoCoders\Teamleader\Teamleader;
use \SumoCoders\Teamleader\Crm\Contact;

class TeamleaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Teamleader
     */
    private $teamleader;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->teamleader = new Teamleader(API_GROUP, API_KEY);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->teamleader = null;
        parent::tearDown();
    }

    /**
     * Tests Teamleader->getTimeOut()
     */
    public function testGetTimeOut()
    {
        $this->teamleader->setTimeOut(5);
        $this->assertEquals(5, $this->teamleader->getTimeOut());
    }

    /**
     * Tests Teamleader->getUserAgent()
     */
    public function testGetUserAgent()
    {
        $this->teamleader->setUserAgent('testing/1.0.0');
        $this->assertEquals(
            'PHP Teamleader/' . Teamleader::VERSION . ' testing/1.0.0',
            $this->teamleader->getUserAgent()
        );
    }

    /**
     * Tests Teamleader->helloWorld()
     */
    public function testHelloWorld()
    {
        $this->assertEquals($this->teamleader->helloWorld(), 'Successful Teamleader API request.');
    }

    /**
     * Tests Teamleader->crmGetContacts()
     */
    public function testCrmGetContacts()
    {
        $data = $this->teamleader->crmGetContacts();
        foreach ($data as $row) {
            $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Contact', $row);
        }
    }

    /**
     * Tests Teamleader->crmGetContact()
     */
    public function testCrmGetContact()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');

        $id = $this->teamleader->crmAddContact($contact);

        $contactFromApi = $this->teamleader->crmGetContact($id);
        $this->assertEquals($contact->getEmail(), $contactFromApi->getEmail());
    }

    /**
     * Tests Teamleader->crmAddContact
     */
    public function testCrmAddContact()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');

        $id = $this->teamleader->crmAddContact($contact);

        $contactFromApi = $this->teamleader->crmGetContact($id);
        $this->assertEquals($contact->getEmail(), $contactFromApi->getEmail());
    }

    /**
     * Tests Teamleader->crmUpdateContact
     */
    public function testCrmUpdateContact()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');

        $id = $this->teamleader->crmAddContact($contact);

        $contact->setId($id);
        $contact->setEmail($time . '-edited@example.com');
        $this->assertTrue($this->teamleader->crmUpdateContact($contact));

        $contactFromApi = $this->teamleader->crmGetContact($id);
        $this->assertEquals($contact->getEmail(), $contactFromApi->getEmail());
    }
    /**
     * Tests teamleader->crmAddCompany()
     */
    public function testCrmAddCompany() {
        $company = new Company();
        $company->setName(time());
        $id = $this->teamleader->crmAddCompany($company);

        $response = $this->teamleader->crmGetCompany($id);
        $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Company', $response);
    }

}
