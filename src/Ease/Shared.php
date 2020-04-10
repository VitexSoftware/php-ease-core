<?php

/**
 * Všeobecně sdílený objekt frameworku.
 * Tento objekt je automaticky přez svůj singleton instancován do každého Ease*
 * objektu.
 * Poskytuje kdykoliv přístup k často volaným objektům framworku jako například
 * uživatel, databáze, webstránka nebo logy.
 * Také obsahuje pole obecnych nastavení a funkce pro jeho obluhu.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2018 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Všeobecně sdílený objekt frameworku.
 * Tento objekt je automaticky přez svůj singleton instancován do každého Ease*
 * objektu.
 * Poskytuje kdykoliv přístup k často volaným objektům framworku jako například
 * uživatel, databáze, webstránka nebo logy.
 * Také obsahuje pole obecnych nastavení a funkce pro jeho obluhu.
 *
 * @copyright 2009-2016 Vitex@hippy.cz (G)
 * @author    Vitex <vitex@hippy.cz>
 */
class Shared extends Atom {

    /**
     * Pole konfigurací.
     *
     * @var array
     */
    public $configuration = [];

    /**
     * Saves obejct instace (singleton...).
     *
     * @var Shared
     */
    private static $instance = null;

    /**
     * Array of Status Messages
     *
     * @var array of Logger\Message
     */
    public $messages = [];

    /**
     * session item name with user object
     *
     * @var string
     */
    public static $userSessionName = 'User';

    /**
     * Inicializace sdílené třídy.
     */
    public function __construct() {
        $cgiMessages = [];
        $webMessages = [];
        $prefix = defined('EASE_APPNAME') ? constant('EASE_APPNAME') : '';
        $msgFile = sys_get_temp_dir() . '/' . $prefix . 'EaseStatusMessages' . posix_getuid() . '.ser';
        if (file_exists($msgFile) && is_readable($msgFile) && filesize($msgFile) && is_writable($msgFile)
        ) {
            $cgiMessages = unserialize(file_get_contents($msgFile));
            file_put_contents($msgFile, '');
        }

        if (defined('EASE_APPNAME')) {
            if (isset($_SESSION[constant('EASE_APPNAME')]['EaseMessages'])) {
                $webMessages = $_SESSION[constant('EASE_APPNAME')]['EaseMessages'];
                unset($_SESSION[constant('EASE_APPNAME')]['EaseMessages']);
            }
        } else {
            if (isset($_SESSION['EaseMessages'])) {
                $webMessages = $_SESSION['EaseMessages'];
                unset($_SESSION['EaseMessages']);
            }
        }
        $this->statusMessages = is_array($cgiMessages) ? array_merge(
                        $cgiMessages, $webMessages
                ) : $webMessages;
    }

    /**
     * Pri vytvareni objektu pomoci funkce singleton (ma stejne parametry, jako konstruktor)
     * se bude v ramci behu programu pouzivat pouze jedna jeho Instance (ta prvni).
     *
     * @param string $class název třídy jenž má být zinstancována
     *
     * @link http://docs.php.net/en/language.oop5.patterns.html Dokumentace a priklad
     *
     * @return \Ease\Shared
     */
    public static function singleton() {
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
    public static function &instanced() {
        $easeShared = self::singleton();

        return $easeShared;
    }

    /**
     * Nastavuje hodnotu konfiguračního klíče.
     *
     * @param string $configName  klíč
     * @param mixed  $configValue hodnota klíče
     */
    public function setConfigValue($configName, $configValue) {
        $this->configuration[$configName] = $configValue;
    }

    /**
     * Vrací konfigurační hodnotu pod klíčem.
     *
     * @param string $configName klíč
     *
     * @return mixed
     */
    public function getConfigValue($configName) {
        return array_key_exists($configName, $this->configuration) ? $this->configuration[$configName] : null;
    }

    /**
     * Vrací instanci objektu logování.
     *
     * @return Logger
     */
    public static function logger() {
        return Logger\Regent::singleton();
    }

    /**
     * Write remaining messages to temporary file.
     */
    public function __destruct() {
        if (php_sapi_name() == 'cli') {
            $prefix = defined('EASE_APPNAME') ? constant('EASE_APPNAME') : '';
            $messagesFile = sys_get_temp_dir() . '/' . $prefix . 'EaseStatusMessages' . posix_getuid() . '.ser';
            file_put_contents($messagesFile, serialize($this->statusMessages));
        }
    }

    /**
     * Vrací, případně i založí objekt uživatele.
     *
     * @param User|Anonym|string $user objekt nového uživatele nebo
     *                                 název třídy
     *
     * @return User
     */
    public static function &user(object $user = null, string $candidat = 'User', string $userSessionName = 'User') {
        $efprefix = defined('EASE_APPNAME') ? constant('EASE_APPNAME') : 'EaseFramework';
        if (empty($user) && isset($_SESSION[$efprefix][self::$userSessionName])) {
            return $_SESSION[$efprefix][self::$userSessionName];
        }
        if (!is_null($userSessionName)) {
            self::$userSessionName = $userSessionName;
        }
        if (is_object($user)) {
            $_SESSION[$efprefix][self::$userSessionName] = clone $user;
        } else {
            if (!empty($candidat)) {
                $_SESSION[$efprefix][self::$userSessionName] = method_exists($candidat, 'singleton') ? $candidat::singleton() : new $candidat();
            }
        }
        return $_SESSION[$efprefix][self::$userSessionName];
    }

    /**
     * Load Configuration values from json file $this->configFile and define UPPERCASE keys
     *
     * @param string  $configFile      Path to file with configuration
     * @param boolean $defineConstants false to do not define constants
     *
     * @return array full configuration array
     */
    public function loadConfig($configFile, $defineConstants = false) {
        if (!file_exists($configFile)) {
            throw new Exception(
                    'Config file ' . (realpath($configFile) ? realpath($configFile) : $configFile) . ' does not exist'
            );
        }
        $configuration = json_decode(file_get_contents($configFile), true);
        foreach ($configuration as $configKey => $configValue) {
            if ($defineConstants && (strtoupper($configKey) == $configKey) && (!defined($configKey))) {
                define($configKey, $configValue);
            } else {
                $this->setConfigValue($configKey, $configValue);
            }
            $this->configuration[$configKey] = $configValue;
        }

        if (array_key_exists('debug', $this->configuration)) {
            $this->debug = boolval($this->configuration['debug']);
        }

        return $this->configuration;
    }    
    
}
