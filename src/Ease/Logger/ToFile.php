<?php
/**
 * Třída pro logování.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2016 Vitex@hippy.cz (G)
 */

namespace Ease\Logger;

class ToFile extends ToMemory implements Loggingable
{
    /**
     * Předvolená metoda logování.
     *
     * @var string
     */
    public $logType = 'file';

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
     * úroveň logování.
     *
     * @var string - silent,debug
     */
    public $logLevel = 'debug';

    /**
     * Soubor do kterého se lougují pouze zprávy typu Error.
     *
     * @var string filepath
     */
    public $errorLogFile = 'EaseErrors.log';

    /**
     * Odkaz na vlastnící objekt.
     *
     * @var \Ease\Sand
     */
    public $parentObject = null;

    /**
     * Filedescriptor Logu.
     *
     * @var resource
     */
    private $_logFileHandle = null;

    /**
     * Filedescriptor chybového Logu.
     *
     * @var resource
     */
    private $_errorLogFileHandle = null;

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
                $this->errorLogFile = $this->logPrefix.$this->errorLogFile;
            } else {
                $this->logPrefix    = null;
                $this->logFileName  = null;
                $this->errorLogFile = null;
            }
        } else {
            $this->logType      = 'none';
            $this->logPrefix    = null;
            $this->logFileName  = null;
            $this->errorLogFile = null;
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
    public function addToLog($caller, $message, $type = 'message')
    {
        ++$this->messageID;
        if (($this->logLevel == 'silent') && ($type != 'error')) {
            return;
        }
        if (($this->logLevel != 'debug') && ($type == 'debug')) {
            return;
        }
        $this->statusMessages[$type][$this->messageID] = $message;

        $message = htmlspecialchars_decode(strip_tags(stripslashes($message)));

        $logLine = date(DATE_ATOM).' ('.$caller.') '.str_replace(['notice', 'message',
                'debug', 'error', 'warning', 'success', 'info', 'mail',],
                ['**', '##', '@@', '::'], $type).' '.$message."\n";
        if (!isset($this->logStyles[$type])) {
            $type = 'notice';
        }
        if ($this->logType == 'console' || $this->logType == 'both') {
            if (php_sapi_name() == 'cli') {
                echo $logLine;
            } else {
                echo '<div style="'.$this->logStyles[$type].'">'.$logLine."</div>\n";
                flush();
            }
        }
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
            if ($type == 'error') {
                if (!empty($this->errorLogFile)) {
                    if (!$this->_errorLogFileHandle) {
                        $this->_errorLogFileHandle = fopen($this->errorLogFile,
                            'a+');
                    }
                    if ($this->_errorLogFileHandle) {
                        fputs($this->_errorLogFileHandle, $logLine);
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
                                         $isReadable = true, $isWritable = true)
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

    /**
     * Oznamuje chybovou událost.
     * 
     * @deprecated since version 1.0
     *
     * @param string $caller     název volající funkce, nebo objektu
     * @param string $message    zpráva
     * @param mixed  $objectData data k zaznamenání
     */
    public function error($caller, $message, $objectData = null)
    {
        if ($this->errorLogFile) {
            $logFileHandle = fopen($this->errorLogFile, 'a+');
            if ($logFileHandle) {
                if (php_sapi_name() == 'clie') {
                    fputs($logFileHandle,
                        \Ease\Brick::printPreBasic($_ENV)."\n #End of CLI enviroment  <<<<<<<<<<<<<<<<<<<<<<<<<<< # \n\n");
                } else {
                    fputs($logFileHandle,
                        \Ease\Brick::printPreBasic($_SERVER)."\n #End of Server enviroment  <<<<<<<<<<<<<<<<<<<<<<<<<<< # \n\n");
                }
                if (isset($_POST) && count($_POST)) {
                    fputs($logFileHandle,
                        \Ease\Brick::printPreBasic($_POST)."\n #End of _POST  <<<<<<<<<<<<<<<<<<<<<<<<<<< # \n\n");
                }
                if (isset($_GET) && count($_GET)) {
                    fputs($logFileHandle,
                        \Ease\Brick::printPreBasic($_GET)."\n #End of _GET enviroment  <<<<<<<<<<<<<<<<<<<<<<<<<<< # \n\n");
                }
                if ($objectData) {
                    fputs($logFileHandle,
                        \Ease\Brick::printPreBasic($objectData)."\n #End of ObjectData >>>>>>>>>>>>>>>>>>>>>>>>>>>># \n\n");
                }
                fclose($logFileHandle);
            } else {
                throw new Exception('Error: Couldn\'t open the '.realpath($this->errorLogFile).' error log file');
            }
        }
        $this->addToLog($caller, $message, 'error');
    }

    /**
     * Uzavře chybové soubory.
     */
    public function __destruct()
    {
        if ($this->_logFileHandle) {
            fclose($this->_logFileHandle);
        }
        if ($this->_errorLogFileHandle) {
            fclose($this->_errorLogFileHandle);
        }
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
