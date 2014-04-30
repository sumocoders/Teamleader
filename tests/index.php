<?php

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

use \SumoCoders\Teamleader\Teamleader;
use \SumoCoders\Teamleader\Crm\Contact;
use \SumoCoders\Teamleader\Crm\Company;
use \SumoCoders\Teamleader\Opportunities\Sale;
use \SumoCoders\Teamleader\Opportunities\SaleLine;
use \SumoCoders\Teamleader\Invoices\Invoice;
use \SumoCoders\Teamleader\Invoices\InvoiceLine;

// create instance
$teamleader = new Teamleader(API_GROUP, API_KEY);

try {
   // $response = $teamleader->helloWorld();

   // $response = $teamleader->crmGetContacts();
   // $response = $teamleader->crmGetContact(1109425);

   // $contact = new Contact();
   // $contact->setForename('Tijs');
   // $contact->setSurname('Verkoyen');
   // $contact->setEmail(time() . '@verkoyen.eu');
   // $response = $teamleader->crmAddContact($contact);

   // $contact = new Contact();
   // $contact->setId(1109425);
   // $contact->setEmail(time() . '@verkoyen.eu');
   // $response = $teamleader->crmUpdateContact($contact);

   // $response = $teamleader->crmGetCompanies();
   // $response = $teamleader->crmGetCompany(450736);

   // $company = new Company();
   // $company->setName('Avocom ' . time());
   // $response = $teamleader->crmAddCompany($company);
   // var_dump($company);

   // $company = new Company();
   // $company->setId(674676);
   // $company->setEmail(time() . '@verkoyen.eu');
   // $response = $teamleader->crmUpdateCompany($company);

   // $sale = new Sale();
   // $sale->setTitle('title');
   // $sale->setSource('source');
   // $sale->setResponsibleSysClientId(3187);
   // $sale->setCompany($company);
   // $sale->setSysDepartmentId(2131);
   // $sale->setDescription('description');

   // $line1 = new SaleLine();
   // $line1->setAmount(1);
   // $line1->setDescription('description 1');
   // $line1->setPrice(10);
   // $line1->setVat('21');
   // $sale->addLine($line1);

   // $line2 = new SaleLine();
   // $line2->setAmount(2);
   // $line2->setDescription('description 2');
   // $line2->setPrice(20);
   // $line2->setVat('06');
   // $sale->addLine($line2);
   
   // $invoice = new Invoice();
   // $invoice->setCompany($company);
   // $invoice->setSysDepartmentId(2131);

   // $line1 = new InvoiceLine();
   // $line1->setAmount(1);
   // $line1->setDescription('Description 1');
   // $line1->setPrice(30);
   // $line1->setVat('06');
   // $invoice->addLine($line1);

   // $line2 = new InvoiceLine();
   // $line2->setAmount(2);
   // $line2->setDescription('Description 2');
   // $line2->setPrice(15);
   // $line2->setVat('06');
   // $invoice->addLine($line2);

   //  $response = $teamleader->invoicesAddInvoice($invoice);
} catch (Exception $e) {
    var_dump($e);
}

// output
var_dump($response);
