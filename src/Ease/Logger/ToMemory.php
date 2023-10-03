<?php

/**
 * Class to Log messages.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

namespace Ease\Logger;

class ToMemory extends \Ease\Atom implements Loggingable
{
    /**
     * Předvolená metoda logování.
     *
     * @var string
     */
    public $logType = 'memory';

    /**
     * Adresář do kterého se zapisují logy.
     *
     * @var string dirpath
     */
    public $logPrefix = null;

    /**
     * Messages live here
     * @var array
     */
    public static $statusMessages = [];

    /**
     * Hodnoty pro obarvování logu.
     *
     * @var array
     */
    public $logStyles = [
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
     *
     * @var \Ease\Sand
     */
    public $parentObject = null;

    /**
     * ID naposledy ulozene zpravy.
     *
     * @var int unsigned
     */
    private $messageID = 0;

    /**
     * Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Pri vytvareni objektu pomoci funkce singleton (ma stejne parametry, jako
     * konstruktor) se bude v ramci behu programu pouzivat pouze jedna jeho
     * instance (ta prvni).
     *
     * @link http://docs.php.net/en/language.oop5.patterns.html Dokumentace a
     * priklad
     */
    public static function singleton()
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
     * @param string|object $caller  název volajícího objektu
     * @param string $message zpráva
     * @param string $type    typ zprávy (success|info|error|warning|*)
     *
     * @return int logged message length
     */
    public function addToLog($caller, $message, $type = 'message')
    {
        ++$this->messageID;
        self::$statusMessages[$type][Message::getCallerName($caller) . $this->messageID] = $message;
        return strlen($message);
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
        if (key_exists($logType, $this->logStyles)) {
            return $this->logStyles[$logType];
        } else {
            return '';
        }
    }
}
