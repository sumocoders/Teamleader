<?php

namespace SumoCoders\Teamleader\Crm;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;

/**
 * Contact class
 *
 * @author         Tijs Verkoyen <php-teamleader@sumocoders.be>
 * @version        1.0.0
 * @copyright      Copyright (c) SumoCoders. All rights reserved.
 * @license        BSD License
 */
class Contact
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $forename;

    /**
     * @var string
     */
    private $surname;

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
    private $gsm;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $website;

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
    private $zipcode;

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
    private $language;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var int
     */
    private $dob;

    /**
     * @var string
     */
    private $iban;

    /**
     * @var string
     */
    private $bic;

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
     * @var array
     */
    private $customFields;

    /**
     * @var array
     */
    private $linkedCompanyIds;

    /**
     * @var array
     */
    private $tags;

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
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode)
    {
        $this->language = $languageCode;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
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
     * @param int $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @return int
     */
    public function getDob()
    {
        return $this->dob;
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
     * @param string $forename
     */
    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    /**
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gsm
     */
    public function setGsm($gsm)
    {
        $this->gsm = $gsm;
    }

    /**
     * @return string
     */
    public function getGsm()
    {
        return $this->gsm;
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
     * @param array $linkedCompanyIds
     */
    public function setLinkedCompanyIds($linkedCompanyIds)
    {
        $this->linkedCompanyIds = $linkedCompanyIds;
    }

    /**
     * @return array
     */
    public function getLinkedCompanyIds()
    {
        return $this->linkedCompanyIds;
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
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
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
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param string $status
     */
    private function setStatus($status)
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
     * Initialize a Contact with raw data we got from the API
     *
     * @param  array   $data
     * @return Contact
     */
    public static function initializeWithRawData($data)
    {
        $item = new Contact();

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
                        call_user_func(array($item, $methodName), $value);
                    }
            }

        }

        return $item;
    }

    /**
     * This method will convert a contact to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getForename()) {
            $return['forename'] = $this->getForename();
        }
        if ($this->getSurname()) {
            $return['surname'] = $this->getSurname();
        }
        if ($this->getEmail()) {
            $return['email'] = $this->getEmail();
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
        if ($this->getGender()) {
            $return['gender'] = $this->getGender();
        }
        if ($this->getDob()) {
            $return['dob'] = $this->getDob();
        }
        if ($this->getLinkedCompanyIds()) {
            $return['linked_company_ids'] = implode(',', $this->getLinkedCompanyIds());
        }
        if ($this->getTags()) {
            $return['add_tag_by_string'] = implode(',', $this->getTags());
        }
        if ($this->getCustomFields()) {
            foreach ($this->getCustomFields() as $fieldID => $fieldValue) {
                $return['custom_field_' . $fieldID] = $fieldValue;
            }
        }
        return $return;
    }
}
