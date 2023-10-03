<?php

/**
 * Log to stdout/stderr
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

namespace Ease\Logger;

/**
 * Log to syslog.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 */
class ToStd extends ToMemory implements Loggingable
{
    /**
     * ID naposledy ulozene zpravy.
     *
     * @var int unsigned
     */
    private $messageID = 0;

    /**
     * List of allready flushed messages.
     *
     * @var array
     */
    public $flushed = [];

    /**
     * Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Log Name
     *
     * @var string
     */
    public $logName = null;

    /**
     * Logovací třída.
     *
     * @param string $logName symbolic name for log
     */
    public function __construct($logName = null)
    {
        $this->logName = empty($logName) ? \Ease\Shared::appName() : $logName;
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
            self::$instance = new self(\Ease\Shared::appName() ? \Ease\Shared::appName() : 'EaseFramework');
        }

        return self::$instance;
    }

    /**
     * Zapise zapravu do logu.
     *
     * @param string $caller  název volajícího objektu
     * @param string $message zpráva
     * @param string $type    typ zprávy (success|info|error|warning|*)
     *
     * @return null|int logged message length ?
     */
    public function addToLog($caller, $message, $type = 'message')
    {
        ++$this->messageID;

        self::$statusMessages[$type][$this->messageID] = $message;

        $message = htmlspecialchars_decode(strip_tags(stripslashes(strval($message))));

        $user = \Ease\User::singleton();
        if (get_class($user) !== 'Ease\\Anonym') {
            if (method_exists($user, 'getUserName')) {
                $person = $user->getUserName();
            } else {
                $person = $user->getObjectName();
            }
            $caller = $person . ' ' . Message::getCallerName($caller);
        }

        $logLine = ' `' . $caller . '` ' . str_replace(
            ['notice', 'message', 'debug', 'report',
            'error', 'warning', 'success', 'info', 'mail',],
            ['**', '##', '@@', '::'],
            $type
        ) . ' ' . $message . "\n";
        if (!isset($this->logStyles[$type])) {
            $type = 'notice';
        }

        return $this->output($type, $logLine);
    }

    /**
     * Output logline to stderr/stdout by its type
     *
     * @param string $type    message type 'error' or anything else
     * @param string $logLine message to output
     *
     * @return int bytes written
     */
    public function output($type, $logLine)
    {
        $written = 0;
        switch ($type) {
            case 'error':
                $stderr = fopen('php://stderr', 'w');
                $written += fwrite($stderr, $this->logName . ': ' . $logLine);
                fclose($stderr);
                break;
            default:
                $stdout = fopen('php://stdout', 'w');
                $written += fwrite($stdout, $this->logName . ': ' . $logLine);
                fclose($stdout);
                break;
        }
        return $written;
    }

    /**
     * Last message check/modify point before output
     *
     * @param string $messageRaw
     *
     * @return string ready to use message
     */
    public function finalizeMessage($messageRaw)
    {
        return trim($messageRaw) . PHP_EOL;
    }
}
