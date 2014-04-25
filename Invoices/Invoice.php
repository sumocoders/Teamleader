<?php

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Invoices\InvoiceLine;

// API endpoint: https://www.teamleader.be/api/addInvoice.php
// Required POST parameters for invoices
// contact_or_company: contact or company: Who is the invoice for?
// contact_or_company_id:integer: ID of the contact or company
// sys_department_id: ID of the department the invoice will be added to
// Extra POST parameters for invoice lines (required)
// description_1: string
// price_1: decimal
// amount_1: decimal
// vat_1: 00/ 06 / 12 / 21 / CM / EX / MC / VCMD: the vat tariff for this line
// product_id_1: id of the product (optional)
// account_1: id of the bookkeeping account (optional)

// description_2: string
// price_2: decimal
// ...
// custom_field_ID: replace ID by the ID of your custom field.

class Invoice
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var int
     */
    private $sysDepartmentId;

    /**
     * @var array
     */
    private $lines;

    /**
     * @param \SumoCoders\Teamleader\Crm\Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return \SumoCoders\Teamleader\Crm\Company
     */
    public function getCompany()
    {
        return $this->company;
    }    

    /**
     * @param \SumoCoders\Teamleader\Crm\Contact $contact
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return \SumoCoders\Teamleader\Crm\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param int $sysDepartmentId
     */
    public function setSysDepartmentId($sysDepartmentId)
    {
        $this->sysDepartmentId = $sysDepartmentId;
    }

    /**
     * @param int $sysDepartmentId
     */
    public function getSysDepartmentId()
    {
        return $this->sysDepartmentId;
    }

    /**
     * @param array $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Is this invoice linked to a contact or a company
     *
     * @return string
     * @throws \SumoCoders\Teamleader\Exception
     */
    public function isContactOrCompany()
    {
        if ($this->getContact() && $this->getCompany()) {
            throw new Exception('You can\'t specify a contact and a company');
        }

        if ($this->getContact()) {
            return self::CONTACT;
        }
        if ($this->getCompany()) {
            return self::COMPANY;
        }

        throw new Exception('No contact or company specified');
    }

    /**
     * @param InvoiceLine $line
     */
    public function addLine(InvoiceLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * This method will convert an invoice to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getContact()) {
            $return['contact_or_company_id'] = $this->getContact()->getId();
        }
        if ($this->getCompany()) {
            $return['contact_or_company_id'] = $this->getCompany()->getId();
        }
        $return['contact_or_company'] = $this->isContactOrCompany();
        if ($this->getSysDepartmentId()) {
            $return['sys_department_id'] = $this->getSysDepartmentId();
        }

        $lines = $this->getLines();
        if (!empty($lines)) {
            foreach ($lines as $index => $line) {
                $return = array_merge(
                    $return,
                    $line->toArrayForApi($index + 1)
                );
            }
        }

        return $return;
    }
}
