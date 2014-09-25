<?php

namespace SumoCoders\Teamleader\Deals;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Deals\DealLine;

class Deal
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

    /**
     * @var integer
     */
     private $id;

    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $lines;

    /**
     * @var int
     */
    private $responsibleSysClientId;

    /**
     * @var string
     */
    private $source;

    /**
     * @var int
     */
    private $sourceId;

    /**
     * @var int
     */
    private $sysDepartmentId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $offerteNr;

    /**
     * @var int
     */
    private $contactId;

    /**
     * @var int
     */
    private $companyId;

    /**
     * @var int
     */
    private $phaseId;

    /**
     * @var int
     */
    private $totalPriceExclVat;

    /**
     * @var array
     */
    private $customFields;

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

     /**
      * @return integer
      */
    public function getId()
    {
        return $this->id;
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
     * @param array $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param int $responsibleSysClientId
     */
    public function setResponsibleUserId($responsibleSysClientId)
    {
        $this->responsibleSysClientId = $responsibleSysClientId;
    }

    /**
     * @return int
     */
    public function getResponsibleSysClientId()
    {
        return $this->responsibleSysClientId;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param integer $source
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return integer
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param int $sysDepartmentId
     */
    public function setSysDepartmentId($sysDepartmentId)
    {
        $this->sysDepartmentId = $sysDepartmentId;
    }

    /**
     * @return int
     */
    public function getSysDepartmentId()
    {
        return $this->sysDepartmentId;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getOfferteNr()
    {
        return $this->offerteNr;
    }

    /**
     * @param int
     */
    public function setOfferteNr($nr)
    {
        $this->offerteNr = $nr;
    }

    /**
     * @return int
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param int
     */
    public function setCompanyId($id)
    {
        $this->companyId = $id;
    }

    /**
     * @return int
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * @param int
     */
    public function setContactId($id)
    {
        $this->contactId = $id;
    }

    /**
     * @return int
     */
    public function getPhaseId()
    {
        return $this->phaseId;
    }

    /**
     * @param int
     */
    public function setPhaseId($id)
    {
        $this->phaseId = $id;
    }

    /**
     * @return int
     */
    public function getTotalPriceExclVat()
    {
        return $this->totalPriceExclVat;
    }

    /**
     * @param int
     */
    public function setTotalPriceExclVat($price)
    {
        $this->totalPriceExclVat = $price;
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
     * Is this deal linked to a contact or a company
     *
     * @return string
     * @throws \SumoCoders\Teamleader\Exception
     */
    public function isContactOrCompany()
    {
        if (isset($this->companyId) && isset($this->contactId)) {
            throw new Exception('You can\'t specify a contact and a company');
        }

        if ($this->getContactId()) {
            return self::CONTACT;
        }
        if ($this->getCompanyId()) {
            return self::COMPANY;
        }

        throw new Exception('No contact or company specified');
    }

    /**
     * @param DealLine $line
     */
    public function addLine(DealLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * This method will convert a deal to an array that can be used for an
     * API-request
     *
     * @param bool $add create an array for an insert or update api call
     *
     * @return array
     */
    public function toArrayForApi($add = true)
    {
        $return = array();

        if ($this->getContactId()) {
            $return['contact_or_company_id'] = $this->getContactId();
        }
        if ($this->getCompanyId()) {
            $return['contact_or_company_id'] = $this->getCompanyId();
        }
        // Contact or company only need to be specified on an insert function
        if ($add) {
            $return['contact_or_company'] = $this->isContactOrCompany();
        }
        if ($this->getDescription()) {
            $return['description'] = $this->getDescription();
        }
        if ($this->getResponsibleSysClientId()) {
            $return['responsible_sys_client_id'] = $this->getResponsibleSysClientId();
        }
        if ($this->getSource()) {
            $return['source'] = $this->getSource();
        }
        if ($this->getSysDepartmentId()) {
            $return['sys_department_id'] = $this->getSysDepartmentId();
        }
        if ($this->getTitle()) {
            $return['title'] = $this->getTitle();
        }
        if ($this->getPhaseId()) {
                $return['phase_id'] = $this->getPhaseId();
        }
        if ($this->getCustomFields()) {
            foreach ($this->getCustomFields() as $fieldID => $fieldValue) {
                $return['custom_field_' . $fieldID] = $fieldValue;
            }
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


/**
     * Initialize a deal with raw data we got from the API
     *
     * @param  array   $data
     * @return deal
     */
    public static function initializeWithRawData($data)
    {
        $item = new Deal();

        foreach ($data as $key => $value) {
            switch ($key) {
                case substr($key, 0, 3) == 'cf_':
                    $chunks = explode('_', $key);
                    $id = end($chunks);
                    $item->setCustomField($id, $value);
                    break;

                case 'for_id':
                case 'language_name':
                    break;

                case 'deleted':
                    $item->setDeleted(($value == 1));
                    break;

                case 'for':
                    if ($value === 'company') {
                        $item->setCompanyId($data['for_id']);
                    } else {
                        $item->setContactId($data['for_id']);
                    }
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
                        call_user_func(array($item, $methodName), $value);
                    }
            }
        }

        return $item;
    }
}
