<?php
/**
 * Log to Windows Event Log.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2016 Vitex@hippy.cz (G)
 */

namespace Ease\Logger;

/**
 * Log to EventLog.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */
class ToEventlog extends ToSyslog implements Loggingable
{
    /**
     * Předvolená metoda logování.
     *
     * @var string
     */
    public $logType = 'eventlog';

    /**
     * @var ToEventlog Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Encode For Windows event Log
     *
     * @param string $messageRaw
     *
     * @return string ready to use message
     */
    public function finalizeMessage($messageRaw)
    {
        return iconv("UTF-8", "cp1251//TRANSLIT", $messageRaw);
    }
    
}
