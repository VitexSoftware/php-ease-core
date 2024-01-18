<?php

/**
 * Message Classs
 *
 * @category Logging
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019-2023 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT MIT
 *
 * PHP 7
 */

declare(strict_types=1);

namespace Ease\Logger;

/**
 * Description of Message
 *
 * @author vitex
 */
class Message
{
    /**
     * Message body
     *
     * @var string
     */
    public $body;

    /**
     * Message type
     *
     * @var string info|succes|warning|error|mail|debug|event
     */
    public $type;

    /**
     *
     * @var \Ease\Atom
     */
    public $caller;

    /**
     * Message Timestamp
     *
     * @var int
     */
    public $when;

    /**
     * Basic Message class
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
        if (is_null($when)) {
            $this->when = time();
        } else {
            $this->when = $when;
        }
    }

    /**
     * Unicode Symbol for given message type
     *
     * @param  string $type Type of message
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
     * Obtain object name from caller object
     *
     * @param object|string $caller
     *
     * @return string
     */
    public static function getCallerName($caller)
    {
        if (is_object($caller)) {
            if (method_exists($caller, 'getObjectName')) {
                $callerName = $caller->getObjectName();
            } else {
                $callerName = get_class($caller);
            }
        } else {
            $callerName = strval($caller);
        }
        return $callerName;
    }
}
