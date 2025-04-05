<?php

/**
 * Message Classs.
 *
 * @category Logging
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019-2025 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT MIT
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

namespace Ease\Logger;

/**
 * Description of Message.
 *
 * @author vitex
 */
class Message
{
    /**
     * Message body.
     */
    public string $body;

    /**
     * Message type.
     *
     * @var string info|succes|warning|error|mail|debug|event
     */
    public string $type;

    /**
     * Message producing Object.
     */
    /* mixed */
    public $caller;

    /**
     * Message Timestamp.
     */
    public int $when;

    /**
     * Basic Message class.
     *
     * @param string            $message text
     * @param string            $type    One of info|notice|debug|error
     * @param \Ease\Atom|string $caller  Origin of message
     * @param int               $when    Timestamp
     */
    public function __construct($message, $type = 'info', $caller = null, $when = null)
    {
        $this->body = $message;
        $this->type = $type;
        $this->caller = $caller;

        $this->when = null === $when ? \time() : $when;
    }

    /**
     * Unicode Symbol for given message type.
     *
     * @param string $type  Type of message
     * @param mixed  $color
     *
     * @return string
     */
    public static function getTypeUnicodeSymbol($type, $color = true)
    {
        if ($color === true) {
            switch ($type) {
                case 'mail':                       // Envelope
                    $symbol = '✉';

                    break;
                case 'warning':                    // Vykřičník v trojůhelníku
                    $symbol = '⚠';

                    break;
                case 'error':                      // Lebka
                    $symbol = '💀';

                    break;
                case 'success':                    // Kytička
                    $symbol = '🌼';

                    break;
                case 'debug':                      // Gear
                    $symbol = '⚙';

                    break;
                case 'info':
                    $symbol = 'ℹ';

                    break;
                case 'event':
                    $symbol = '👻';

                    break;
                case 'report':
                    $symbol = '📃';

                    break;

                default:                           // i v kroužku
                    $symbol = '🤔';

                    break;
            }
        } else {
            switch ($type) {
                case 'mail':                       // Envelope
                    $symbol = '✉';

                    break;
                case 'warning':                    // Vykřičník v trojůhelníku
                    $symbol = '⚠';

                    break;
                case 'error':                      // Lebka
                    $symbol = '☠';

                    break;
                case 'success':                    // Kytička
                    $symbol = '❁';

                    break;
                case 'debug':                      // Gear
                    $symbol = '⚙';

                    break;
                case 'event':
                    $symbol = '✋';

                    break;
                case 'report':
                    $symbol = '➿';

                    break;
                case 'info':                      // Gear
                    $symbol = 'ⓘ';

                    break;

                default:                           // Squared Question
                    $symbol = '🯄';

                    break;
            }
        }

        return $symbol;
    }

    /**
     * Obtain object name from caller object.
     *
     * @param object|string $caller
     */
    public static function getCallerName($caller): string
    {
        if (\is_object($caller)) {
            $callerName = method_exists($caller, 'getObjectName') ? $caller->getObjectName() : $caller::class;
        } else {
            $callerName = (string) $caller;
        }

        return $callerName;
    }
}
