<?php

namespace SumoCoders\Teamleader\Invoices\Invoice;

class CreditNote
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var array
     */
    private $lines;

    /**
     * Gets the value of invoice.
     *
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
    
    /**
     * Sets the value of invoice.
     *
     * @param Invoice $invoice the invoice
     *
     * @return self
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Gets the value of lines.
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }
    
    /**
     * Sets the value of lines.
     *
     * @param array $lines the lines
     *
     * @return self
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * @param SaleLine $line
     */
    public function addLine(SaleLine $line)
    {
        $this->lines[] = $line;
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
