<?php

declare(strict_types=1);

/**
 * This file is part of the EaseCore package.
 *
 * (c) Vítězslav Dvořák <info@vitexsoftware.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Ease\Logger;

/**
 * Description of LoggingTest.
 *
 * @author vitex
 */
class LoggingTest extends \PHPUnit\Framework\TestCase
{
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
     * @covers \Ease\Logger\Logging::getLogger
     */
    public function testGetLogger(): void
    {
        $this->assertInstanceOf('\Ease\Logger\Regent', $this->object->getLogger());
    }

    /**
     * @covers \Ease\Logger\Logging::addStatusMessage
     */
    public function testaddStatusMessage(): void
    {
        $this->object->cleanSatatusMessages();
        $this->object->addStatusMessage(_('Status message add test'), 'info');
        $this->assertNotEmpty($this->object->getStatusMessages());
    }

    /**
     * @covers \Ease\Logger\Logging::cleanSatatusMessages
     */
    public function testcleanStatusMessages(): void
    {
        $this->object->addStatusMessage('Clean Test');
        $this->object->cleanSatatusMessages();
        $this->assertEmpty($this->object->getStatusMessages(), 'Status messages cleaning');
    }

    /**
     * @covers \Ease\Logger\Logging::getStatusMessages
     */
    public function testgetstatusMessages(): void
    {
        $this->object->cleanSatatusMessages();
        $this->object->addStatusMessage('Message');
        $this->object->addStatusMessage('Message', 'warning');
        $this->object->addStatusMessage('Message', 'debug');
        $this->object->addStatusMessage('Message', 'error');
        $this->assertCount(4, $this->object->getstatusMessages());
    }

    /**
     * @covers \Ease\Logger\Logging::logBanner
     */
    public function testLogBanner(): void
    {
        $this->object->logBanner();
        $statuses = $this->object->getStatusMessages();
        $this->assertStringContainsString(
            'EaseCore',
            end($statuses)->body,
        );
    }
}
