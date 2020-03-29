<?php
/**
 * Anonymous user class.
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2009-2020 Vitex@hippy.cz (G)
 * 
 * @category User Classes
 * @package EasePHP
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Anonymous User Class
 */
class Anonym extends Brick
{
    /**
     * Druh uživatele.
     *
     * @var string
     */
    public $type = 'anonymous';

    /**
     * Anonymní uživatel má vždy ID null.
     *
     * @var null
     */
    public $userID = null;

    /**
     * Indikátor přihlášení.
     *
     * @var bool
     */
    public $logged = false;

    /**
     * User Settings array
     *
     * @var array
     */
    public $settings = [];

    /**
     * Nastavení jména objektu uživatele.
     *
     * @param string $objectName vynucené jméno objektu
     *
     * @return string
     */
    public function setObjectName($objectName = null)
    {
        if (is_null($objectName) && isset($_SERVER['REMOTE_ADDR'])) {
            $name = parent::setObjectName(get_class($this).'@'.self::remoteToIdentity());
        } else {
            $name = parent::setObjectName($objectName);
        }
        return $name;
    }

    /**
     * Returns user identity with logname if logged
     *
     * @return string
     */
    public static function remoteToIdentity()
    {
        if (isset($_SERVER['REMOTE_USER'])) {
            $identity = $_SERVER['REMOTE_ADDR'].' ['.$_SERVER['REMOTE_USER'].']';
        } else {
            $identity = $_SERVER['REMOTE_ADDR'];
        }
        return $identity;
    }

    /**
     * Anonym má level.
     *
     * @return int
     */
    public function getUserLevel()
    {
        return -1;
    }

    /**
     * Anonym nema ID.
     */
    public function getUserID()
    {
        return;
    }

    /**
     * Anonym nemá login.
     */
    public function getUserLogin()
    {
        return;
    }

    /**
     * Anonym nemůže být přihlášený.
     *
     * @return bool FALSE
     */
    public function isLogged()
    {
        return $this->logged;
    }

    /**
     * Anonym nemá nastavení.
     *
     * @param string $settingName jméno klíče nastavení
     */
    public function getSettingValue(/** @scrutinizer ignore-unused */ $settingName = null)
    {
        return;
    }

    /**
     * Nastaví položku nastavení.
     *
     * @param string $settingName  klíčové slovo pro nastavení
     * @param mixed  $settingValue hodnota nastavení
     */
    public function setSettingValue($settingName, $settingValue)
    {
        $this->settings[$settingName] = $settingValue;
    }

    
    
    /**
     * Anonym nemá mail.
     */
    public function getUserEmail()
    {
        return;
    }

    /**
     * Fake permissions.
     *
     * @param string $permKeyword permission keyword
     */
    public function getPermission(/** @scrutinizer ignore-unused */ $permKeyword = null)
    {
        return;
    }

    /**
     * Just fake.
     *
     * @return bool true - always logged off
     */
    public function logout()
    {
        $this->userID = null;

        return true;
    }
}
