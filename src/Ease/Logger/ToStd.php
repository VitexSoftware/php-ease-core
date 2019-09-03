<?php
/**
 * Log to stdout/stderr
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2016 Vitex@hippy.cz (G)
 */

namespace Ease\Logger;

/**
 * Log to syslog.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */
class ToStd extends ToMemory implements Loggingable
{
    /**
     * Pole uložených zpráv.
     *
     * @var array
     */
    public $statusMessages = [];

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
        $this->logName = $logName;
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
            self::$instance = new self(defined('EASE_APPNAME') ? constant('EASE_APPNAME') : 'EaseFramework');
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
     * @return null|boolean byl report zapsán ?
     */
    public function addToLog($caller, $message, $type = 'message')
    {
        ++$this->messageID;

        $this->statusMessages[$type][$this->messageID] = $message;

        $message = htmlspecialchars_decode(strip_tags(stripslashes($message)));

        $user = \Ease\User::singleton();
        if (get_class($user) !== 'Ease\\Anonym') {
            if (method_exists($user, 'getUserName')) {
                $person = $user->getUserName();
            } else {
                $person = $user->getObjectName();
            }
            $caller = $person.' '.$caller;
        }

        $logLine = ' `'.$caller.'` '.str_replace(
            ['notice', 'message', 'debug', 'report',
                'error', 'warning', 'success', 'info', 'mail',],
            ['**', '##', '@@', '::'], $type
        ).' '.$message."\n";
        if (!isset($this->logStyles[$type])) {
            $type = 'notice';
        }

        $this->output($type, $logLine);

        return true;
    }

    /**
     * Output logline to stderr/stdout by its type
     *
     * @param string $type    message type 'error' or anything else
     * @param string $logLine message to output
     */
    public function output($type, $logLine)
    {
        switch ($type) {
        case 'error':
            $stderr = fopen('php://stderr', 'w');
            fwrite($stderr, $this->logName.': '.$logLine);
            fclose($stderr);
            break;
        default:
            $stdout = fopen('php://stdout', 'w');
            fwrite($stdout, $this->logName.': '.$logLine);
            fclose($stdout);
            break;
        }
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
        return trim($messageRaw).PHP_EOL;
    }

    /**
     * Flush Messages.
     *
     * @param string $caller
     *
     * @return int how many messages was flushed
     */
    public function flush($caller = null)
    {
        $flushed = 0;
        if (count($this->statusMessages)) {
            foreach ($this->statusMessages as $type => $messages) {
                foreach ($messages as $messageID => $message) {
                    if (!isset($this->flushed[$type][$messageID])) {
                        $this->addToLog($caller, $message, $type);
                        $this->flushed[$type][$messageID] = true;
                        ++$flushed;
                    }
                }
            }
        }

        return $flushed;
    }
}
