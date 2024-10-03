<?php

/**
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 *
 * PHP 7
 * PHP 8
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

namespace Ease;

/**
 * Common shared object.
 *
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 * @author    Vitex <vitex@hippy.cz>
 */
class Shared extends Atom
{
    /**
     * Pole konfigurací.
     */
    public array $configuration = [];

    /**
     * Array of Status Messages.
     *
     * @var array of Logger\Message
     */
    public array $messages = [];

    /**
     * session item name with user object.
     */
    public static string $userSessionName = 'User';

    /**
     * Status messeges lives here.
     */
    public static array $statusMessages = [];

    /**
     * Saves obejct instace (singleton...).
     */
    private static Shared $instance;

    /**
     * Inicializace sdílené třídy.
     */
    public function __construct()
    {
        $sessMsgs = [];
        $appName = self::appName();

        if (isset($_SESSION[$appName]['EaseMessages'])) {
            $sessMsgs = $_SESSION[$appName]['EaseMessages'];
            unset($_SESSION[$appName]['EaseMessages']);
        }

        self::$statusMessages = array_merge(self::loadStatusMessages(), $sessMsgs);
    }

    /**
     * Load required Initial Configuration.
     *
     * @param array  $configKeys
     * @param string $envFile
     * @param bool   $exit       do exit(1) when unsuccessful ?
     *
     * @return bool Configuration success
     */
    public static function init($configKeys = [], $envFile = '', $exit = true)
    {
        if (empty($envFile) === false) {
            if (file_exists($envFile)) {
                self::singleton()->loadConfig($envFile, true);
            } else {
                fwrite(fopen('php://stderr', 'wb'), 'Specified config file '.$envFile.' does not exists. (cwd: '.getcwd().')'.\PHP_EOL);
            }
        }

        $configured = true;

        if ((array_search('DB_CONNECTION', $configKeys, true) !== false) && preg_match('/^sqlite/', self::cfg('DB_CONNECTION', ''))) {
            unset($configKeys[array_search('DB_PASSWORD', $configKeys, true)], $configKeys[array_search('DB_USERNAME', $configKeys, true)], $configKeys[array_search('DB_HOST', $configKeys, true)], $configKeys[array_search('DB_PORT', $configKeys, true)]);
        }

        foreach ($configKeys as $cfgKey) {
            if (empty(self::cfg($cfgKey))) {
                fwrite(fopen('php://stderr', 'wb'), self::appName().': Requied configuration '.$cfgKey.' is not set.'.\PHP_EOL);
                $configured = false;
            }
        }

        if ($configured === false) {
            if ($envFile) {
                fwrite(fopen('php://stderr', 'wb'), self::appName().': (using '.$envFile.')'.\PHP_EOL);
            }

            if ($exit) {
                exit(1);
            }
        }

        return $configured;
    }

    /**
     * Get configuration from constant or environment.
     *
     * @param string $constant
     * @param mixed  $cfg      Default value
     *
     * @return null|bool|int|string
     */
    public static function cfg(/* string */ $constant, $cfg = null)
    {
        if (!empty($constant) && \defined($constant)) {
            $cfg = \constant($constant);
        } elseif (\array_key_exists($constant, $_ENV)) {
            $cfg = getenv($constant, true);
        } else {
            $env = getenv($constant);

            if (!empty($env)) {
                $cfg = $env;
            }
        }

        return $cfg;
    }

    /**
     * Application name or "Composer project Name" fallback.
     *
     * @return string
     */
    public static function appName()
    {
        if (method_exists('Composer\InstalledVersions', 'getRootPackage')) {
            $package = \Composer\InstalledVersions::getRootPackage();
        } else {
            $package['name'] = 'Unnamed';
        }

        return self::cfg('EASE_APPNAME') ? self::cfg('EASE_APPNAME') : (self::cfg('APP_NAME') ?: $package['name']);
    }

    /**
     * Application version or "0.0.0" fallback.
     *
     * @return string
     */
    public static function appVersion()
    {
        if (method_exists('Composer\InstalledVersions', 'getRootPackage')) {
            $package = \Composer\InstalledVersions::getRootPackage();
        } else {
            $package = [];
        }

        return \array_key_exists('version', $package) ? $package['version'] : '0.0.0';
    }

    /**
     * File with stored messages.
     *
     * @param string $sessID
     *
     * @return string
     */
    public static function msgFile($sessID = 'EaseStatusMessages')
    {
        $uid = (\function_exists('posix_getuid') ? posix_getuid() : (\function_exists('posix_getpwuid') ? posix_getpwuid(posix_getuid()) : ''));

        return Functions::sysFilename(sys_get_temp_dir().'/'.self::appName().$sessID.$uid.'.ser');
    }

