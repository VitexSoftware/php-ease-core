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
use PHPUnit\Framework\Attributes\CoversClass;
/**
 * Description of RecordKeyTest.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class RecordKeyTest extends \PHPUnit\Framework\TestCase
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new Local\SandTester();
    }

    /**
     * @covers \Ease\RecordKey::getMyKey
     */
    public function testGetMyKey(): void
    {
        $this->object->setmyKey('test');
        $this->assertEquals('test', $this->object->getmyKey());
        $this->assertEquals('X', $this->object->getmyKey(['id' => 'X']));
    }

    /**
     * @covers \Ease\RecordKey::setMyKey
     */
    public function testSetMyKey(): void
    {
        $this->object->setmyKey('test');
        $this->assertEquals('test', $this->object->getmyKey());
    }

    /**
     * @covers \Ease\RecordKey::getkeyColumn
     */
    public function testGetkeyColumn(): void
    {
        $this->object->setkeyColumn('test');
        $this->assertEquals('test', $this->object->getKeyColumn());
    }

    /**
     * @covers \Ease\RecordKey::setkeyColumn
     */
    public function testSetkeyColumn(): void
    {
        $this->object->setkeyColumn('test');
        $this->assertEquals('test', $this->object->getKeyColumn());
    }
}
