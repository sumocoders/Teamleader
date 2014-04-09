<?php
/**
 * Created by PhpStorm.
 * User: tijs
 * Date: 08/04/14
 * Time: 11:47
 */

namespace SumoCoders\Teamleader\Opportunities;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Opportunities\SaleLine;

class Sale
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

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
    private $sysDepartmentId;

    /**
     * @var string
     */
    private $title;

    /**
     * @param \SumoCoders\Teamleader\Crm\Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return \SumoCoders\Teamleader\Crm\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param \SumoCoders\Teamleader\Crm\Contact $contact
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return \SumoCoders\Teamleader\Crm\Contact
     */
    public function getContact()
    {
        return $this->contact;
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
    public function setResponsibleSysClientId($responsibleSysClientId)
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
     * Is this sale linked to a contact or a company
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
     * @param SaleLine $line
     */
    public function addLine(SaleLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * This method will convert a sale to an array that can be used for an
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
