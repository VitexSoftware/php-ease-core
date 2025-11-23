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
 * File logger that writes log messages to specified files with automatic directory management.
 *
 * Provides reliable file-based logging with comprehensive directory validation,
 * automatic file creation, and proper resource management. Supports configurable
 * log directories and graceful error handling for file system issues.
 *
 * @author vitex
 */
class ToFile extends ToMemory implements Loggingable
{
    /**
     * Directory path prefix where log files are written.
     *
     * @var string directory path
     */
    public string $logPrefix = '';

    /**
     * Log file name with full path.
     *
     * @var string log file path
     */
    public string $logFileName = 'Ease.log';

    /**
     * Reference to parent object.
     *
     * @var \Ease\Sand parent object instance
     */
    public \Ease\Sand $parentObject;

    /**
     * Framework configuration object.
     *
     * @var \Ease\Shared configuration instance
     */
    public \Ease\Shared $easeShared;

    /**
     * Log file descriptor handle.
     *
     * @var null|false|resource file handle or false on failure
     */
    private $logFileHandle;

    /**
     * ID of last stored message.
     *
     * @var int message counter
     */
    private int $messageID = 0;

    /**
     * Singleton instance storage.
     *
     * @var ?ToFile singleton instance
     */
    private static ?ToFile $instance = null;

    /**
     * File logger constructor.
     *
     * Initializes the file logger with specified base directory or uses
     * LOG_DIRECTORY configuration. Automatically sets up log file paths
     * and validates directory accessibility.
     *
     * @param ?string $baseLogDir Base directory for log files, null to use LOG_DIRECTORY config
     *
     * @throws \Exception if directory is not accessible or writable
     */
    public function __construct(?string $baseLogDir = null)
    {
        $this->setupLogFiles($baseLogDir);
    }

    /**
     * Closes log file handles on object destruction.
     *
     * Ensures proper resource cleanup by closing any open file handles
     * when the logger object is destroyed.
     */
    public function __destruct()
    {
        if ($this->logFileHandle && \is_resource($this->logFileHandle)) {
            fclose($this->logFileHandle);
        }
    }

    /**
     * Get singleton instance of File Logger.
     *
     * Returns existing instance or creates new one with specified log directory.
     * Ensures only one file logger instance exists per application.
     *
     * @param string $logdir Log directory path, empty string to use default
     *
     * @return ToFile singleton instance
     */
    public static function singleton(string $logdir = ''): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($logdir);
        }

        return self::$instance;
    }

    /**
     * Set up log file paths and validate directory accessibility.
     *
     * Configures log file paths based on provided directory or LOG_DIRECTORY
     * configuration. Validates directory exists, is readable and writable.
     * Disables logging if directory setup fails.
     *
     * @param string $baseLogDir Base directory for log files, empty to use config
     *
     * @throws \Exception if directory validation fails
     */
    public function setupLogFiles(string $baseLogDir = ''): void
    {
        $baseLogDir = empty($baseLogDir) ? \Ease\Shared::cfg('LOG_DIRECTORY') : $baseLogDir;

        if (empty($baseLogDir)) {
            $this->logType = 'none';
            $this->logPrefix = '';
            $this->logFileName = '';
        } else {
            $this->logPrefix = \Ease\Functions::sysFilename($baseLogDir);

            // Ensure trailing directory separator
            if (!empty($this->logPrefix) && !str_ends_with($this->logPrefix, \DIRECTORY_SEPARATOR)) {
                $this->logPrefix .= \DIRECTORY_SEPARATOR;
            }

            if ($this->testDirectory($this->logPrefix)) {
                $baseLogName = 'Ease.log';
                $this->logFileName = $this->logPrefix.$baseLogName;
                $this->logType = 'file';
            } else {
                $this->logPrefix = '';
                $this->logFileName = '';
                $this->logType = 'none';
            }
        }
    }

    /**
     * Write message to log file with timestamp and caller information.
     *
     * Formats and writes log messages to the configured log file. Messages include
     * ISO 8601 timestamp, caller identification, message type symbols, and content.
     * Automatically opens log file if not already open.
     *
     * @param mixed  $caller  Object or string identification of logging class
     * @param string $message Message content to log
     * @param string $type    Message type (success|info|error|warning|notice|debug|*)
     *
     * @throws \Exception if file operations fail
     *
     * @return int Number of bytes written to file
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

        if (!($this->logPrefix === '' || $this->logPrefix === '0') && ($this->logType === 'file' || $this->logType === 'both')) {
            if ($this->logFileName !== '' && $this->logFileName !== '0') {
                if (!$this->logFileHandle) {
                    $this->logFileHandle = fopen($this->logFileName, 'a+b');
                }

                if ($this->logFileHandle !== null) {
                    $written += fwrite($this->logFileHandle, $logLine);
                }
            }
        }

        return $written;
    }

    /**
     * Test directory status and report any issues.
     *
     * Validates directory existence, readability, and writability based on
     * specified parameters. Throws exceptions with detailed error messages
     * if validation fails.
     *
     * @param string $directoryPath Path to directory to test
     * @param bool   $isDir         Test if path is a directory
     * @param bool   $isReadable    Test if directory is readable
     * @param bool   $isWritable    Test if directory is writable
     *
     * @throws \Exception with localized error message if any test fails
     *
     * @return bool True if all tests pass
     */
    public static function testDirectory(string $directoryPath, bool $isDir = true, bool $isReadable = true, bool $isWritable = true): bool
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
