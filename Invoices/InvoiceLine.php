<?php

/**
 * @todo Bookkeeping accounts
 */

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Products\Product;

class InvoiceLine
{
    /**
     * @var float
     */
    private $amount;
    /**
     * @var float
     */
    private $pricePerUnit;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $vat;

    /**
     * @var float
     */
    private $lineTotalExclVat;

    /**
     * @var float
     */
    private $lineTotalInclVat;

    /**
     * @var int
     */
    private $productId;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param float $price_per_unit
     */
    public function setPricePerUnit($price_per_unit)
    {
        $this->pricePerUnit = $price_per_unit;
    }

    /**
     * @return float
     */
    public function getPricePerUnit()
    {
        return $this->pricePerUnit;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param float $lineTotalExclVat
     */
    public function setLineTotalExclVat($lineTotalExclVat)
    {
        $this->lineTotalExclVat = $lineTotalExclVat;
    }

    /**
     * @return float
     */
    public function getLineTotalExclVat()
    {
        return $this->lineTotalExclVat;
    }

    /**
     * @param float $lineTotalInclVat
     */
    public function setLineTotalInclVat($lineTotalInclVat)
    {
        $this->lineTotalInclVat = $lineTotalInclVat;
    }

    /**
     * @return float
     */
    public function getLineTotalInclVat()
    {
        return $this->lineTotalInclVat;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->productId = $product->getId();
    }

    /**
     * @param int $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Initialize an Invoiceline with raw data we got from the API
     *
     * @param  array   $data
     * @return Invoice
     */
    public static function initializeWithRawData($data)
    {
        $invoiceLine = new InvoiceLine();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'text':
                    $invoiceLine->setDescription($value);
                    break;

                case 'vat_rate':
                    $invoiceLine->setVat($value);
                    break;

                case 'account':
                    // Todo
                    break;

                default:
                    // ignore empty values
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
                        call_user_func(array($invoiceLine, $methodName), $value);
                    }
            }
        }

        return $invoiceLine;
    }

    /**
     * This method will convert a sale to an array that can be used for an
     * API-request
     *
     * @param  int   $index
     * @return array
     */
    public function toArrayForApi($index = 1)
    {
        $return = array();
        $return['description_' . $index] = $this->getDescription();
        $return['price_' . $index] = $this->getPrice();
        $return['amount_' . $index] = $this->getAmount();
        $return['vat_' . $index] = $this->getVat();
        $return['subtitle_' . $index] = $this->getSubtitle();

        if ($this->getProductId()) {
            $return['product_id_' . $index] = $this->getProductId();
        }
        // $return['account_' . $index] = $this->getAccount()->getId();

        return $return;
    }
}
