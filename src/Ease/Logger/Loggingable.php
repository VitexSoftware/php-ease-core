<?php

/**
 * Loggingable interface
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

namespace Ease\Logger;

/**
 * @codeCoverageIgnore
 * @author             Vítězslav Dvořák <info@vitexsoftware.cz>
 */
interface Loggingable
{
    public function addToLog($caller, $message, $type = 'message');
}
