<?php

namespace SumoCoders\Teamleader\Meetings;

use SumoCoders\Teamleader\Teamleader;

class Meeting
{
    /**
     * @var int
     */
    private $id;

    /**
     * Start date as timestamp for the meeting
     *
     * @var int|\DateTime
     */
    private $startDate;

    /**
     * userId of the attending user
     *
     * @var int
     */
    private $userId;

    /**
     * Title of the meeting
     *
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * Duration in minutes
     *
     * @var int
     */
    private $duration;

    /**
     * Milestone of the corresponding project
     *
     * @var int
     */
    private $milestoneId;

    /**
     * Meeting custom fields
     *
     * @var array
     */
    private $customFields;

    /**
     * @var array
     */
    private $attendees;

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
     * @return int
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param int|\DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        if ($startDate instanceof \DateTime) {
            $startDate = $startDate->getTimestamp();
        }
        $this->startDate = $startDate;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getMilestoneId()
    {
        return $this->milestoneId;
    }

    /**
     * @param int $milestoneId
     */
    public function setMilestoneId($milestoneId)
    {
        $this->milestoneId = $milestoneId;
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
     * @return array
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * @param array $customFields
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;
    }

    /**
     * @param $data
     * @return Meeting
     */
    public static function initializeWithRawData($data)
    {
        $meeting = new Meeting();
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
                        call_user_func(array($meeting, $methodName), $value);
                    }
            }
        }

        // These properties don't have the same name in the Teamleader api as when you set them
        $meeting->setStartDate($data['date_timestamp']);
        $meeting->setDuration($data['duration_minutes']);

        if (isset($data['attending_internal'][0]['user_id'])) {
            $meeting->setUserId($data['attending_internal'][0]['user_id']);
        }

        return $meeting;
    }

    /**
     * This method will convert a credit note to an array that can be used for an
     * API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getStartDate()) {
            $return['start_date'] = $this->getStartDate();
        }
        if ($this->getUserId()) {
            $return['user_id'] = $this->getUserId();
        }
        if ($this->getTitle()) {
            $return['title'] = $this->getTitle();
        }
        if ($this->getDescription()) {
            $return['description'] = $this->getDescription();
        }
        if ($this->getDuration()) {
            $return['duration'] = $this->getDuration();
        }
        if ($this->getMilestoneId()) {
            $return['milestone_id'] = $this->getMilestoneId();
        }
        if ($this->getCustomFields()) {
            foreach ($this->getCustomFields() as $fieldID => $fieldValue) {
                $return['custom_field_' . $fieldID] = $fieldValue;
            }
        }

        return $return;
    }
}
