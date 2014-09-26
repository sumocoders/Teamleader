<?php

namespace SumoCoders\Teamleader\Users;

class User
{
    /**
     * @var int The user id
     */
    private $id;

    /**
     * @var string The user name
     */
    private $name;

    /**
     * @var string The user email
     */
    private $email;

    /**
     * @var string The user gsm
     */
    private $gsm;

    /**
     * @var string The user telephone
     */
    private $telephone;

    /**
     * Constructor
     *
     * @param int    $id        The user id
     * @param string $name      The user name
     * @param string $email     The user email
     * @param string $gsm       The user gsm
     * @param string $telephone The user telephone
     */
    public function __construct($id, $name, $email, $gsm = null, $telephone = null)
    {
        $this->id = (int) $id;
        $this->name = (string) $name;
        $this->email = (string) $email;
        $this->gsm = (string) $gsm;
        $this->telephone = (string) $telephone;
    }

    /**
     * Initialize a user with raw data we got from the API
     *
     * @param array $data The raw data
     *
     * @return user
     */
    public static function initializeWithRawData($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            throw new \InvalidArgumentException('A user should have an id');
        }
        if (!isset($data['name']) || empty($data['name'])) {
            throw new \InvalidArgumentException('A user should have a name');
        }
        if (!isset($data['email']) || empty($data['email'])) {
            throw new \InvalidArgumentException('A user should have an email');
        }

        return new static($data['id'], $data['name'], $data['email'], $data['gsm'] ?: null, $data['telephone'] ?: null);
    }

    /**
     * Getter for id
     *
     * @return int The user id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for name
     *
     * @return string The user name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for email
     *
     * @return string The user email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Getter for gsm
     *
     * @return string The user gsm
     */
    public function getGsm()
    {
        return $this->gsm;
    }

    /**
     * Getter for telephone
     *
     * @return string The user telephone
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
}
