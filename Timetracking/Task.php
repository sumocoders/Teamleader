<?php

namespace SumoCoders\Teamleader\Timetracking;

class Task
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $dueDate;

    /**
     * @var int
     */
    private $workerId;

    /**
     * @var int
     */
    private $taskTypeId;

    /**
     * @var string
     */
    private $for;

    /**
     * @var int
     */
    private $forId;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var string
     */
    private $priority;

    /**
     * @var int
     */
    private $id;

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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param int $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
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
     * @return string
     */
    public function getFor()
    {
        return $this->for;
    }

    /**
     * @param string $for
     */
    public function setFor($for)
    {
        $this->for = $for;
    }

    /**
     * @return int
     */
    public function getForId()
    {
        return $this->forId;
    }

    /**
     * @param int $forId
     */
    public function setForId($forId)
    {
        $this->forId = $forId;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getTaskTypeId()
    {
        return $this->taskTypeId;
    }

    /**
     * @param int $taskTypeId
     */
    public function setTaskTypeId($taskTypeId)
    {
        $this->taskTypeId = $taskTypeId;
    }

    /**
     * @return int
     */
    public function getWorkerId()
    {
        return $this->workerId;
    }

    /**
     * @param int $workerId
     */
    public function setWorkerId($workerId)
    {
        $this->workerId = $workerId;
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

        $return['description'] = $this->getDescription();
        $return['due_date'] = $this->getDueDate();
        $return['worker_id'] = $this->getWorkerId();
        $return['task_type_id'] = $this->getTaskTypeId();
        $return['duration'] = $this->getDuration();
        $return['priority'] = $this->getPriority();

        if($this->getForId() && $this->getFor()) {
            $return['for'] = $this->getFor();
            $return['for_id'] = $this->getForId();
        }

        return $return;
    }
}
