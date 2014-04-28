<?php

namespace SumoCoders\Teamleader\Invoices;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Invoices\InvoiceLine;

class Invoice
{
    const CONTACT = 'contact';
    const COMPANY = 'company';

    /**
     * @var  int
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
     * @var int
     */
    private $sysDepartmentId;

    /**
     * @var bool
     */
    private $paid = false;

    /**
     * @var array
     */
    private $lines;

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
     * @param int $sysDepartmentId
     */
    public function setSysDepartmentId($sysDepartmentId)
    {
        $this->sysDepartmentId = $sysDepartmentId;
    }

    /**
     * @param int $paid
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * @param int $sysDepartmentId
     */
    public function getSysDepartmentId()
    {
        return $this->sysDepartmentId;
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
     * Is this invoice linked to a contact or a company
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
     * @param InvoiceLine $line
     */
    public function addLine(InvoiceLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * Initialize an Invoice with raw data we got from the API
     *
     * @param  array   $data
     * @return Invoice
     */
    public static function initializeWithRawData($data)
    {
        // $item = new Company();

        // foreach ($data as $key => $value) {
        //     switch ($key) {
        //         case substr($key, 0, 3) == 'cf_':
        //             $chunks = explode('_', $key);
        //             $id = end($chunks);
        //             $item->setCustomField($id, $value);
        //             break;

        //         case 'language_name':
        //             break;

        //         case 'deleted':
        //             $item->setDeleted(($value == 1));
        //             break;

        //         default:
        //             // ignore empty values
        //             if ($value == '') {
        //                 continue;
        //             }

        //             $methodName = 'set' . str_replace('_', '', ucwords($key));
        //             if (!method_exists(__CLASS__, $methodName)) {
        //                 if (Teamleader::DEBUG) {
        //                     var_dump($key, $value);
        //                 }
        //                 throw new Exception('Unknown method (' . $methodName . ')');
        //             }
        //             call_user_func(array($item, $methodName), $value);
        //     }
        }

        return $item;
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
