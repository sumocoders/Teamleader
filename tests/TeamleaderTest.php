<?php

/**
 * @todo PDF generation functions
 */

namespace SumoCoders\Teamleader\tests;

spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    if ($parts[0] == 'SumoCoders' && $parts[1] == 'Teamleader') {
        unset($parts[0], $parts[1]);
        $root = __DIR__ . DIRECTORY_SEPARATOR . '..';
        $file = ''; 
        foreach ($parts as $part) {
            $file .= DIRECTORY_SEPARATOR . $part;
        }
        $file .= '.php';
        require_once $root . $file;
    }
});

require_once 'config.php';

use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Deals\Deal;
use SumoCoders\Teamleader\Invoices\Invoice;
use SumoCoders\Teamleader\Invoices\InvoiceLine;
use SumoCoders\Teamleader\Invoices\Creditnote;
use SumoCoders\Teamleader\Invoices\CreditnoteLine;
use SumoCoders\Teamleader\Subscriptions\Subscription;
use SumoCoders\Teamleader\Subscriptions\SubscriptionLine;

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
        $contact->addTag('tag1');
        $contact->addTag('tag2');

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
     * Tests Teamleader->crmGetCompanies()
     */
    public function testCrmGetCompanies()
    {
        $data = $this->teamleader->crmGetCompanies();
        foreach ($data as $row) {
            $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Company', $row);
        }
    }

    /**
     * Tests Teamleader->crmGetCompanies()
     */
    public function testCrmGetCompany()
    {
        $company = new Company();
        $company->setName(time());
        $id = $this->teamleader->crmAddCompany($company);

        $response = $this->teamleader->crmGetCompany($id);
        $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Company', $response);
    }

    /**
     * Tests teamleader->crmAddCompany()
     */
    public function testCrmAddCompany()
    {
        $company = new Company();
        $company->setName(time());
        $id = $this->teamleader->crmAddCompany($company);

        $response = $this->teamleader->crmGetCompany($id);
        $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Company', $response);
    }

    /**
     * Tests teamleader->crmUpdateCompany()
     */
    public function testCrmUpdateCompany()
    {
        $street = time();

        $company = new Company();
        $company->setName(time());

        $id = $this->teamleader->crmAddCompany($company);
        $company->setId($id);
        $company->setStreet($street);

        $this->assertTrue($this->teamleader->crmUpdateCompany($company));

        $response = $this->teamleader->crmGetCompany($id);
        $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Company', $response);
        $this->assertEquals($street, $response->getStreet());
    }

    /**
     * Tests teamleader->crmUpdateCompany()
     */
    public function testCrmGetAllCustomers()
    {
        $time = time();

        $company = new Company();
        $company->setName(time());

        $id = $this->teamleader->crmAddCompany($company);
        $company->setId($id);
        $company->setStreet($time);

        $companyId = $this->teamleader->crmAddCompany($company);

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');

        $contactId = $this->teamleader->crmAddContact($contact);

        $customers = $this->teamleader->crmGetAllCustomers();

        $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Company', $customers['companies'][$companyId]);
        $this->assertInstanceOf('SumoCoders\Teamleader\Crm\Contact', $customers['contacts'][$contactId]);
    }

    /**
     * Tests teamleader->dealsAddDeal)
     */
    public function testOpportunitiesAddDeal()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $deal = new Deal();
        $deal->setTitle('title_' . $time);
        $deal->setSource('source_' . $time);
        $deal->setContact($contact);
        $deal->setResponsibleSysClientId(3187);
        $deal->setSysDepartmentId(2131);

        $response = $this->teamleader->dealsAddDeal($deal);
        $this->assertInternalType('integer', $response);
    }

    /**
     * Tests teamleader->invoicesAddInvoice();
     */
    public function testInvoicesAddInvoice()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $response = $this->teamleader->invoicesAddInvoice($invoice);
        $this->assertInternalType('integer', $response);
    }

    /**
     * Tests teamleader->invoicesGetInvoice()
     */
    public function testInvoicesGetInvoice()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $id = $this->teamleader->invoicesAddInvoice($invoice);

        $response = $this->teamleader->invoicesGetInvoice($id);

        $this->assertInstanceOf('SumoCoders\Teamleader\Invoices\Invoice', $response);
    }

    /**
     * Tests teamleader->invoicesUpdateInvoice()
     */
    public function testInvoicesUpdateInvoice()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $id = $this->teamleader->invoicesAddInvoice($invoice);
        $invoice->setId($id);

        $response = $this->teamleader->invoicesUpdateInvoice($invoice);

        $this->assertTrue($response);
    }

    /**
     * Tests teamleader->invoicesSetInvoicePaid()
     */
    public function testInvoicesSetInvoicePaid()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $id = $this->teamleader->invoicesAddInvoice($invoice);
        $invoice->setId($id);

        $response = $this->teamleader->invoicesSetInvoicePaid($invoice);

        $this->assertTrue($response);
    }

    /**
     * Tests teamleader->invoicesGetInvoices()
     */
    public function testInvoicesGetInvoices()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $this->teamleader->invoicesAddInvoice($invoice);

        $dateFrom = strtotime(date('Y-m-d H:i:s') . " -1 day");
        $dateTo = strtotime(date('Y-m-d H:i:s') . " +1 day");
        $response = $this->teamleader->invoicesGetInvoices($dateFrom, $dateTo);
        
        $this->assertInstanceOf('SumoCoders\Teamleader\Invoices\Invoice', $response[0]);
    }

    /**
     * Tests teamleader->invoicesCreditnote();
     */
    public function testInvoicesAddCreditnote()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $id = $this->teamleader->invoicesAddInvoice($invoice);
        $invoice->setId($id);

        $creditnote = new Creditnote();
        $creditnote->setInvoice($invoice);

        $line1 = new CreditnoteLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $creditnote->addLine($line1);

        $response = $this->teamleader->invoicesAddCreditnote($creditnote);
        $this->assertInternalType('integer', $response);
    }

    /**
     * Tests teamleader->invoicesGetCreditnote()
     */
    public function testInvoicesGetCreditnote()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $id = $this->teamleader->invoicesAddInvoice($invoice);
        $invoice->setId($id);

        $creditnote = new Creditnote();
        $creditnote->setInvoice($invoice);

        $line1 = new CreditnoteLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $creditnote->addLine($line1);

        $id = $this->teamleader->invoicesAddCreditnote($creditnote);

        $response = $this->teamleader->invoicesGetCreditnote($id);
        
        $this->assertInstanceOf('SumoCoders\Teamleader\Invoices\Creditnote', $response);
    }

    /**
     * Tests teamleader->invoicesGetCreditnotes()
     */
    public function testInvoicesGetCreditnotes()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $invoice = new Invoice();
        $invoice->setContact($contact);
        $invoice->setSysDepartmentId(2131);

        $line1 = new InvoiceLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $invoice->addLine($line1);

        $id = $this->teamleader->invoicesAddInvoice($invoice);
        $invoice->setId($id);

        $creditnote = new Creditnote();
        $creditnote->setInvoice($invoice);

        $line1 = new CreditnoteLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $creditnote->addLine($line1);

        $this->teamleader->invoicesAddCreditnote($creditnote);

        $dateFrom = strtotime(date('Y-m-d H:i:s') . " -1 day");
        $dateTo = strtotime(date('Y-m-d H:i:s') . " +1 day");
        $response = $this->teamleader->invoicesGetCreditnotes($dateFrom, $dateTo);
        
        $this->assertInstanceOf('SumoCoders\Teamleader\Invoices\Creditnote', $response[0]);
    }

    /**
     * Tests teamleader->invoicesGetCreditnotes()
     */
    public function testSubscriptionsAddSubscription()
    {
        $time = time();

        $contact = new Contact();
        $contact->setForename($time);
        $contact->setSurname($time);
        $contact->setEmail($time . '@example.com');
        $id = $this->teamleader->crmAddContact($contact);
        $contact->setId($id);

        $subscription = new Subscription();
        $subscription->setContact($contact);
        $subscription->setSysDepartmentId(2131);
        $subscription->setTitle($time);
        $subscription->setDateStart($time);
        $subscription->setRepeatAfter('monthly');

        $line1 = new SubscriptionLine();
        $line1->setAmount(1);
        $line1->setDescription('Description ' . $time);
        $line1->setPrice(30);
        $line1->setVat('06');
        $subscription->addLine($line1);

        $id = $this->teamleader->subscriptionsAddSubscription($subscription);
        
        $this->assertEquals($subscription->getId(), $id);
    }
}
