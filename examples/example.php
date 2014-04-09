<?php

/**
 * Teamleader example
 *
 * This Teamleader PHP Wrapper class connects to the Teamleader API.
 *
 * @author Jeroen Desloovere <info@jeroendesloovere.be>
 */

// add your own credentials in this file
require_once __DIR__ . '/credentials.php';

// required to load
require_once __DIR__ . '/../../../autoload.php';

use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Opportunities\Sale;
use SumoCoders\Teamleader\Opportunities\SaleLine;

// create instance
$teamleader = new Teamleader($apiGroup, $apiKey);

try {
//    $response = $teamleader->helloWorld();
//
//    $response = $teamleader->crmGetContacts();
//    $response = $teamleader->crmGetContact(1109425);
//
//    $contact = new Contact();
//    $contact->setForename('Tijs');
//    $contact->setSurname('Verkoyen');
//    $contact->setEmail(time() . '@verkoyen.eu');
//    $response = $teamleader->crmAddContact($contact);
//
//    $contact = new Contact();
//    $contact->setId(1109425);
//    $contact->setEmail(time() . '@verkoyen.eu');
//    $response = $teamleader->crmUpdateContact($contact);
//
//    $response = $teamleader->crmGetCompanies();
//    $response = $teamleader->crmGetCompany(450736);
//
//    $company = new Company();
//    $company->setName('Avocom');
//    $response = $teamleader->crmAddCompany($company);
//
//    $company = new Company();
//    $company->setId(674676);
//    $company->setEmail(time() . '@verkoyen.eu');
//    $response = $teamleader->crmUpdateCompany($company);
//
//    $sale = new Sale();
//    $sale->setTitle('title');
//    $sale->setSource('source');
//    $sale->setResponsibleSysClientId(3187);
//    $sale->setContact($contact);
//    $sale->setSysDepartmentId(2131);
//    $sale->setDescription('description');
//
//    $line1 = new SaleLine();
//    $line1->setAmount(1);
//    $line1->setDescription('description 1');
//    $line1->setPrice(10);
//    $line1->setVat('21');
//    $sale->addLine($line1);
//
//    $line2 = new SaleLine();
//    $line2->setAmount(2);
//    $line2->setDescription('description 2');
//    $line2->setPrice(20);
//    $line2->setVat('06');
//    $sale->addLine($line2);
//
    $response = $teamleader->opportunitiesAddSale($sale);
} catch (Exception $e) {
    var_dump($e);
}

// output
var_dump($response);
