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

use PHPUnit\Framework\Attributes\CoversTrait;
use Test\Ease\Local\SandTester;
use \Ease\RecordKey;

/**
 * Description of RecordKeyTest.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
#[CoversTrait('RecordKey')]
class RecordKeyTest extends \PHPUnit\Framework\TestCase
{
    protected SandTester $sand;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->sand = new SandTester();
    }

    /**
     * @covers \Ease\RecordKey::getMyKey
     */
    public function testGetMyKey(): void
    {
        $this->sand->setmyKey('test');
        $this->assertEquals('test', $this->sand->getmyKey());
        $this->assertEquals('X', $this->sand->getmyKey(['id' => 'X']));
    }

    /**
     * @covers \Ease\RecordKey::setMyKey
     */
    public function testSetMyKey(): void
    {
        $this->sand->setmyKey('test');
        $this->assertEquals('test', $this->sand->getmyKey());
    }

    /**
     * @covers \Ease\RecordKey::getkeyColumn
     */
    public function testGetkeyColumn(): void
    {
        $this->sand->setkeyColumn('test');
        $this->assertEquals('test', $this->sand->getKeyColumn());
    }

    /**
     * @covers \Ease\RecordKey::setkeyColumn
     */
    public function testSetkeyColumn(): void
    {
        $this->sand->setkeyColumn('test');
        $this->assertEquals('test', $this->sand->getKeyColumn());
    }
}
