<?php

namespace SumoCoders\Teamleader\tests;

require_once '../../../autoload.php';
require_once 'config.php';

use \SumoCoders\Teamleader\Teamleader;

class TeamleaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Teamleader
     */
    private $teamleader;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->teamleader = new Teamleader(API_GROUP, API_KEY);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->teamleader = null;
        parent::tearDown();
    }

    /**
     * Tests Teamleader->getTimeOut()
     */
    public function testGetTimeOut()
    {
        $this->teamleader->setTimeOut(5);
        $this->assertEquals(5, $this->teamleader->getTimeOut());
    }

    /**
     * Tests Teamleader->getUserAgent()
     */
    public function testGetUserAgent()
    {
        $this->teamleader->setUserAgent('testing/1.0.0');
        $this->assertEquals(
            'PHP Teamleader/' . Teamleader::VERSION . ' testing/1.0.0',
            $this->teamleader->getUserAgent()
        );
    }
}
