<?php

/**
 * Since Until methods.
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2024-2025 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

/**
 * This file is part of the EaseCore package.
 *
 * (c) Vítězslav Dvořák <info@vitexsoftware.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ease;

/**
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
trait datescope
{
    protected \DateTime $since;
    protected \DateTime $until;

    /**
     * Prepare processing interval.
     *
     * @throws \Exception
     *
     * @return \DatePeriod<\DateTime, \DateInterval, \DateTime>
     */
    public function setScope(string $scope): \DatePeriod
    {
        switch ($scope) {
            case 'today':
                $this->since = (new \DateTime('today'))->setTime(0, 0);
                $this->until = (new \DateTime('today'))->setTime(23, 59, 59, 999);

                break;
            case 'yesterday':
                $this->since = (new \DateTime('yesterday'))->setTime(0, 0);
                $this->until = (new \DateTime('yesterday'))->setTime(23, 59, 59, 999);

                break;
            case 'current_month':
                $this->since = (new \DateTime('first day of this month'))->setTime(0, 0);
                $this->until = (new \DateTime())->setTime(23, 59, 59, 999);

                break;
            case 'last_month':
                $this->since = (new \DateTime('first day of last month'))->setTime(0, 0);
                $this->until = (new \DateTime('last day of last month'))->setTime(23, 59, 59, 999);

                break;
            case 'last_week':
                $this->since = (new \DateTime('monday last week'))->setTime(0, 0);
                $this->until = (new \DateTime('sunday last week'))->setTime(23, 59, 59, 999);

                break;
            case 'last_two_months':
                $this->since = (new \DateTime('first day of -2 months'))->setTime(0, 0);
                $this->until = (new \DateTime('last day of last month'))->setTime(23, 59, 59, 999);

                break;
            case 'previous_month':
                $this->since = (new \DateTime('first day of -2 months'))->setTime(0, 0);
                $this->until = (new \DateTime('last day of -2 months'))->setTime(23, 59, 59, 999);

                break;
            case 'two_months_ago':
                $this->since = (new \DateTime('first day of -3 months'))->setTime(0, 0);
                $this->until = (new \DateTime('last day of -3 months'))->setTime(23, 59, 59, 999);

                break;
            case 'this_year':
                $this->since = (new \DateTime('first day of January '.date('Y')))->setTime(0, 0);
                $this->until = (new \DateTime('last day of December '.date('Y')))->setTime(23, 59, 59, 999);

                break;
            case 'January':  // 1
            case 'February': // 2
            case 'March':    // 3
            case 'April':    // 4
            case 'May':      // 5
            case 'June':     // 6
            case 'July':     // 7
            case 'August':   // 8
            case 'September':// 9
            case 'October':  // 10
            case 'November': // 11
            case 'December': // 12
                $this->since = new \DateTime('first day of '.$scope.' '.date('Y'));
                $this->until = new \DateTime('last day of '.$scope.' '.date('Y'));

                break;

            default:
                if (strstr($scope, '>')) {
                    [$begin, $end] = explode('>', $scope);
                    $this->since = new \DateTime($begin);
                    $this->until = new \DateTime($end);
                } else {
                    if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $scope)) {
                        $this->since = (new \DateTime($scope))->setTime(0, 0);
                        $this->until = (new \DateTime($scope))->setTime(23, 59, 59, 999);

                        break;
                    }

                    throw new \InvalidArgumentException('Unknown scope '.$scope);
                }

                break;
        }

        if ($scope !== 'auto' && $scope !== 'today' && $scope !== 'yesterday') {
            $this->since = $this->since->setTime(0, 0);
            $this->until = $this->until->setTime(23, 59, 59, 999);
        }

        return new \DatePeriod($this->since, new \DateInterval('P1D'), $this->until);
    }

    public function getSince(): \DateTime
    {
        return $this->since;
    }

    public function getUntil(): \DateTime
    {
        return $this->until;
    }
}
