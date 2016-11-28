<?php

namespace SumoCoders\Teamleader\CustomFields;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;

/**
 * CustomField class
 *
 * @author         Ricardo <php-teamleader@sumocoders.be>
 * @version        1.0.0
 * @copyright      Copyright (c) SumoCoders. All rights reserved.
 * @license        BSD License
 */
class CustomField
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
    private $for;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $group;

    /**
     * @param string $id
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name= $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $for
     */
    public function setFor($for)
    {
        $this->for = $for;
    }

    /**
     * @return string
     */
    public function getFor()
    {
        return $this->for;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Initialize a CustomField with raw data we got from the API
     *
     * @param  array   $data
     * @return CustomField
     */
    public static function initializeWithRawData($data)
    {
        $item = new CustomField();
        
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'id':
                    $item->setId($value);
                    break;
                case 'name':
                    $item->setName($value);
                    break;
                case 'for':
                    $item->setFor($value);
                    break;
                case 'type':
                    $item->setType($value);
                    break;
                case 'group':
                    $item->setGroup($value);
                    break;
                default:
                    // ignore empty values
                    if ($value === '') {
                        continue;
                    }
            }
        }
        return $item;
    }

    /**
     * This method will convert a custom field to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getId()) {
            $return['id'] = $this->getId();
        }
        if ($this->getName()) {
            $return['name'] = $this->getName();
        }
        if ($this->getFor()) {
            $return['for'] = $this->getFor();
        }
        if ($this->getType()) {
            $return['type'] = $this->getType();
        }
        if ($this->getGroup()) {
            $return['group'] = $this->getGroup();
        }

        return $return;
    }
}
