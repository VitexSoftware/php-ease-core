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

use Ease\Anonym;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-17 at 23:58:38.
 */
#[CoversClass(Anonym::class)]
class AnonymTest extends BrickTest
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new Anonym();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers \Ease\Anonym::setObjectName
     */
    public function testSetObjectName(): void
    {
        $this->assertEquals('Test', $this->object->setObjectName('Test'));
        unset($_SERVER['REMOTE_USER']);
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->assertEquals(
            'Ease\Anonym@127.0.0.1',
            $this->object->setObjectName(),
        );
        $_SERVER['REMOTE_USER'] = 'tester';
        $this->assertEquals(
            'Ease\Anonym@127.0.0.1 [tester]',
            $this->object->setObjectName(),
        );
    }

    /**
     * @covers \Ease\Anonym::remoteToIdentity
     */
    public function testRemoteToIdentity(): void
    {
        $_SERVER['REMOTE_USER'] = null;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->assertEquals('127.0.0.1', $this->object->remoteToIdentity());
        $_SERVER['REMOTE_USER'] = 'tester';
        $this->assertEquals(
            '127.0.0.1 [tester]',
            $this->object->remoteToIdentity(),
        );
    }

    /**
     * @covers \Ease\Anonym::getUserLevel
     */
    public function testGetUserLevel(): void
    {
        $this->assertEquals(-1, $this->object->getUserLevel());
    }

    /**
     * @covers \Ease\Anonym::getUserID
     */
    public function testGetUserID(): void
    {
        $this->assertEquals(0, $this->object->getUserID());
    }

    /**
     * @covers \Ease\Anonym::getUserLogin
     */
    public function testGetUserLogin(): void
    {
        $this->assertEmpty($this->object->getUserLogin());
    }

    /**
     * @covers \Ease\Anonym::isLogged
     */
    public function testIsLogged(): void
    {
        $this->assertFalse($this->object->isLogged());
    }

    /**
     * @covers \Ease\Anonym::getSettingValue
     */
    public function testGetSettingValue(): void
    {
        $this->assertNull($this->object->getSettingValue('test'));
    }

    /**
     * @covers \Ease\Anonym::setSettingValue
     */
    public function testSetSettingValue(): void
    {
        $this->object->setSettingValue('test', true);
        $this->assertEquals(['test' => true], $this->object->settings);
    }

    /**
     * @covers \Ease\Anonym::getUserEmail
     */
    public function testGetUserEmail(): void
    {
        $this->assertEmpty($this->object->getUserEmail());
    }

    /**
     * @covers \Ease\Anonym::getPermission
     */
    public function testGetPermission(): void
    {
        $this->assertNull($this->object->getPermission('test'));
    }

    /**
     * @covers \Ease\Anonym::logout
     */
    public function testLogout(): void
    {
        $this->assertTrue($this->object->logout());
    }
}
