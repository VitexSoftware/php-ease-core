<?php
/**
 * Class to Rule message loggers.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2016-2019 Vitex@hippy.cz (G)
 */

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
     * Allow to write logs to multiplete logging destinations
     * 
     * @param string $logger class name
     */
    public function __construct($logger = null)
    {
        if (is_null($logger) && defined('EASE_LOGGER')) {
            $loggers = explode('|', constant('EASE_LOGGER'));
        } else {
            $loggers = empty($logger) ? ['syslog'] : [$logger];
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
                if (\class_exists($logger) && \method_exists(
                    $logger,
                    'singleton'
                )
                ) {
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
     * @param string $caller  message provider
     * @param string $message message to log
     * @param string $type    info|succes|warning|error|email|...
     * 
     * @return boolean At least one logger takes message
     */
    public function addToLog($caller, $message, $type = 'info')
    {
        $logged = false;
        foreach ($this->loggers as $logger) {
            $logged = $logger->addToLog($caller, $message, $type);
        }
        return $logged;
    }

    /**
     * Add Status Object to stack
     * 
     * @param \Ease\Logger\Message $message
     * 
     * @return int number of stored message
     */
    public function addStatusObject(Message $message)
    {
        $this->addToLog($message->caller, $message->body, $message->type);
        \Ease\Shared::instanced()->addStatusMessage(
            $message->body,
            $message->type
        );
        return parent::addStatusMessage($message->body, $message->type);
    }

    /**
     * Get The Regent
     */
   
    public static function singleton($logger = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($logger);
        }
        return self::$instance;
    }
}
