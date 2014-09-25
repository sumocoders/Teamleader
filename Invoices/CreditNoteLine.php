<?php

/**
 * @todo Bookkeeping accounts
 */

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;

class CreditnoteLine
{
    /**
     * @var string
     */
    private $description;

	/**
     * @var float
     */
    private $pricePerUnit;
    /**
     * @var int
     */
    private $price;

    /**
     * @var int
     */
    private $amount;

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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }
	
	/**
     * @param int $price_per_unit
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
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }
    
    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
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
     * Initialize a CreditnoteLine with raw data we got from the API
     *
     * @param  array   $data
     * @return Invoice
     */
    public static function initializeWithRawData($data)
    {
        $creditnoteLine = new CreditnoteLine();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'text':
                    $creditnoteLine->setDescription($value);
                    break;

                case 'vat_rate':
                    $creditnoteLine->setVat($value);
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
                        call_user_func(array($creditnoteLine, $methodName), $value);
                    }
            }
        }

        return $creditnoteLine;
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

        return $return;
    }
}
