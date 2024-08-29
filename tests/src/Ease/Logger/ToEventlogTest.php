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

use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-17 at 23:58:23.
 */
#[CoversClass(ToEventlog::class)]
class ToEventlogTest extends ToSyslogTest
{
    protected Ease\Logger\ToSyslog $toeventlog;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->toeventlog = new \Ease\Logger\ToEventlog();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers \Ease\Logger\ToEventlog::finalizeMessage
     */
    public function testFinalizeMessage(): void
    {
        $this->assertEquals('zlutoucky kun', $this->toeventlog->finalizeMessage('žluťoučký kůň'));
    }
}
