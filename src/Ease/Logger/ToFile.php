<?php

/**
 * File Logging class.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 */

declare(strict_types=1);

namespace Ease\Logger;

/**
 * @method  methodName(type $paramName) Description
 */
class ToFile extends ToMemory implements Loggingable
{
    /**
     * Adresář do kterého se zapisují logy.
     *
     * @var string dirpath
     */
    public $logPrefix = null;

    /**
     * Soubor s do kterého se zapisuje log.
     *
     * @var string
     */
    public $logFileName = 'Ease.log';

    /**
     * Odkaz na vlastnící objekt.
     *
     * @var \Ease\Sand
     */
    public $parentObject = null;

    /**
     * Filedescriptor Logu.
     *
     * @var resource|boolean
     */
    private $logFileHandle = null;

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
     * Saves obejct instace (singleton...).
     */
    private static $instance = null;

    /**
     * Logovací třída.
     *
     * @param string $baseLogDir
     */
    public function __construct($baseLogDir = null)
    {
        $this->setupLogFiles($baseLogDir);
    }

    /**
     * Get instanece of File Logger
     *
     * @param string $logdir
     *
     * @return ToFile
     */
    public static function singleton($logdir = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($logdir);
        }

        return self::$instance;
    }

    /**
     * Nastaví cesty logovacích souborů.
     *
     * @param string $baseLogDir
     */
    public function setupLogFiles($baseLogDir = '')
    {
        $baseLogDir = empty($baseLogDir) ? \Ease\Shared::cfg('LOG_DIRECTORY') : $baseLogDir;

        if (empty($baseLogDir)) {
            $this->logType = 'none';
            $this->logPrefix = '';
            $this->logFileName = '';
        } else {
            $this->logPrefix = \Ease\Functions::sysFilename($baseLogDir);
            if ($this->testDirectory($this->logPrefix)) {
                $this->logFileName = $this->logPrefix . $this->logFileName;
            } else {
                $this->logPrefix = '';
                $this->logFileName = '';
            }
        }
    }

    /**
     * Zapise zapravu do logu.
     *
     * @param string $caller  název volajícího objektu
     * @param string $message zpráva
     * @param string $type    typ zprávy (success|info|error|warning|*)
     *
     * @return int bytes written
     */
    public function addToLog($caller, $message, $type = 'notice')
    {
        ++$this->messageID;
        $written = 0;
        self::$statusMessages[$type][$this->messageID] = $message;

        $message = htmlspecialchars_decode(strip_tags(stripslashes($message)));

        $logLine = date(DATE_ATOM) . ' (' . Message::getCallerName($caller) . ') ' . str_replace(
            ['notice', 'message', 'debug', 'error', 'warning', 'success', 'info', 'mail'],
            ['**', '##', '@@', '::'],
            $type
        ) . ' ' . $message . "\n";
        if (!empty($this->logPrefix)) {
            if ($this->logType == 'file' || $this->logType == 'both') {
                if (!empty($this->logFileName)) {
                    if (!$this->logFileHandle) {
                        $this->logFileHandle = fopen($this->logFileName, 'a+');
                    }
                    if ($this->logFileHandle !== null) {
                        $written += fwrite($this->logFileHandle, $logLine);
                    }
                }
            }
        }

        return $written;
    }

    /**
     * Zkontroluje stav adresáře a upozorní na případné nesnáze.
     *
     * @param string $directoryPath cesta k adresáři
     * @param bool   $isDir         detekovat existenci adresáře
     * @param bool   $isReadable    testovat čitelnost
     * @param bool   $isWritable    testovat zapisovatelnost
     *
     * @return bool konečný výsledek testu
     */
    public static function testDirectory($directoryPath, $isDir = true, $isReadable = true, $isWritable = true)
    {
        if ($isDir && !is_dir($directoryPath)) {
            throw new \Exception($directoryPath . _(' is not an folder. Current directory:') . ' ' . getcwd());
        }
        if ($isReadable && !is_readable($directoryPath)) {
            throw new \Exception($directoryPath . _(' not an readable folder. Current directory:') . ' ' . getcwd());
        }
        if ($isWritable && !is_writable($directoryPath)) {
            throw new \Exception($directoryPath . _(' not an writeable folder. Current directory:') . ' ' . getcwd());
        }
        return true;
    }

    /**
     * Uzavře chybové soubory.
     */
    public function __destruct()
    {
        if ($this->logFileHandle && is_resource($this->logFileHandle)) {
            fclose($this->logFileHandle);
        }
    }
}
