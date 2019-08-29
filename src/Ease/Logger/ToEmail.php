<?php
/**
 * Send logs by email
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */

namespace Ease\Logger;

/**
 * Log to Email
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */
class ToEmail extends ToMemory implements Loggingable
{
    /**
     * Předvolená metoda logování.
     *
     * @var string
     */
    public $logType = 'email';

    /**
     * úroveň logování.
     *
     * @var string - silent,debug
     */
    public $logLevel = 'debug';

    /**
     * Odkaz na vlastnící objekt.
     *
     * @var \Ease\Sand
     */
    public $parentObject = null;

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
     * Obecné konfigurace frameworku.
     *
     * @var \Ease\Shared
     */
    public $easeShared = null;

    /**
     * List of allready flushed messages.
     *
     * @var array
     */
    public $flushed = [];

    /**
     * Saves obejct instace (singleton...).
     */
    private static $_instance = null;

    /**
     * Handle to mailer.
     *
     * @var resource
     */
    public $mailer = null;

    /**
     * Recipient
     *
     * @var string
     */
    public $recipient = null;

    /**
     * Log Subject
     *
     * @var string
     */
    public $subject = null;

    /**
     * Logger to mail Class
     *
     * @param string $recipient
     * @param string $subject   of message
     */
    public function __construct($recipient = null, $subject = null)
    {
        $this->recipient = $recipient;
        $this->subject   = empty($subject) ? $_SERVER['PHP_SELF'] : $subject;
        $this->mailer = new \Ease\Mailer($this->recipient, $this->subject);
        $this->mailer->setUserNotification(false);
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
        if (!isset(self::$_instance)) {
            $class = __CLASS__;
            if (defined('EASE_APPNAME')) {
                self::$_instance = new $class(
                    constant('EASE_EMAILTO'),
                    constant('EASE_APPNAME')
                );
            } else {
                self::$_instance = new $class('EaseFramework');
            }
        }

        return self::$_instance;
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
    public function addToLog($caller, $message, $type = 'notice')
    {

        ++$this->messageID;
        if (($this->logLevel == 'silent') && ($type != 'error')) {
            return;
        }
        if (($this->logLevel != 'debug') && ($type == 'debug')) {
            return;
        }

        $this->statusMessages[$type][$this->messageID] = $message;

        $logLine = strftime("%D %T").' `'.$caller.'`: '.$message;

        $this->mailer->textBody .= "\n" . $logLine;
        return true;
    }

    /**
     * Send collected messages.
     */
    public function __destruct()
    {
        if (strlen($this->mailer->mailBody) > 0 ) {
            $this->mailer->send();
        }
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
