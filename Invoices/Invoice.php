<?php

/**
 * @todo Discount info
 */

namespace SumoCoders\Teamleader\Invoices;

use DateTime;
use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Invoices\InvoiceLine;

class Invoice
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

    /**
     * @var  int
     */
    private $id;

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
     * @var bool
     */
    private $paid = false;

    /**
     * @var array
     */
    private $lines;

    /**
     * @var int
     */
    private $invoiceNr;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $dateFormatted;

    /**
     * @var DateTime
     */
    private $datePaid;

    /**
     * @var string
     */
    private $datePaidFormatted;

    /**
     * @var int
     */
    private $totalPriceExclVat;

    /**
     * @var int
     */
    private $totalPriceInclVat;

    /**
     * @var DateTime
     */
    private $dueDate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @param int $paid
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
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
     * @param int $invoiceNr
     */
    public function setInvoiceNr($invoiceNr)
    {
        $this->invoiceNr = $invoiceNr;
    }

    /**
     * @return int
     */
    public function getInvoiceNr()
    {
        return $this->invoiceNr;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $dateFormatted
     */
    public function setDateFormatted($dateFormatted)
    {
        $this->dateFormatted = $dateFormatted;
    }

    /**
     * @return int
     */
    public function getDateFormatted()
    {
        return $this->dateFormatted;
    }

    /**
     * @param DateTime $datePaid
     */
    public function setDatePaid(DatePaidTime $datePaid)
    {
        $this->datePaid = $datePaid;
    }

    /**
     * @return int
     */
    public function getDatePaid()
    {
        return $this->datePaid;
    }

    /**
     * @param string $datePaidFormatted
     */
    public function setDatePaidFormatted($datePaidFormatted)
    {
        $this->datePaidFormatted = $datePaidFormatted;
    }

    /**
     * @return string
     */
    public function getDatePaidFormatted()
    {
        return $this->datePaidFormatted;
    }

    /**
     * @param flaot $totalPriceExclVat
     */
    public function setTotalPriceExclVat($totalPriceExclVat)
    {
        $this->totalPriceExclVat = $totalPriceExclVat;
    }

    /**
     * @return flaot
     */
    public function getTotalPriceExclVat()
    {
        return $this->totalPriceExclVat;
    }

    /**
     * @param flaot $totalPriceInclVat
     */
    public function setTotalPriceInclVat($totalPriceInclVat)
    {
        $this->totalPriceInclVat = $totalPriceInclVat;
    }

    /**
     * @return flaot
     */
    public function getTotalPriceInclVat()
    {
        return $this->totalPriceInclVat;
    }

    /**
     * @param DateTime $dueDate
     */
    public function setDueDate(DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
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
     * Initialize an Invoice with raw data we got from the API
     *
     * @param  array   $data
     * @return Invoice
     */
    public static function initializeWithRawData($data)
    {
        $invoice = new Invoice();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'date':
                    $date = new DateTime();
                    $date->setTimestamp($value);
                    $invoice->setDate($date);
                    break;

                case 'date_paid':
                    if ($value != -1) {
                        $datePaid = new DateTime();
                        $datePaid->setTimestamp($value);
                        $invoice->setDatePaid($datePaid);
                    }
                    break;

                case 'paid':
                    $invoice->setPaid((bool) $value);
                    break;

                case 'for':
                    break;

                case 'for_id':
                    // $tl = new Teamleader();
                    if ($data['for'] == self::CONTACT) {
                        // $invoice->setContact($tl->crmGetContact($value));
                    } else if ($data['for'] == self::COMPANY) {
                        // $invoice->setCompany($tl->crmGetCompany($value));
                    } else {
                        throw new Exception('\'For\' must be ' . self::CONTACT . ' or ' . self::COMPANY . '.');
                    }
                    break;

                case 'items':
                    foreach ($value as $invoiceLine) {
                        $invoice->addLine(InvoiceLine::initializeWithRawData($invoiceLine));
                    }
                    break;

                case 'discount_info':
                    // Todo
                    break;

                case 'due_date':
                    $dueDate = new DateTime();
                    $dueDate->setTimestamp($value);
                    $invoice->setDueDate($dueDate);
                    break;

                default:
                    // ignore empty values
                    if ($value == '') {
                        continue;
                    }

                    $methodName = 'set' . str_replace('_', '', ucwords($key));
                    if (!method_exists(__CLASS__, $methodName)) {
                        if (Teamleader::DEBUG) {
                            var_dump($key, $value);
                        }
                        throw new Exception('Unknown method (' . $methodName . ')');
                    }
                    call_user_func(array($invoice, $methodName), $value);
            }
        }

        return $invoice;
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
