<?php

namespace SumoCoders\Teamleader\Departments;

class Department
{
    /**
     * @var int The department id
     */
    private $id;

    /**
     * @var string The department name
     */
    private $name;

    /**
     * Constructor
     *
     * @param int    $id   The department id
     * @param string $name The department name
     */
    public function __construct($id, $name)
    {
        $this->id = (int) $id;
        $this->name = (string) $name;
    }

    /**
     * Initialize a department with raw data we got from the API
     *
     * @param array $data The raw data
     *
     * @return Department
     */
    public static function initializeWithRawData($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            throw new \InvalidArgumentException('A department should have an id');
        }
        if (!isset($data['name']) || empty($data['name'])) {
            throw new \InvalidArgumentException('A department should have a name');
        }

        return new static($data['id'], $data['name']);
    }

    /**
     * Getter for id
     *
     * @return int The department id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for name
     *
     * @return string The department name
     */
    public function getName()
    {
        return $this->name;
    }
}
