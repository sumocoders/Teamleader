<?php

/**
 * @todo Products
 * @todo Bookkeeping account
 * @todo Discount info
 */

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;

class Invoice
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

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
     * @var string
     */
    private $invoiceNrDetailed;

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
     * @var int
     */
    private $totalPriceExclVat;

    /**
     * @var int
     */
    private $totalPriceInclVat;

    /**
     * @var int
     */
    private $dueDate;

    /**
     * @var string
     */
    private $dueDateFormatted;

    /**
     * @var int
     */
    private $incassoRecallCosts;

    /**
     * @var int
     */
    private $incassoInsterestAmount;

    /**
     * @var string
     */
    private $structuredCommunication;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var array
     */
    private $customFields;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @param string $invoiceNrDetailed
     */
    public function setInvoiceNrDetailed($invoiceNrDetailed)
    {
        $this->invoiceNrDetailed = $invoiceNrDetailed;
    }

    /**
     * @return string
     */
    public function getInvoiceNrDetailed()
    {
        return $this->invoiceNrDetailed;
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
     * @param int $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return int
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param int $incassoRecallCosts
     */
    public function setIncassoRecallCosts($incassoRecallCosts)
    {
        $this->incassoRecallCosts = $incassoRecallCosts;
    }

    /**
     * @return int
     */
    public function getIncassoRecallCosts()
    {
        return $this->incassoRecallCosts;
    }

    /**
     * @param int $incassoInterestAmount
     */
    public function setIncassoInterestAmount($incassoInterestAmount)
    {
        $this->incassoInterestAmount = $incassoInterestAmount;
    }

    /**
     * @return int
     */
    public function getIncassoInterestAmount()
    {
        return $this->incassoInterestAmount;
    }

    /**
     * @param string $dueDateFormatted
     */
    public function setDueDateFormatted($dueDateFormatted)
    {
        $this->dueDateFormatted = $dueDateFormatted;
    }

    /**
     * @return string
     */
    public function getDueDateFormatted()
    {
        return $this->dueDateFormatted;
    }

    /**
     * @param string $structuredCommunication
     */
    public function setStructuredCommunication($structuredCommunication)
    {
        $this->structuredCommunication = $structuredCommunication;
    }

    /**
     * @return string
     */
    public function getStructuredCommunication()
    {
        return $this->structuredCommunication;
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
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Set a single custom field
     *
     * @param string $id
     * @param mixed  $value
     */
    public function setCustomField($id, $value)
    {
        $this->customFields[$id] = $value;
    }

    /**
     * @param array $customFields
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;
    }

    /**
     * @return array
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }


    /**
     * Initialize an Invoice with raw data we got from the API
     *
     * @param  array        $data
     * @param  Teamleader   $tl     A Teamleader instance is necessary to get the contact or company from the API
     * @return Invoice
     */
    public static function initializeWithRawData($data, $tl, $cachedCustomers = null)
    {
        $invoice = new Invoice();

        foreach ($data as $key => $value) {
            switch ($key) {
                case substr($key, 0, 3) == 'cf_':
                    $chunks = explode('_', $key);
                    $id = end($chunks);
                    $invoice->setCustomField($id, $value);
                    break;

                case 'date_paid':
                    if ($value != -1) {
                        $invoice->setDatePaid($value);
                    }
                    break;

                case 'paid':
                    $invoice->setPaid((bool) $value);
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
                    } elseif (isset($data['contact_or_company'])) {
                        $contactOrCompany = $data['contact_or_company'];
                    }

                    if (isset($data['for_id'])) {
                        $contactOrCompanyId = $data['for_id'];
                    } elseif (isset($data['contact_or_company_id'])) {
                        $contactOrCompanyId = $data['contact_or_company_id'];
                    }

                    if ($contactOrCompany == self::CONTACT) {
                        if ($cachedCustomers) {
                            $invoice->setContact($cachedCustomers['contacts'][$value]);
                        } else {
                            $invoice->setContact($tl->crmGetContact($value));
                        }
                    } elseif ($contactOrCompany == self::COMPANY) {
                        if ($cachedCustomers) {
                            $invoice->setCompany($cachedCustomers['companies'][$value]);
                        } else {
                            $invoice->setCompany($tl->crmGetCompany($value));
                        }
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

                default:
                    // Ignore empty values
                    if ($value == '') {
                        continue;
                    }

                    $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                    if (!method_exists(__CLASS__, $methodName)) {
                        if (Teamleader::DEBUG) {
                            var_dump($key, $value);
                            throw new Exception('Unknown method (' . $methodName . ')');
                        }
                    } else {
                        call_user_func(array($invoice, $methodName), $value);
                    }
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

        if ($this->getComments()) {
            $return['comments'] = $this->getComments();
        }

        if ($this->getCustomFields()) {
            foreach ($this->getCustomFields() as $customFieldId => $value) {
                $return['custom_field_' . $customFieldId] = $value;
            }
        }

        return $return;
    }
}
