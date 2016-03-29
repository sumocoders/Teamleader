<?php

namespace SumoCoders\Teamleader\Subscriptions;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Subscriptions\Subscription;

/**
 * Subscription class
 *
 * @author         Sebastian De Deyne <sebastian@sumocoders.be>
 * @version        1.0.0
 * @copyright      Copyright (c) SumoCoders. All rights reserved.
 * @license        BSD License
 */
class Subscription
{
    const CONTACT = 'contact';
    const COMPANY = 'company';
    
    /**
     * @var int
     */
    private $id;

    /**
     * @var array
     */
    private $lines;

    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var int
     */
    private $sysDepartmentId;

    /**
     * @var int
     */
    private $dateStart;

    /**
     * @var string
     */
    private $repeatAfter;

    /**
     * @var string
     */
    private $title;

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the value of id.
     *
     * @param int $id the id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }

    /**
     * Gets the value of contact.
     *
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }
    
    /**
     * Sets the value of contact.
     *
     * @param Contact $contact the contact
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Gets the value of company.
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    /**
     * Sets the value of company.
     *
     * @param Company $company the company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Gets the value of sysDepartmentId.
     *
     * @return int
     */
    public function getSysDepartmentId()
    {
        return $this->sysDepartmentId;
    }
    
    /**
     * Sets the value of sysDepartmentId.
     *
     * @param int $sysDepartmentId the sys department id
     */
    public function setSysDepartmentId($sysDepartmentId)
    {
        $this->sysDepartmentId = $sysDepartmentId;
    }

    /**
     * Gets the value of dateStart.
     *
     * @return int
     */
    public function getDateStart()
    {
        return $this->date;
    }
    
    /**
     * Sets the value of dateStart.
     *
     * @param int $date the dateStart
     */
    public function setDateStart($date)
    {
        $this->date = $date;
    }

    /**
     * Gets the value of repeatAfter.
     *
     * @return string
     */
    public function getRepeatAfter()
    {
        return $this->repeatAfter;
    }
    
    /**
     * Sets the value of repeatAfter.
     *
     * @param string $repeatAfter the repeat after
     *
     * @return self
     *
     * @throws \SumoCoders\Teamleader\Exception
     */
    public function setRepeatAfter($repeatAfter)
    {
        if ($this->isValidRepeatAfterValue($repeatAfter)) {
            $this->repeatAfter = $repeatAfter;
        } else {
            throw new Exception('Invalid repeatAfter value');
        }
        
        return $this;
    }

    /**
     * Gets the value of title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the value of title.
     *
     * @param string $title the title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Is this subscription linked to a contact or a company
     *
     * @return string
     * @throws \SumoCoders\Teamleader\Exception
     */
    public function isContactOrCompany()
    {
        if ($this->getContact() && $this->getCompany()) {
            throw new Exception('You can\'t specify a contact and a company');
        }

        if ($this->getContact()) {
            return self::CONTACT;
        }
        if ($this->getCompany()) {
            return self::COMPANY;
        }

        throw new Exception('No contact or company specified');
    }

    /**
     * @param SubscriptionLine $line
     */
    public function addLine(SubscriptionLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * Check whether the repeatAfter value is valid
     *
     * @param string $value
     * @return bool
     */
    private function isValidRepeatAfterValue($value)
    {
        $values = array(
            "monthly",
            "twomonthly",
            "quarterly",
            "sixmonthly",
            "yearly",
            "twoyearly"
        );

        return in_array($value, $values);
    }

    /**
     * This method will convert an invoice to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getContact()) {
            $return['contact_or_company_id'] = $this->getContact()->getId();
        }
        if ($this->getCompany()) {
            $return['contact_or_company_id'] = $this->getCompany()->getId();
        }
        $return['contact_or_company'] = $this->isContactOrCompany();
        if ($this->getSysDepartmentId()) {
            $return['sys_department_id'] = $this->getSysDepartmentId();
        }

        $return['date_start'] = date('d/m/Y', $this->getDateStart());
        $return['repeat_after'] = $this->getRepeatAfter();
        $return['title'] = $this->getTitle();

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
