<?php
/**
 * Třída pro logování.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */

namespace Ease\Logger;

/**
 * Log to syslog.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
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
     * @param string $logName syslog log source identifier
     */
    public function __construct($logName = null)
    {
        if (!empty($logName)) {
            openlog($logName, constant('LOG_NDELAY'), constant('LOG_USER'));
        }
    }

    /**
     * Obtain instance of Syslog loger
     * 
     * @return ToSyslog
     */
    public static function singleton()
    {
        if (!isset(self::$instance)) {
                self::$instance = new self(defined('EASE_APPNAME') ? constant('EASE_APPNAME') :  'EaseFramework');
        }
        return self::$instance;
    }

    /**
     * Output logline to syslog/messages by its type
     *
     * @param string $type    message type 'error' or anything else
     * @param string $logLine message to output
     */
    public function output($type, $logLine)
    {
        return syslog($type == 'error' ? constant('LOG_ERR') : constant('LOG_INFO'), $this->finalizeMessage($logLine));
    }

    /**
     * Uzavře chybové soubory.
     * 
     * @return boolean syslog close status
     */
    public function __destruct()
    {
        return closelog();
    }
}
