<?php

/**
 * Loggingable interface
 * 
 * @category Logging
 * 
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019-2020 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT MIT
 * 
 * PHP 7
 */

namespace Ease\Logger;

/**
 * @codeCoverageIgnore
 * @author             Vítězslav Dvořák <info@vitexsoftware.cz>
 */
interface Loggingable {

    public function addToLog($caller, $message, $type = 'message');
}
