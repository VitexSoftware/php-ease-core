<?php

/**
 * Log to stdout/stderr.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2024 Vitex@hippy.cz (G)
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
 * Log to syslog.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 */
class ToStd extends ToMemory implements Loggingable
{
    /**
     * List of allready flushed messages.
     *
     * @var array<string>
     */
    public array $flushed = [];

    /**
     * Log Name.
     */
    public string $logName = '';

    /**
     * ID naposledy ulozene zpravy.
     *
     * @var int unsigned
     */
    private int $messageID = 0;

    /**
     * Saves obejct instace (singleton...).
     */
    private static self $instance;

    /**
     * Logovací třída.
     *
     * @param string $logName symbolic name for log
     */
    public function __construct($logName = null)
    {
        $this->logName = empty($logName) ? \Ease\Shared::appName() : $logName;
    }

    public static function singleton(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(\Ease\Shared::appName() ?: 'EaseFramework');
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

        $message = htmlspecialchars_decode(strip_tags(stripslashes((string) $message)));

        $user = \Ease\User::singleton();

        if (\get_class($user) !== 'Ease\\Anonym') {
            if (method_exists($user, 'getUserName')) {
                $person = $user->getUserName();
            } else {
                $person = $user->getObjectName();
            }

            $caller = $person.' '.Message::getCallerName($caller);
        }

        $logLine = ' `'.$caller.'` '.str_replace(
            ['notice', 'message', 'debug', 'report',
                'error', 'warning', 'success', 'info', 'mail', ],
            ['**', '##', '@@', '::'],
            (string) $type,
        ).' '.$message."\n";

        if (!isset($this->logStyles[$type])) {
            $type = 'notice';
        }

        return $this->output($type, $logLine);
    }

    /**
     * Output logline to stderr/stdout by its type.
     *
     * @param string $type    message type 'error' or anything else
     * @param string $logLine message to output
     *
     * @return int bytes written
     */
    public function output(string $type, $logLine)
    {
        $written = 0;

        switch ($type) {
            case 'error':
                $stderr = fopen('php://stderr', 'wb');
                $written += fwrite($stderr, $this->logName.': '.$logLine);
                fclose($stderr);

                break;

            default:
                $stdout = fopen('php://stdout', 'wb');
                $written += fwrite($stdout, $this->logName.': '.$logLine);
                fclose($stdout);

                break;
        }

        return $written;
    }

    /**
     * Last message check/modify point before output.
     *
     * @param string $messageRaw
     *
     * @return string ready to use message
     */
    public function finalizeMessage($messageRaw)
    {
        return trim($messageRaw).\PHP_EOL;
    }
}
