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

use Ease\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-12-29 at 21:56:53.
 */

#[CoversClass(Exception::class)]
 class ExceptionTest extends \PHPUnit\Framework\TestCase
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new Exception('test');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers \Ease\Exception::__construct
     */
    public function testConstructor(): void
    {
        $classname = \get_class($this->object);

        $this->expectException('\Ease\Exception');
        $this->expectExceptionMessage('test');

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $mock->__construct('test');

        throw new Exception('test');
    }
}
