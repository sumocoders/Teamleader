<?php

namespace SumoCoders\Teamleader\Invoices;

class CreditNoteLine
{
    /**
     * @var string
     */
    private $description;

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

    // /**
    //  * @var Account
    //  */
    // private $account;

    /**
     * Gets the value of price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Sets the value of price.
     *
     * @param int $price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets the value of amount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Sets the value of amount.
     *
     * @param int $amount
     *
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Gets the value of vat.
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }
    
    /**
     * Sets the value of vat.
     *
     * @param string $vat
     *
     * @return self
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Gets the value of account.
     *
     * @return mixed
     */
    // public function getAccount()
    // {
    //     return $this->account;
    // }
    
    /**
     * Sets the value of account.
     *
     * @param mixed $account
     *
     * @return self
     */
    // public function setAccount($account)
    // {
    //     $this->account = $account;

    //     return $this;
    // }
    
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
        // $return['account_' . $index] = $this->getAccount()->getId();

        return $return;
    }
}
