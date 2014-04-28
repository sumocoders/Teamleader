<?php

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Invoices\CreditnoteLine;

class Creditnote
{
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
     * @param SaleLine $line
     */
    public function addLine(CreditnoteLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * Initialize a Creditnote with raw data we got from the API
     *
     * @param  array   $data
     * @return Creditnote
     */
    public static function initializeWithRawData($data)
    {
        // $item = new Company();

        // foreach ($data as $key => $value) {
        //     switch ($key) {
        //         case substr($key, 0, 3) == 'cf_':
        //             $chunks = explode('_', $key);
        //             $id = end($chunks);
        //             $item->setCustomField($id, $value);
        //             break;

        //         case 'language_name':
        //             break;

        //         case 'deleted':
        //             $item->setDeleted(($value == 1));
        //             break;

        //         default:
        //             // ignore empty values
        //             if ($value == '') {
        //                 continue;
        //             }

        //             $methodName = 'set' . str_replace('_', '', ucwords($key));
        //             if (!method_exists(__CLASS__, $methodName)) {
        //                 if (Teamleader::DEBUG) {
        //                     var_dump($key, $value);
        //                 }
        //                 throw new Exception('Unknown method (' . $methodName . ')');
        //             }
        //             call_user_func(array($item, $methodName), $value);
        //     }
        // }

        // return $item;
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
