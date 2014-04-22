<?php

namespace SumoCoders\Teamleader\Invoices;

class InvoiceLine
{
    /**
     * @var float
     */
    private $amount;

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

    // /**
    //  * @var Product
    //  */
    // private $product;

    // /**
    //  * @var Account
    //  */
    // private $account;

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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

    // /**
    //  * @param Product $product
    //  */
    // public function setProduct(Product $product)
    // {
    //     $this->product = $product;
    // }

    // /**
    //  * @return string
    //  */
    // public function getProduct()
    // {
    //     return $this->product;
    // }

    // /**
    //  * @param Account $account
    //  */
    // public function setAccount(Account $account)
    // {
    //     $this->account = $account;
    // }

    // /**
    //  * @return string
    //  */
    // public function getAccount()
    // {
    //     return $this->account;
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
        // $return['product_id_' . $index] = $this->getProduct()->getId();
        // $return['account_' . $index] = $this->getAccount()->getId();

        return $return;
    }
}
