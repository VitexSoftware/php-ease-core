<?php

/**
 * Class to Log messages to Console.
 *
 * Output format:
 *
 * [datum] [time] [severity icon] ❲AppName⦒ObjectNamespace\Object@ID❳ message in severity color
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2016-2023 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

namespace Ease\Logger;

/**
 * Description of ToConsole
 *
 * @author vitex
 */
class ToConsole extends ToMemory implements Loggingable
{
    /**
     * Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Standard Output handle
     *
     * @var resource|false
     */
    public $stdout;

    /**
     * Standard error handle
     *
     * @var resource
     */
    public $stderr;

    /**
     * Ansi Codes
     *
     * @var array
     */
    protected static $ansiCodes = array(
        "off" => 0,
        "bold" => 1,
        "italic" => 3,
        "underline" => 4,
        "blink" => 5,
        "inverse" => 7,
        "hidden" => 8,
        "black" => 30,
        "red" => 31,
        "green" => 32,
        "yellow" => 33,
        "blue" => 34,
        "magenta" => 35,
        "cyan" => 36,
        "white" => 37,
        "black_bg" => 40,
        "red_bg" => 41,
        "green_bg" => 42,
        "yellow_bg" => 43,
        "blue_bg" => 44,
        "magenta_bg" => 45,
        "cyan_bg" => 46,
        "white_bg" => 47
    );

    /**
     * Log Status messages to console
     */
    public function __construct()
    {
        $this->stdout = fopen('php://stdout', 'w');
        $this->stderr = fopen('php://stderr', 'w');
    }

    /**
     * Set Ansi Color
     *
     * @param string $str   string to colorize
     * @param string $color color name
     *
     * @return string
     */
    public static function set($str, $color)
    {
        $colorAttrs = explode("+", $color);
        $ansiStr = "";
        foreach ($colorAttrs as $attr) {
            $ansiStr .= "\033[" . self::$ansiCodes[$attr] . "m";
        }
        $ansiStr .= $str . "\033[" . self::$ansiCodes["off"] . "m";
        return $ansiStr;
    }

    /**
     * Zapise zapravu do logu.
     *
     * @param string|object $caller  název volajícího objektu
     * @param string        $message zpráva
     * @param string        $type    typ zprávy (success|info|error|warning|*)
     *
     * @return int written message length
     */
    public function addToLog($caller, $message, $type = 'message')
    {
        $fmt = datefmt_create(
            \Ease\Locale::$localeUsed,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            date_default_timezone_get(),
            \IntlDateFormatter::GREGORIAN,
            'MM/dd/yyyy H:m:s'
        );

        $ansiMessage = $this->set(strip_tags(strval($message)), self::getTypeColor($type));
        $logLine = datefmt_format($fmt, new \DateTime()) . ' ' .
                Message::getTypeUnicodeSymbol($type) . ' ❲' .
                \Ease\Shared::appName() . '⦒' .
                Message::getCallerName($caller) . '❳ ' .
                $ansiMessage;
        $written = 0;
        switch ($type) {
            case 'error':
                $written += fputs($this->stderr, $logLine . "\n");
                break;
            default:
                $written += fputs($this->stdout, $logLine . "\n");
                break;
        }
        return $written;
    }

    /**
     * Get color code for given message
     *
     * @param string $type  mail|warning|error|debug|success|info
     */
    public static function getTypeColor($type)
    {
        switch ($type) {
            case 'mail':                       // Envelope
                $color = 'blue';
                break;
            case 'warning':                    // Vykřičník v trojůhelníku
                $color = 'yellow';
                break;
            case 'error':                      // Lebka
                $color = 'red';
                break;
            case 'debug':                      // Kytička
                $color = 'magenta';
                break;
            case 'success':                    // Kytička
                $color = 'green';
                break;
            case 'info':                    // Kytička
                $color = 'blue';
                break;
            case 'event':
                $color = 'cyan';
                break;
            default:                           // i v kroužku
                $color = 'white';
                break;
        }
        return $color;
    }

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
            self::$instance = new self();
        }
        return self::$instance;
    }
}
