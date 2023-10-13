<?php

namespace Test\Ease\Logger;

use Ease\Logger\ToSyslog;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-17 at 23:58:23.
 */
class ToSyslogTest extends ToMemoryTest
{
    /**
     * @var Ease\Logger\ToSyslog
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new ToSyslog();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * Test Constructor
     *
     * @covers \Ease\Logger\ToSyslog::__construct
     */
    public function testConstructor()
    {
        global $_SESSION;
        $classname = get_class($this->object);

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $_SESSION['testApp']['EaseMessages'] = [];
        $mock->__construct();
        $this->assertIsArray($mock::$statusMessages);
    }

    /**
     * @covers Ease\Logger\ToSyslog::addToLog
     */
    public function testAddToLog()
    {
        $this->assertIsNumeric($this->object->addToLog($this, 'test'));
    }

    /**
     * @covers Ease\Logger\ToSyslog::singleton
     */
    public function testSingleton()
    {
        $this->assertInstanceOf('Ease\Logger\ToSyslog', ToSyslog::singleton());
    }

    /**
     * @covers Ease\Logger\ToSyslog::__destruct
     */
    public function test__destruct()
    {
        $this->assertNull($this->object->__destruct());
    }

    /**
     * @covers Ease\Logger\ToSyslog::output
     */
    public function testOutput()
    {
        $this->assertEquals(8, $this->object->output('info', 'UnitTest'));
    }

    /**
     * @covers Ease\Logger\ToSyslog::finalizeMessage
     */
    public function testFinalizeMessage()
    {
        $this->assertEquals('test', $this->object->finalizeMessage(' test '));
    }
}
