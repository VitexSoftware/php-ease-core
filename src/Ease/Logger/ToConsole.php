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
 * Console logger that outputs formatted log messages to stdout/stderr with ANSI color codes.
 *
 * Provides internationalized date formatting with graceful fallback to standard PHP
 * date formatting when IntlDateFormatter is unavailable or misconfigured.
 *
 * Output format:
 * [date] [time] [severity icon] ❲AppName⦒ObjectNamespace\Object@ID❳ message in severity color
 *
 * @author vitex
 */
class ToConsole extends ToMemory implements Loggingable
{
    /**
     * Standard Output handle.
     *
     * @var resource
     */
    public $stdout;

    /**
     * Standard error handle.
     *
     * @var resource
     */
    public $stderr;

    /**
     * ANSI escape codes for terminal colors and formatting.
     *
     * @var array<string, int> mapping of color/format names to ANSI codes
     */
    protected static array $ansiCodes = [
        'off' => 0,
        'bold' => 1,
        'italic' => 3,
        'underline' => 4,
        'blink' => 5,
        'inverse' => 7,
        'hidden' => 8,
        'black' => 30,
        'red' => 31,
        'green' => 32,
        'yellow' => 33,
        'blue' => 34,
        'magenta' => 35,
        'cyan' => 36,
        'white' => 37,
        'black_bg' => 40,
        'red_bg' => 41,
        'green_bg' => 42,
        'yellow_bg' => 43,
        'blue_bg' => 44,
        'magenta_bg' => 45,
        'cyan_bg' => 46,
        'white_bg' => 47,
    ];

    /**
     * Saves object instance (singleton...).
     *
     * @var ?ToConsole
     */
    private static ?ToConsole $instance = null;

    /**
     * Log Status messages to console.
     * 
     * @throws \RuntimeException if stdout or stderr cannot be opened
     */
    public function __construct()
    {
        $stdout = fopen('php://stdout', 'wb');
        $stderr = fopen('php://stderr', 'wb');
        
        if ($stdout === false) {
            throw new \RuntimeException('Cannot open stdout for writing');
        }
        
        if ($stderr === false) {
            throw new \RuntimeException('Cannot open stderr for writing');
        }
        
        $this->stdout = $stdout;
        $this->stderr = $stderr;
    }

    /**
     * Set Ansi Color.
     *
     * @param string $str   string to colorize
     * @param string $color color name
     *
     * @return string
     */
    public static function set($str, $color)
    {
        $colorAttrs = explode('+', $color);
        $ansiStr = '';

        foreach ($colorAttrs as $attr) {
            $ansiStr .= "\033[".self::$ansiCodes[$attr].'m';
        }

        return $ansiStr.($str."\033[".self::$ansiCodes['off'].'m');
    }

    /**
     * Write message to console with internationalized date formatting and fallback.
     * 
     * Uses IntlDateFormatter for localized date formatting when possible,
     * with graceful fallback to standard PHP date formatting if the
     * internationalization extension fails or is misconfigured.
     *
     * @param object|string $caller  object or string identification of logging class
     * @param string        $message message to log
     * @param string        $type    severity level (success|info|error|warning|debug|*)
     *
     * @return int number of bytes written to output
     * 
     * @throws \Exception if output streams are not available
     */
    public function addToLog($caller, $message, $type = 'message')
    {
        // Fallback to default format if IntlDateFormatter creation fails
        $dateTime = new \DateTime();
        $formattedDate = $dateTime->format('m/d/Y H:i:s'); // Default fallback
        
        try {
            $fmt = datefmt_create(
                \Ease\Locale::$localeUsed,
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                date_default_timezone_get(),
                \IntlDateFormatter::GREGORIAN,
                'MM/dd/yyyy HH:mm:ss',
            );

            if ($fmt instanceof \IntlDateFormatter) {
                try {
                    $intlFormatted = datefmt_format($fmt, $dateTime);

                    if ($intlFormatted !== false) {
                        $formattedDate = $intlFormatted;
                    }
                } catch (\Error $e) {
                    // Keep the default fallback format if IntlDateFormatter fails
                }
            }
        } catch (\ValueError | \Error $e) {
            // Keep the default fallback format if datefmt_create fails
        }

        $ansiMessage = $this->set(strip_tags((string) $message), self::getTypeColor($type));
        $logLine = $formattedDate.' '.
                Message::getTypeUnicodeSymbol($type).' ❲'.
                \Ease\Shared::appName().'⦒'.
                Message::getCallerName($caller).'❳ '.
                $ansiMessage;
        $written = 0;

        switch ($type) {
            case 'error':
                $written += fwrite($this->stderr, $logLine."\n");

                break;

            default:
                $written += fwrite($this->stdout, $logLine."\n");

                break;
        }

        return $written;
    }

    /**
     * Get color code for given message.
     *
     * @param string $type mail|warning|error|debug|success|info
     */
    public static function getTypeColor(string $type): string
    {
        switch ($type) {
            case 'mail':
            case 'info':                    // Kytička
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
     * @see http://docs.php.net/en/language.oop5.patterns.html Dokumentace a
     * priklad
     */
    public static function singleton(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
