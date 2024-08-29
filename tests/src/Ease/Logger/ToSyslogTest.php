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

use Ease\Logger\ToSyslog;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-17 at 23:58:23.
 */
#[CoversClass(ToSyslog::class)]
class ToSyslogTest extends ToMemoryTest
{
    protected Ease\Logger\ToSyslog $tosyslog;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->tosyslog = new ToSyslog();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * Test Constructor.
     *
     * @covers \Ease\Logger\ToSyslog::__construct
     */
    public function testConstructor(): void
    {
        global $_SESSION;
        $classname = \get_class($this->tosyslog);

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $_SESSION['testApp']['EaseMessages'] = [];
        $mock->__construct();
        $this->assertIsArray($mock::$statusMessages);
    }

    /**
     * @covers \Ease\Logger\ToSyslog::addToLog
     */
    public function testAddToLog(): void
    {
        $this->assertIsNumeric($this->tosyslog->addToLog($this, 'test'));
    }

    /**
     * @covers \Ease\Logger\ToSyslog::singleton
     */
    public function testSingleton(): void
    {
        $this->assertInstanceOf('Ease\Logger\ToSyslog', ToSyslog::singleton());
    }

    /**
     * @covers \Ease\Logger\ToSyslog::__destruct
     */
    public function testDestruct(): void
    {
        $this->assertNull($this->tosyslog->__destruct());
    }

    /**
     * @covers \Ease\Logger\ToSyslog::output
     */
    public function testOutput(): void
    {
        $this->assertEquals(8, $this->tosyslog->output('info', 'UnitTest'));
    }

    /**
     * @covers \Ease\Logger\ToSyslog::finalizeMessage
     */
    public function testFinalizeMessage(): void
    {
        $this->assertEquals('test', $this->tosyslog->finalizeMessage(' test '));
    }
}
