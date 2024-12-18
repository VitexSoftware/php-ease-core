<?php

/**
 * File Logging class.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
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
 * @method methodName(type $paramName) Description
 */
class ToFile extends ToMemory implements Loggingable
{
    /**
     * Adresář do kterého se zapisují logy.
     *
     * @var string dirpath
     */
    public string $logPrefix = '';

    /**
     * Soubor s do kterého se zapisuje log.
     */
    public string $logFileName = 'Ease.log';

    /**
     * Odkaz na vlastnící objekt.
     */
    public \Ease\Sand $parentObject;

    /**
     * Obecné konfigurace frameworku.
     */
    public \Ease\Shared $easeShared;

    /**
     * Filedescriptor Logu.
     *
     * @var bool|resource
     */
    private $logFileHandle;

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
     * @param string $baseLogDir
     */
    public function __construct($baseLogDir = null)
    {
        $this->setupLogFiles($baseLogDir);
    }

    /**
     * Uzavře chybové soubory.
     */
    public function __destruct()
    {
        if ($this->logFileHandle && \is_resource($this->logFileHandle)) {
            fclose($this->logFileHandle);
        }
    }

    /**
     * Get instanece of File Logger.
     */
    public static function singleton(string $logdir = ''): self
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
    public function setupLogFiles($baseLogDir = ''): void
    {
        $baseLogDir = empty($baseLogDir) ? \Ease\Shared::cfg('LOG_DIRECTORY') : $baseLogDir;

        if (empty($baseLogDir)) {
            $this->logType = 'none';
            $this->logPrefix = '';
            $this->logFileName = '';
        } else {
            $this->logPrefix = \Ease\Functions::sysFilename($baseLogDir);

            if ($this->testDirectory($this->logPrefix)) {
                $this->logFileName = $this->logPrefix.$this->logFileName;
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

        $logLine = date(\DATE_ATOM).' ('.Message::getCallerName($caller).') '.str_replace(
            ['notice', 'message', 'debug', 'error', 'warning', 'success', 'info', 'mail'],
            ['**', '##', '@@', '::'],
            $type,
        ).' '.$message."\n";

        if (!empty($this->logPrefix)) {
            if ($this->logType === 'file' || $this->logType === 'both') {
                if (!empty($this->logFileName)) {
                    if (!$this->logFileHandle) {
                        $this->logFileHandle = fopen($this->logFileName, 'a+b');
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
            throw new \Exception($directoryPath._(' is not an folder. Current directory:').' '.getcwd());
        }

        if ($isReadable && !is_readable($directoryPath)) {
            throw new \Exception($directoryPath._(' not an readable folder. Current directory:').' '.getcwd());
        }

        if ($isWritable && !is_writable($directoryPath)) {
            throw new \Exception($directoryPath._(' not an writeable folder. Current directory:').' '.getcwd());
        }

        return true;
    }
}