    /**
     * Load status Messages from session.
     *
     * @param string $sessID
     *
     * @return array
     */
    public static function loadStatusMessages($sessID = 'EaseStatusMessages')
    {
        $msgFile = self::msgFile($sessID);
        $messages = [];

        if (file_exists($msgFile) && is_readable($msgFile) && filesize($msgFile) && is_writable($msgFile)) {
            $messages = unserialize(file_get_contents($msgFile));
            unlink($msgFile);
        }

        return $messages;
    }

    /**
     * Write remaining messages to temporary file.
     *
     * @param mixed $sessID
     *
     * @return int bytes saved
     */
    public function saveStatusMessages($sessID = 'EaseStatusMessages')
    {
        return file_put_contents(self::msgFile($sessID), serialize(self::$statusMessages));
    }

    /**
     * Pri vytvareni objektu pomoci funkce singleton (ma stejne parametry, jako konstruktor)
     * se bude v ramci behu programu pouzivat pouze jedna jeho Instance (ta prvni).
     *
     * @see http://docs.php.net/en/language.oop5.patterns.html Dokumentace a priklad
     *
     * @return \Ease\Shared
     */
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Vrací se.
     *
     * @return Shared
     */
    public static function &instanced()
    {
        $easeShared = self::singleton();

        return $easeShared;
    }

    /**
     * Nastavuje hodnotu konfiguračního klíče.
     *
     * @param string $configName  klíč
     * @param mixed  $configValue hodnota klíče
     */
    public function setConfigValue($configName, $configValue): void
    {
        $this->configuration[$configName] = $configValue;
    }

    /**
     * Vrací konfigurační hodnotu pod klíčem.
     *
     * @param string $configName klíč
     *
     * @return mixed
     */
    public function getConfigValue($configName)
    {
        return \array_key_exists($configName, $this->configuration) ? $this->configuration[$configName] : null;
    }

    /**
     * Vrací instanci objektu logování.
     *
     * @param null|array $loggers Override inital loggers
     *
     * @return Logger\Regent
     */
    public static function logger($loggers = null)
    {
        return Logger\Regent::singleton($loggers);
    }

    /**
     * Gives you shared User object. Create it first if not exist yet.
     *
     * @param Anonym|Person|string|User $user objekt nového uživatele nebo název třídy
     *
     * @return User
     */
    public static function &user(?Person $user = null, string $candidat = 'User', string $userSessionName = 'User')
    {
        $efprefix = self::appName();

        if (empty($user) && isset($_SESSION[$efprefix][self::$userSessionName])) {
            return $_SESSION[$efprefix][self::$userSessionName];
        }

        if (!empty($userSessionName)) {
            self::$userSessionName = $userSessionName;
        }

        if (\is_object($user)) {
            $_SESSION[$efprefix][self::$userSessionName] = clone $user;
        } else {
            if (!empty($candidat)) {
                $_SESSION[$efprefix][self::$userSessionName] = method_exists($candidat, 'singleton') ?
                        $candidat::singleton() :
                        new $candidat();
            }
        }

        return $_SESSION[$efprefix][self::$userSessionName];
    }

    /**
     * Load Configuration values from json file $this->configFile and define UPPERCASE keys.
     *
     * @param string $configFile      Path to file with configuration
     * @param bool   $defineConstants false to do not define constants
     *
     * @return array full configuration array
     */
    public function loadConfig($configFile, $defineConstants = false)
    {
        switch (strtolower(pathinfo($configFile, \constant('PATHINFO_EXTENSION')))) {
            case 'json':
                $configuration = json_decode(file_get_contents($configFile), true);

                break;
            case 'env':
                $configuration = [];

                foreach (file($configFile) as $cfgRow) {
                    if (strstr($cfgRow, '=')) {
                        [$key, $value] = preg_split('/=/', $cfgRow, 2);
                        $configuration[$key] = trim($value, " \t\n\r\0\x0B'\"");
                    }
                }

                break;

            default:
                throw new Exception('unsupported config type: '.$configFile);
        }

        foreach ($configuration as $configKey => $configValue) {
            if ($defineConstants && (strtoupper($configKey) === $configKey) && (!\defined($configKey))) {
                \define($configKey, $configValue);
            } else {
                $this->setConfigValue($configKey, $configValue);
            }

            $this->configuration[$configKey] = $configValue;
        }

        if (\array_key_exists('debug', $this->configuration)) {
            $this->debug = (bool) $this->configuration['debug'];

            if ($this->debug === true) {
                $this->logger()->addToLog($this, 'Loaded configuration from '.$configFile, 'debug');
            }
        }

        return $this->configuration;
    }
}
