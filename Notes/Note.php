<?php

namespace SumoCoders\Teamleader\Notes;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;
use SumoCoders\Teamleader\Crm\Contact;
use SumoCoders\Teamleader\Crm\Company;
use SumoCoders\Teamleader\Deals\Deal;

class Note
{
    /**
     * @var string
     */
    private $objectType;

    /**
     * @var int
     */
    private $objectId;

    /**
    * @var string
    */
    private $noteTitle;

    /**
    * @var string
    */
    private $noteExtraInformation;

    /**
    * @var string
    */
    private $noteExtraInformationType;


    /**
     * @param string $noteExtraInformation
     */
    public function setNoteExtraInformation($noteExtraInformation)
    {
        $this->noteExtraInformation = $noteExtraInformation;
    }

    /**
     * @return string
     */
    public function getNoteExtraInformation()
    {
        return $this->noteExtraInformation;
    }

    /**
     * @param string $noteExtraInformationType
     */
    public function setNoteExtraInformationType($noteExtraInformationType)
    {
        $this->noteExtraInformationType = $noteExtraInformationType;
    }

    /**
     * @return string
     */
    public function getNoteExtraInformationType()
    {
        return $this->noteExtraInformationType;
    }

    /**
     * @param string $noteTitle
     */
    public function setNoteTitle($noteTitle)
    {
        $this->noteTitle = $noteTitle;
    }

    /**
     * @return string
     */
    public function getNoteTitle()
    {
        return $this->noteTitle;
    }

    /**
     * @param int $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $objectType
     */
    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;
    }

    /**
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }


    /**
     * This method will convert a deal to an array that can be used for an
     * API-request
     *
     * @param bool $add create an array for an insert or update api call
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getObjectType()) {
            $return['object_type'] = $this->getObjectType();
        }
        if ($this->getObjectId()) {
            $return['object_id'] = $this->getObjectId();
        }
        if ($this->getNoteTitle()) {
            $return['note_title'] = $this->getNoteTitle();
        }
        if ($this->getNoteExtraInformation()) {
            $return['note_extra_information'] = $this->getNoteExtraInformation();
        }
        if ($this->getNoteExtraInformationType()) {
            $return['note_extra_information_type'] = $this->getNoteExtraInformationType();
        }

        return $return;
    }


/**
     * Initialize a note with raw data we got from the API
     *
     * @param  array   $data
     * @return note
     */
    public static function initializeWithRawData($data)
    {
        $item = new Note();

        foreach ($data as $key => $value) {
            switch ($key) {
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
                    break;
            }
        }

        return $item;
    }
}
