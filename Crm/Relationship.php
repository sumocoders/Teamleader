<?php

namespace SumoCoders\Teamleader\Crm;

use SumoCoders\Teamleader\Exception;
use SumoCoders\Teamleader\Teamleader;

/**
 * Class Relationship
 *
 * @package SumoCoders\Teamleader\Crm
 */
class Relationship
{
	/**
	 * @var int $id
	 */
	private $id;
	/**
	 * @var int $contact_id
	 */
	private $contact_id;
	/**
	 * @var int $company_id
	 */
	private $company_id;
	/**
	 * @var string $function
	 */
	private $function;
	/**
	 * @return int
	 */
	public function getCompanyId()
	{
		// Return stored company id
		return $this->company_id;
	}
	/**
	 * @return int
	 */
	public function getContactId()
	{
		// Return stored contact id
		return $this->contact_id;
	}
	/**
	 * @return int
	 */
	public function getId()
	{
		// Return stored id
		return $this->id;
	}
	/**
	 * @return string
	 */
	public function getFunction()
	{
		// Return stored function
		return $this->function;
	}
	/**
	 * @param int $company_id
	 */
	public function setCompanyId($company_id)
	{
		// Store company id
		$this->company_id = $company_id;
	}
	/**
	 * @param int $contact_id
	 */
	public function setContactId($contact_id)
	{
		// Store contact id
		$this->contact_id = $contact_id;
	}
	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
	/**
	 * @param string $function
	 */
	public function setFunction($function)
	{
		// Store function
		$this->function = $function;
	}
	/**
	 * @param array $data
	 * @return Relationship
	 * @throws Exception
	 */
	public static function initializeWithRawData($data)
	{
		// Create new relationship
		$item = new Relationship();
		// Loop data
		foreach ($data as $key => $value)
		{
			// Set methodname based on the key
			$method = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
			// Make sure the method exist in this current object
			if (!method_exists(__CLASS__,$method))
			{
				// Check if debug mode has been enabled
				if (Teamleader::DEBUG)
				{
					// Dump
					var_dump($key,$value);
					// Throw exception
					throw new Exception('Unknown method (' . $method . ')');
				}
			}
			else call_user_func(array($item,$method),$value);
		}
		// Return the build item
		return $item;
	}
}