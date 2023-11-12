<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Ease\Logger;

/**
 * Description of LoggingTest
 *
 * @author vitex
 */
class LoggingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Test\Ease\Local\LoggingTester
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new \Test\Ease\Local\LoggingTester();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers Ease\Logger\Logging::getLogger
     */
    public function testGetLogger()
    {
        $this->assertInstanceOf('\Ease\Logger\Regent', $this->object->getLogger());
    }

    /**
     * @covers Ease\Logger\Logging::addStatusMessage
     */
    public function testaddStatusMessage()
    {
        $this->object->cleanSatatusMessages();
        $this->object->addStatusMessage(_('Status message add test'), 'info');
        $this->assertNotEmpty($this->object->getStatusMessages());
    }

    /**
     * @covers Ease\Logger\Logging::cleanSatatusMessages
     */
    public function testcleanStatusMessages()
    {
        $this->object->addStatusMessage('Clean Test');
        $this->object->cleanSatatusMessages();
        $this->assertEmpty($this->object->getStatusMessages(), 'Status messages cleaning');
    }

    /**
     * @covers Ease\Logger\Logging::getStatusMessages
     */
    public function testgetstatusMessages()
    {
        $this->object->cleanSatatusMessages();
        $this->object->addStatusMessage('Message');
        $this->object->addStatusMessage('Message', 'warning');
        $this->object->addStatusMessage('Message', 'debug');
        $this->object->addStatusMessage('Message', 'error');
        $this->assertEquals(4, count($this->object->getstatusMessages()));
    }

    /**
     * @covers Ease\Logger\Logging::logBanner
     */
    public function testLogBanner()
    {
        $this->object->logBanner();
        $statuses = $this->object->getStatusMessages();
        $this->assertStringContainsString(
            'EaseCore',
            end($statuses)->body
        );
    }
}
