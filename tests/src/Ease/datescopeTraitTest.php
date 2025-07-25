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

use Ease\datescope;
use PHPUnit\Framework\TestCase;

class datescopeTraitTest extends TestCase
{
    use datescope;

    /**
     * Helper to expose protected properties for assertions.
     */
    public function getSince(): \DateTime
    {
        return $this->since;
    }

    public function getUntil(): \DateTime
    {
        return $this->until;
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testYesterdayScope(): void
    {
        $period = $this->setScope('yesterday');
        $since = $this->getSince();
        $until = $this->getUntil();
        $this->assertEquals((new \DateTime('yesterday'))->setTime(0, 0), $since);
        $this->assertEquals((new \DateTime('yesterday'))->setTime(23, 59, 59, 999), $until);
        $this->assertInstanceOf(\DatePeriod::class, $period);
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testCurrentMonthScope(): void
    {
        $period = $this->setScope('current_month');
        $since = $this->getSince();
        $until = $this->getUntil();
        $this->assertEquals((new \DateTime('first day of this month'))->setTime(0, 0), $since);
        $this->assertEquals((new \DateTime())->setTime(23, 59, 59, 999), $until);
        $this->assertInstanceOf(\DatePeriod::class, $period);
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testLastMonthScope(): void
    {
        $period = $this->setScope('last_month');
        $since = $this->getSince();
        $until = $this->getUntil();
        $this->assertEquals((new \DateTime('first day of last month'))->setTime(0, 0), $since);
        $this->assertEquals((new \DateTime('last day of last month'))->setTime(23, 59, 59, 999), $until);
        $this->assertInstanceOf(\DatePeriod::class, $period);
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testMonthNameScope(): void
    {
        $period = $this->setScope('July');
        $since = $this->getSince();
        $until = $this->getUntil();
        $this->assertEquals((new \DateTime('first day of July '.date('Y')))->setTime(0, 0), $since);
        $this->assertEquals((new \DateTime('last day of July '.date('Y')))->setTime(23, 59, 59, 999), $until);
        $this->assertInstanceOf(\DatePeriod::class, $period);
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testDateStringScope(): void
    {
        $date = '2025-07-17';
        $period = $this->setScope($date);
        $since = $this->getSince();
        $until = $this->getUntil();
        $this->assertEquals((new \DateTime($date))->setTime(0, 0), $since);
        $this->assertEquals((new \DateTime($date))->setTime(23, 59, 59, 999), $until);
        $this->assertInstanceOf(\DatePeriod::class, $period);
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testCustomRangeScope(): void
    {
        $period = $this->setScope('2025-07-01>2025-07-17');
        $since = $this->getSince();
        $until = $this->getUntil();
        $this->assertEquals((new \DateTime('2025-07-01'))->setTime(0, 0), $since);
        $this->assertEquals((new \DateTime('2025-07-17'))->setTime(23, 59, 59, 999), $until);
        $this->assertInstanceOf(\DatePeriod::class, $period);
    }

    /**
     * @covers \Ease\datescope::setScope
     */
    public function testUnknownScopeThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->setScope('not_a_scope');
    }
}
