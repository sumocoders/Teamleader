<?php

namespace SumoCoders\Teamleader\Crm;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;

/**
 * Company class
 *
 * @author         Tijs Verkoyen <php-teamleader@sumocoders.be>
 * @version        1.0.0
 * @copyright      Copyright (c) SumoCoders. All rights reserved.
 * @license        BSD License
 */
class Company
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $taxCode;

    /**
     * @var string
     */
    private $localBusinessNumber;

    /**
     * @var string
     */
    private $businessType;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    /**
     * @var array
     */
    private $extraAddresses;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $iban;

    /**
     * @var string
     */
    private $bic;

    /**
     * @var string
     */
    private $language;

    /**
     * @var int
     */
    private $dateAdded;

    /**
     * @var int
     */
    private $dateEdited;

    /**
     * @var bool
     */
    private $deleted;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $vatLiability;

    /**
     * @var string
     */
    private $paymentTerm;

    /**
     * @var int
     */
    private $pricelistId;

    /**
     * @var int
     */
    private $accountManagerId;

    /**
     * @var array
     */
    private $customFields;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $businessType
     */
    public function setBusinessType($businessType)
    {
        $this->businessType = $businessType;
    }

    /**
     * @return string
     */
    public function getBusinessType()
    {
        return $this->businessType;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param array $customFields
     */
    public function setExtraAddresses($extraAddresses)
    {
        $this->extraAddresses = $extraAddresses;
    }

    /**
     * @return array
     */
    public function getExtraAddresses()
    {
        return $this->extraAddresses;
    }

    /**
     * @param int $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return int
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param int $dateEdited
     */
    public function setDateEdited($dateEdited)
    {
        $this->dateEdited = $dateEdited;
    }

    /**
     * @return int
     */
    public function getDateEdited()
    {
        return $this->dateEdited;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $language
     */
    public function setLanguageCode($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param boolean $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $taxCode
     */
    public function setTaxCode($taxCode)
    {
        $this->taxCode = $taxCode;
    }

    /**
     * @return string
     */
    public function getTaxCode()
    {
        return $this->taxCode;
    }

    /**
     * @param string $localBusinessNumber
     */
    public function setLocalBusinessNumber($localBusinessNumber)
    {
        $this->localBusinessNumber = $localBusinessNumber;
    }

    /**
     * @return string
     */
    public function getLocalBusinessNumber()
    {
        return $this->localBusinessNumber;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $zipCode
     */
    public function setZipcode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipCode;
    }

    /**
     * @param int $pricelistId
     */
    public function setPricelistId($pricelistId)
    {
        $this->pricelistId = $pricelistId;
    }

    /**
     * @return int
     */
    public function getPricelistId()
    {
        return $this->pricelistId;
    }

    /**
     * @param string $paymentTerm
     */
    public function setPaymentTerm($paymentTerm)
    {
        $this->paymentTerm = $paymentTerm;
    }

    /**
     * @return string
     */
    public function getPaymentTerm()
    {
        return $this->paymentTerm;
    }

    /**
     * @param string $vatLiability
     */
    public function setVatLiability($vatLiability)
    {
        $this->vatLiability = $vatLiability;
    }

    /**
     * @return string
     */
    public function getVatLiability()
    {
        return $this->vatLiability;
    }

    /**
     * @param int $accountManagerId
     */
    public function setAccountManagerId($accountManagerId)
    {
        $this->accountManagerId = $accountManagerId;
    }

    /**
     * @return int
     */
    public function getAccountManagerId()
    {
        return $this->accountManagerId;
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
     * @param $id
     * @return string
     * @throws Exception
     */
    public function getCustomField($id)
    {
        if (!isset($this->customFields[$id])) {
            throw new Exception(sprintf('Field with id %s not found.', $id));
        }

        return $this->customFields[$id];
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
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tag
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }

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
     * Initialize a Company with raw data we got from the API
     *
     * @param  array   $data
     * @return Company
     */
    public static function initializeWithRawData($data)
    {
        $item = new Company();

        foreach ($data as $key => $value) {
            switch ($key) {
                case substr($key, 0, 3) == 'cf_':
                    $chunks = explode('_', $key);
                    $id = end($chunks);
                    $item->setCustomField($id, $value);
                    break;

                case 'language_name':
                    break;

                case 'deleted':
                    $item->setDeleted(($value == 1));
                    break;

                default:
                    // Ignore empty values
                    if ($value != '') {
                        $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                        if (!method_exists(__CLASS__, $methodName)) {
                            if (Teamleader::DEBUG) {
                                var_dump($key, $value);
                                throw new Exception('Unknown method (' . $methodName . ')');
                            }
                        } else {
                            call_user_func(array($item, $methodName), $value);
                        }
                    }
            }
        }

        return $item;
    }

    /**
     * This method will convert a company to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        $return['name'] = $this->getName();

        if ($this->getEmail()) {
            $return['email'] = $this->getEmail();
        }
        if ($this->getTaxCode()) {
            $return['vat_code'] = $this->getTaxCode();
        }
        if ($this->getBusinessType()) {
            $return['business_type'] = $this->getBusinessType();
        }
        if ($this->getTelephone()) {
            $return['telephone'] = $this->getTelephone();
        }
        if ($this->getCountry()) {
            $return['country'] = $this->getCountry();
        }
        if ($this->getZipcode()) {
            $return['zipcode'] = $this->getZipcode();
        }
        if ($this->getCity()) {
            $return['city'] = $this->getCity();
        }
        if ($this->getStreet()) {
            $return['street'] = $this->getStreet();
        }
        if ($this->getNumber()) {
            $return['number'] = $this->getNumber();
        }
        if ($this->getLanguage()) {
            $return['language'] = $this->getLanguage();
        }
        if ($this->getAccountManagerId()) {
            $return['account_manager_id'] = $this->getAccountManagerId();
        }
        if ($this->getTags()) {
            $return['add_tag_by_string'] = implode(',', $this->getTags());
        }
        if ($this->getWebsite()) {
            $return['website'] = $this->getWebsite();
        }
        if ($this->getFax()) {
            $return['fax'] = $this->getFax();
        }
        if ($this->getLocalBusinessNumber()) {
            $return['local_business_number'] = $this->getLocalBusinessNumber();
        }
        if ($this->getDescription()) {
            $return['description'] = $this->getDescription();
        }

        if ($this->getExtraAddresses()) {
            foreach ($this->getExtraAddresses() as $type => $data) {
                foreach ($data as $key => $value) {
                    $return[$key . "_" . $type] = $value;
                }
            }
        }

        if ($this->getCustomFields()) {
            foreach ($this->getCustomFields() as $fieldID => $fieldValue) {
                $return['custom_field_' . $fieldID] = $fieldValue;
            }
        }

        return $return;
    }
}
