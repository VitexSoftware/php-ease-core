<?php
/**
 * Třída pro logování.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */

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
    private $_logFileHandle = null;

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
    public function setupLogFiles($baseLogDir = null)
    {

        $baseLogDir = is_null($baseLogDir) ? ( is_null($this->logPrefix) && defined('LOG_DIRECTORY') ? constant('LOG_DIRECTORY') : null ) : $baseLogDir;
        
        if (!empty($baseLogDir)) {
            $this->logPrefix = \Ease\Functions::sysFilename($baseLogDir);
            if ($this->testDirectory($this->logPrefix)) {
                $this->logFileName  = $this->logPrefix.$this->logFileName;
            } else {
                $this->logPrefix    = null;
                $this->logFileName  = null;
            }
        } else {
            $this->logType      = 'none';
            $this->logPrefix    = null;
            $this->logFileName  = null;
        }
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
        $this->statusMessages[$type][$this->messageID] = $message;

        $message = htmlspecialchars_decode(strip_tags(stripslashes($message)));

        $logLine = date(DATE_ATOM).' ('.$caller.') '.str_replace(
            ['notice', 'message',
                'debug', 'error', 'warning', 'success', 'info', 'mail',],
            ['**', '##', '@@', '::'], $type
        ).' '.$message."\n";
        if (!empty($this->logPrefix)) {
            if ($this->logType == 'file' || $this->logType == 'both') {
                if (!empty($this->logFileName)) {
                    if (!$this->_logFileHandle) {
                        $this->_logFileHandle = fopen($this->logFileName, 'a+');
                    }
                    if ($this->_logFileHandle !== null) {
                        fwrite($this->_logFileHandle, $logLine);
                    }
                }
            }
        }

        return true;
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
    public static function testDirectory($directoryPath, $isDir = true,
        $isReadable = true, $isWritable = true
    ) {
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

    /**
     * Uzavře chybové soubory.
     */
    public function __destruct()
    {
        if ($this->_logFileHandle) {
            fclose($this->_logFileHandle);        }
    }

    /**
     * Vrací styl logování.
     *
     * @param string $logType typ logu warning|info|success|error|notice|*
     *
     * @return string
     */
    public function getLogStyle($logType = 'notice')
    {
        if (key_exists($logType, $this->logStyles)) {
            return $this->logStyles[$logType];
        } else {
            return '';
        }
    }
}