<?php

/**
 * 
 * @todo Discount info
 */

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Invoices\CreditnoteLine;

class Creditnote
{
    const CONTACT = 'contact';
    const COMPANY = 'company';
    
    /**
     * @var int
     */
    private $id;

    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var array
     */
    private $lines;

    /**
     * @var int
     */
    private $creditnoteNr;

    /**
     * @var int
     */
    private $date;

    /**
     * @var string
     */
    private $dateFormatted;

    /**
     * @var int
     */
    private $datePaid;

    /**
     * @var string
     */
    private $datePaidFormatted;

    /**
     * @var bool
     */
    private $paid = false;

    /**
     * @var int
     */
    private $totalPriceExclVat;

    /**
     * @var int
     */
    private $totalPriceInclVat;

    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var string
     */
    private $name;

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
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
    
    /**
     * @param Invoice $invoice the invoice
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }
    
    /**
     * @param array $lines the lines
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }

    /**
     * @param CreditnoteLine $line
     */
    public function addLine(CreditnoteLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * @param int $creditnoteNr
     */
    public function setCreditnoteNr($creditnoteNrNr)
    {
        $this->creditnoteNrNr = $creditnoteNrNr;
    }

    /**
     * @return int
     */
    public function getCreditnoteNr()
    {
        return $this->creditnoteNrNr;
    }

    /**
     * @param int $date
     */
    public function setDate($date)
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
     * @param int $datePaid
     */
    public function setDatePaid($datePaid)
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Is this creditnote linked to a contact or a company
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
     * Initialize an Invoice with raw data we got from the API
     *
     * @param  array   $data
     * @return Invoice
     */
    public static function initializeWithRawData($data, $tl, $cachedCustomers = null)
    {
        $creditnote = new Creditnote();

        foreach ($data as $key => $value) {
            switch ($key) {

                case 'date_paid':
                    if ($value != 0) {
                        $creditnote->setDatePaid($value);
                    }
                    break;

                case 'paid':
                    $creditnote->setPaid((bool) $value);
                    break;

                // Ignore 'for' and 'contact_or_company' until the id is given
                case 'for':
                case 'contact_or_company':
                    break;

                case 'for_id':
                case 'contact_or_company_id':
                    $contactOrCompany = null;
                    $contactOrCompanyId = null;

                    // Check if contact or copany are given via a 'for' property or a 'contact_or_company' property
                    if (isset($data['for'])) {
                        $contactOrCompany = $data['for'];
                    }else if (isset($data['contact_or_company'])) {
                        $contactOrCompany = $data['contact_or_company'];
                    }

                    if (isset($data['for_id'])) {
                        $contactOrCompanyId = $data['for_id'];
                    } else if (isset($data['contact_or_company_id'])) {
                        $contactOrCompanyId = $data['contact_or_company_id'];
                    }

                    if ($contactOrCompany == self::CONTACT) {
                        if ($cachedCustomers) {
                            $creditnote->setContact($cachedCustomers['contacts'][$value]);
                        } else {
                            $creditnote->setContact($tl->crmGetContact($value));
                        }
                    } else if ($contactOrCompany == self::COMPANY) {
                        if ($cachedCustomers) {
                            $creditnote->setCompany($cachedCustomers['companies'][$value]);
                        } else {
                            $creditnote->setContact($tl->crmGetCompany($value));
                        }
                    } else {
                        throw new Exception('\'For\' must be ' . self::CONTACT . ' or ' . self::COMPANY . '.');
                    }
                    break;

                case 'items':
                    foreach ($value as $creditnoteLine) {
                        $creditnote->addLine(creditnoteLine::initializeWithRawData($creditnoteLine));
                    }
                    break;

                case 'discount_info':
                    // Todo
                    break;

                default:
                    // Ignore empty values
                    if ($value == '') {
                        continue;
                    }

                    $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                    if (!method_exists(__CLASS__, $methodName)) {
                        if (Teamleader::DEBUG) {
                            // var_dump($key, $value);
                            echo $methodName;
                        }
                        throw new Exception('Unknown method (' . $methodName . ')');
                    }
                    call_user_func(array($creditnote, $methodName), $value);
            }
        }

        return $creditnote;
    }

    /**
     * This method will convert a credit note to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getInvoice()) {
            $return['invoice_id'] = $this->getInvoice()->getId();
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
