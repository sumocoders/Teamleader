<?php

namespace SumoCoders\Teamleader\Deals;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;

class Deal
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

     /**
      * @var integer
      */
     private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $status;

    /**
     * @var object
     */
    private $lead;

    /**
     * @var object
     */
    private $customers;

    /**
     * @var object
     */
    private $contact_persons;

    /**
     * @var object
     */
    private $department;

    /**
     * @var object
     */
    private $estimated_value;

    /**
     * @var string
     */
    private $estimated_closing_date;

    /**
     * @var number
     */
    private $estimated_probability;

    /**
     * @var object
     */
    private $current_phase;

    /**
     * @var object
     */
    private $responsible_user;

    /**
     * @var string
     */
    private $closed_at;

    /**
     * @var object
     */
    private $source;

    /**
     * @var string
     */
    private $created_at;

    /**
     * @var string
     */
    private $updated_at;

    /**
     * @var string
     */
    private $web_url;

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
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
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
     * @param object $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return object
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param object $customer
     */
    public function setCustomer($customer)
    {
        $this->customers[] = $customer;
    }

    /**
     * @return object
     */
    public function getCustomer($index)
    {
        return $this->customers[$index];
    }

    /**
     * @param object $contact_person
     */
    public function setContactPerson($contact_person)
    {
        $this->contact_persons[] = $contact_person;
    }

    /**
     * @return object
     */
    public function getContactPerson($index)
    {
        return $this->contact_persons[$index];
    }

    /**
     * @param object $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return object
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param object $estimated_value
     */
    public function setEstimatedValue($estimated_value)
    {
        $this->estimated_value = $estimated_value;
    }

    /**
     * @return object
     */
    public function getEstimatedValue()
    {
        return $this->estimated_value;
    }

    /**
     * @param string $estimated_closing_date
     */
    public function setEstimatedClosingDate($estimated_closing_date)
    {
        $this->estimated_closing_date = $estimated_closing_date;
    }

    /**
     * @return string
     */
    public function getEstimatedClosingDate()
    {
        return $this->estimated_closing_date;
    }

    /**
     * @param number $estimated_probability
     */
    public function setEstimatedProbability($estimated_probability)
    {
        $this->estimated_probability = $estimated_probability;
    }

    /**
     * @return number
     */
    public function getEstimatedProbability()
    {
        return $this->estimated_probability;
    }

    /**
     * @param object $current_phase
     */
    public function setCurrentPhase($current_phase)
    {
        $this->current_phase = $current_phase;
    }

    /**
     * @return object
     */
    public function getCurrentPhase()
    {
        return $this->current_phase;
    }

    /**
     * @param object $responsible_user
     */
    public function setResponsibleUser($responsible_user)
    {
        $this->responsible_user = $responsible_user;
    }

    /**
     * @return object
     */
    public function getResponsibleUser()
    {
        return $this->responsible_user;
    }

    /**
     * @param string $closed_at
     */
    public function setClosedAt($closed_at)
    {
        $this->closed_at = $closed_at;
    }

    /**
     * @return string
     */
    public function getClosedAt()
    {
        return $this->closed_at;
    }

    /**
     * @param object $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return object
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $web_url
     */
    public function setWebUrl($web_url)
    {
        $this->web_url = $web_url;
    }

    /**
     * @return string
     */
    public function getWebUrl()
    {
        return $this->web_url;
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
        if ($this->getReasonRefused()) {
            $return['reason_refused'] = $this->getReasonRefused();
        }
        if ($this->getOptionalContactId()) {
            $return['optional_contact_id'] = $this->getOptionalContactId();
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
