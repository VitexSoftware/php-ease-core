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

namespace Test\Ease;

use Ease\Shared;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-17 at 23:58:11.
 */
class SharedTest extends AtomTest
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = \Ease\Shared::instanced();
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
     * @covers \Ease\Shared::__construct
     */
    public function testConstructor(): void
    {
        global $_SESSION;
        $classname = \get_class($this->object);

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $_SESSION['unitTest']['EaseMessages'] = [0 => 'a', 1 => 'b'];
        $mock->__construct();

        $this->assertIsArray($mock::$statusMessages);
    }

    /**
     * @covers \Ease\Shared::msgFile
     */
    public function testMsgFile(): void
    {
        $this->assertEquals(sys_get_temp_dir().'/unitTestEaseStatusMessages'.posix_getuid().'.ser', \Ease\Shared::msgFile());
    }

    /**
     * @covers \Ease\Shared::appName
     */
    public function testAppName(): void
    {
        $this->assertEquals('unitTest', \Ease\Shared::appName());
    }

    /**
     * @covers \Ease\Shared::singleton
     */
    public function testSingleton(): void
    {
        $this->assertInstanceOf('\Ease\Shared', \Ease\Shared::singleton());
    }

    /**
     * @covers \Ease\Shared::instanced
     */
    public function testInstanced(): void
    {
        $this->assertInstanceOf('\Ease\Shared', \Ease\Shared::instanced());
    }

    /**
     * @covers \Ease\Shared::getConfigValue
     * @covers \Ease\Shared::setConfigValue
     */
    public function testSetConfigValue(): void
    {
        $this->object->setConfigValue('test', true);
        $this->assertTrue($this->object->getConfigValue('test'));
    }

    /**
     * @covers \Ease\Shared::logger
     */
    public function testLogger(): void
    {
        $this->assertInstanceOf('\Ease\Logger\Regent', \Ease\Shared::logger());
    }

    /**
     * @covers \Ease\Shared::loadConfig
     */
    public function testLoadConfig(): void
    {
        if (file_exists('.env')) {
            $env = '.env';
            $json = 'configtest.json';
        } else {
            $env = 'tests/.env';
            $json = 'tests/configtest.json';
        }

        $this->object->loadConfig($env, true);
        $this->assertEquals(['KEY' => 'VALUE', 'FOO' => 'BAR', 'debug' => 'true', 'test' => true], $this->object->configuration);
        $this->object->loadConfig($json, true);
        $this->assertArrayHasKey('opt', $this->object->configuration);
        $this->assertTrue(\defined('KEY'));
        $this->assertEquals('optvalue', $this->object->getConfigValue('opt'));
        $this->assertEquals('keyvalue', $this->object->getConfigValue('KEY'));
        $this->expectException('Exception');
        $this->object->loadConfig('tests/Bootstrap.php', true);
    }

    /**
     * @covers \Ease\Shared::init
     */
    public function testInit(): void
    {
        putenv('DB_CONNECTION=sqlite3');
        $this->assertTrue(\Ease\Shared::init(['DB_CONNECTION'], null, false));
    }

    /**
     * @covers \Ease\Shared::saveStatusMessages
     */
    public function testSaveStatusMessages(): void
    {
        $this->assertIsInt($this->object->saveStatusMessages());
    }

    /**
     * @covers \Ease\Shared::saveStatusMessages
     */
    public function testLoadStatusMessages(): void
    {
        $this->assertIsArray($this->object->loadStatusMessages());
    }

    /**
     * @covers \Ease\Shared::user
     */
    public function testUser(): void
    {
        $this->assertInstanceOf('\Ease\User', \Ease\Shared::user(null, '\Ease\User'));
    }
}
