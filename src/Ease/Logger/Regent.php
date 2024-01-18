<?php

/**
 * Class to Rule message loggers.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2016-2024 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

namespace Ease\Logger;

/**
 * Description of Regent
 *
 * @author vitex
 */
class Regent extends \Ease\Atom implements Loggingable
{
    /**
     * Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Here to reach logger objects
     *
     * @var array
     */
    public $loggers = [];

    /**
     * Keep All messages here
     * @var array<Message>
     */
    private $messages = [];

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
        'event' => 'color: #996600; animation: animate 1.5s linear infinite; ',
        'info' => 'color: blue;',
    ];

    /**
     * Allow to write logs to multiplete logging destinations
     *
     * @param string|array $logger class name
     */
    public function __construct($logger = null)
    {
        if (empty($logger)) {
            $loggers = empty(\Ease\Shared::cfg('EASE_LOGGER')) ? ['syslog'] :
                    explode('|', \Ease\Shared::cfg('EASE_LOGGER'));
        } else {
            $loggers = is_array($logger) ? $logger : [$logger];
        }
        foreach ($loggers as $logger) {
            switch ($logger) {
                case 'console':
                    $this->loggers[$logger] = ToConsole::singleton();
                    break;
                case 'syslog':
                    $this->loggers[$logger] = ToSyslog::singleton();
                    break;
                case 'memory':
                    $this->loggers[$logger] = ToMemory::singleton();
                    break;
                case 'email':
                    $this->loggers[$logger] = ToEmail::singleton();
                    break;
                case 'std':
                    $this->loggers[$logger] = ToStd::singleton();
                    break;
                case 'eventlog':
                    $this->loggers[$logger] = ToEventlog::singleton();
                    break;
                default:
                    if (\class_exists($logger) && \method_exists($logger, 'singleton')) {
                        $this->loggers[$logger] = $logger::singleton();
                    } else {
                        $this->loggers[$logger] = ToFile::singleton();
                    }
                    break;
            }
        }
    }

    public function takeMessage()
    {
    }

    /**
     * Add Status Message to all registered loggers
     *
     * @param object $caller  message provider
     * @param string $message message to log
     * @param string $type    info|succes|warning|error|email|...
     *
     * @return int How many loggers takes message
     */
    public function addToLog($caller, $message, $type = 'info')
    {
        return $this->addStatusObject(new Message($message, $type, $caller));
    }

    /**
     * Stored messages array
     *
     * @return array<Message>
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Clean Internal message buffer
     */
    public function cleanMessages()
    {
        $this->messages = [];
        return true;
    }

    /**
     * Add Status Object to stack
     *
     * @param \Ease\Logger\Message $message
     *
     * @return int number of stored messages
     */
    public function addStatusObject(Message $message)
    {
        $this->messages[] = $message;
        $logged = 0;
        foreach ($this->loggers as $logger) {
            $logged += $logger->addToLog($message->caller, $message->body, $message->type);
        }
        return $logged;
    }

    /**
     * Get The Regent
     */
    public static function singleton($loggers = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($loggers);
        }
        return self::$instance;
    }
}
