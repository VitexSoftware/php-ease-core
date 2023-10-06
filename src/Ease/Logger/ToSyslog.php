<?php

/**
 * Syslog logger handler
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

namespace Ease\Logger;

/**
 * Log to syslog.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 */
class ToSyslog extends ToStd implements Loggingable
{
    /**
     * Předvolená metoda logování.
     *
     * @var string
     */
    public $logType = 'syslog';

    /**
     * Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Logovací třída.
     *
     * @see https://www.php.net/manual/en/function.openlog.php
     *
     * @param string $logName syslog log source identifier
     */
    public function __construct($logName = null)
    {
        parent::__construct($logName);
        openlog(empty($this->logName) ? \Ease\Shared::appName() :
            $this->logName, intval(\Ease\Shared::cfg('LOG_FLAG')), intval(\Ease\Shared::cfg('LOG_FACILITY')));
    }

    /**
     * Obtain instance of Syslog loger
     *
     * @return ToSyslog
     */
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(\Ease\Shared::appName() ? \Ease\Shared::appName() : 'EaseFramework');
        }
        return self::$instance;
    }

    /**
     * Output logline to syslog/messages by its type
     *
     * @param string $type    message type 'error' or anything else
     * @param string $logLine message to output
     *
     * @return int written message length
     */
    public function output($type, $logLine)
    {
        return syslog($type == 'error' ? \Ease\Shared::cfg('LOG_ERR') : \Ease\Shared::cfg('LOG_INFO'), $this->finalizeMessage($logLine)) ?
                strlen($logLine) : 0;
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
        return trim($messageRaw);
    }

    /**
     * Close syslog connection
     */
    public function __destruct()
    {
        closelog();
    }
}
