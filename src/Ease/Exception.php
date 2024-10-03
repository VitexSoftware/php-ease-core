<?php

/**
 * Ease Exception.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 *
 * PHP 7
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
 * Exceptional use of EaseLogger to keep the message.
 *
 * @author vitex
 */
class Exception extends \Exception
{
    /**
     * Ease Framework Exception.
     *
     * @param string $message of exception
     * @param int    $code    error code
     */
    public function __construct($message, $code = 0, ?self $previous = null)
    {
        if (\Ease\Shared::cfg('DEBUG', false)) {
            $trace = $this->getTrace();
            $caller = new Molecule();
            $where = $trace[0]['class'].'::'.$trace[0]['function'];

            if (isset($trace[0]['line'])) {
                $caller->setObjectName($where.':'.$trace[0]['line']);
            } else {
                $caller->setObjectName($where);
            }

            \Ease\Shared::logger()->addStatusObject(new Logger\Message($message, 'error', $caller));
        }

        parent::__construct($message, $code, $previous);
    }
}
