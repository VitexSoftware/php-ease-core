<?php

/**
 * Class to Log messages.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
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

class ToMemory extends \Ease\Atom implements Loggingable
{
    /**
     * Number of messages to keep.
     */
    public int $capacity = 1024;

    /**
     * Předvolená metoda logování.
     */
    public string $logType = 'memory';

    /**
     * Adresář do kterého se zapisují logy.
     *
     * @var string dirpath
     */
    public string $logPrefix = '';

    /**
     * Messages live here.
     *
     * @var array<string, array<string, string>>
     */
    public static array $statusMessages = [];

    /**
     * Hodnoty pro obarvování logu.
     *
     * @var array<string, string>
     */
    public array $logStyles = [
        'notice' => 'color: black;',
        'success' => 'color: #2C5F23;',
        'message' => 'color: #2C5F23;',
        'warning' => 'color: #AB250E;',
        'error' => 'color: red;',
        'debug' => 'font-style: italic;',
        'report' => 'font-wight: bold;',
        'info' => 'color: blue;',
    ];

    /**
     * Odkaz na vlastnící objekt.
     */
    public \Ease\Sand $parentObject;

    /**
     * ID naposledy ulozene zpravy.
     *
     * @var int unsigned
     */
    private int $messageID = 0;

    /**
     * Saves obejct instace (singleton...).
     */
    private static self $instance;

    public static function singleton(): self
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * Zapise zapravu do logu.
     *
     * @param object|string $caller  název volajícího objektu
     * @param string        $message zpráva
     * @param string        $type    typ zprávy (success|info|error|warning|*)
     *
     * @return int logged message length
     */
    public function addToLog($caller, $message, $type = 'message')
    {
        ++$this->messageID;
        self::$statusMessages[$type][Message::getCallerName($caller).$this->messageID] = $message;

        if (\count(self::$statusMessages[$type]) > $this->capacity) {
            self::$statusMessages[$type] = \array_slice(self::$statusMessages[$type], $this->capacity);
        }

        return \strlen($message);
    }

    /**
     * Vrací styl logování.
     *
     * @param string $logType typ logu warning|info|success|error|notice|*
     *
     * @return string
     */
    public function getLogStyle($logType = 'notice')
    {
        if (\array_key_exists($logType, $this->logStyles)) {
            return $this->logStyles[$logType];
        }

        return '';
    }
}
