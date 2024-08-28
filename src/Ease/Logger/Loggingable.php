<?php

/**
 * Loggingable interface.
 *
 * @category Logging
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019-2021 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT MIT
 *
 * PHP 7
 */

declare(strict_types=1);

/**
 * This file is part of the EaseCore package.
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ease\Logger;

/**
 * @codeCoverageIgnore
 *
 * @author             Vítězslav Dvořák <info@vitexsoftware.cz>
 */
interface Loggingable
{
    public function addToLog($caller, $message, $type = 'message');
}
