<?php

namespace SumoCoders\Teamleader\Products;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;

class Product
{
    /**
     * @var  int
     */
    private $id;

    /**
     * @var  string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $externalId;

    /**
     * @var string
     */
    private $vat;

    /**
     * @var int
     */
    private $stockAmount;

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
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
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
     * @param int $stockAmount
     */
    public function setStockAmount($stockAmount)
    {
        $this->stockAmount = $stockAmount;
    }

    /**
     * @return int
     */
    public function getStockAmount()
    {
        return $this->stockAmount;
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
     * Get a single custom field
     *
     * @param string $id
     */
    public function getCustomField($id)
    {
        return $this->customFields[$id];
    }

    /**
     * @return array
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * Initialize an Product with raw data we got from the API
     *
     * @return Product
     */
    public static function initializeWithRawData($data)
    {
        $product = new Product();

        foreach ($data as $key => $value) {
            switch ($key) {
                case substr($key, 0, 3) == 'cf_':
                    $chunks = explode('_', $key);
                    $id = end($chunks);
                    $product->setCustomField($id, $value);
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
                        call_user_func(array($product, $methodName), $value);
                    }
            }
        }

        return $product;
    }

    /**
     * This method will convert an invoice to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array(
            'name' => $this->getName(),
            'price' => $this->getPrice(),
        );

        if ($this->getExternalId()) {
            $return['external_id'] = $this->getExternalId();
        }
        if ($this->getStockAmount() !== 0) {
            $return['stock_amount'] = $this->getStockAmount();
        }
        if ($this->getVat()) {
            $return['vat'] = $this->getVat();
        }
        if ($this->getCustomFields()) {
            foreach ($this->getCustomFields() as $fieldID => $fieldValue) {
                $return['custom_field_' . $fieldID] = $fieldValue;
            }
        }

        return $return;
    }
}
