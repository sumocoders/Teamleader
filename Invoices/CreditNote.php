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
     * Initialize an Invoice with raw data we got from the API
     *
     * @param  array   $data
     * @return Invoice
     */
    public static function initializeWithRawData($data)
    {
        $creditnote = new Creditnote();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'date':
                    $date = new DateTime();
                    $date->setTimestamp($value);
                    $creditnote->setDate($date);
                    break;

                case 'date_paid':
                    if ($value != 0) {
                        $datePaid = new DateTime();
                        $datePaid->setTimestamp($value);
                        $creditnote->setDatePaid($datePaid);
                    }
                    break;

                case 'paid':
                    $creditnote->setPaid((bool) $value);
                    break;

                case 'for':
                    break;

                case 'for_id':
                    // $tl = new Teamleader();
                    if ($data['for'] == self::CONTACT) {
                        // $creditnote->setContact($tl->crmGetContact($value));
                    } else if ($data['for'] == self::COMPANY) {
                        // $creditnote->setCompany($tl->crmGetCompany($value));
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

                case 'due_date':
                    $dueDate = new DateTime();
                    $dueDate->setTimestamp($value);
                    $creditnote->setDueDate($dueDate);
                    break;

                default:
                    // ignore empty values
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
