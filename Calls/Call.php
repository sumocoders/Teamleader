<?php

namespace SumoCoders\Teamleader\Calls;

use DateTime;

class Call
{
    /** @var DateTime */
    private $date;

    /** @var int */
    private $userId;

    /** @var string contact/company */
    private $for;

    /** @var int */
    private $forId;

    /** @var string|null */
    private $description;

    /**
     * @param DateTime $date
     * @param int $userId
     * @param string $for
     * @param int $forId
     * @param null|string $description
     */
    public function __construct(DateTime $date, $userId, $for, $forId, $description = null)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->for = $for;
        $this->forId = $forId;
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getFor()
    {
        return $this->for;
    }

    /**
     * @return int
     */
    public function getForId()
    {
        return $this->forId;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * This method will convert a call to an array that can be used for an API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $call = array();

        if ($this->getDate()) {
            $call['due_date'] = $this->getDate()->getTimestamp();
            $call['hour'] = $this->getDate()->format('h\hi');
        }
        if ($this->getUserId()) {
            $call['user_id'] = $this->getUserId();
        }
        if ($this->getFor()) {
            $call['for'] = $this->getFor();
        }
        if ($this->getForId()) {
            $call['for_id'] = $this->getForId();
        }
        if ($this->getDescription()) {
            $call['description'] = $this->getDescription();
        }

        return $call;
    }
}
