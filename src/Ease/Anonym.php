<?php

/**
 * Anonymous user class.
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 *
 * @category User Classes
 * @package EasePHP
 *
 * PHP 7
 *
 */

declare(strict_types=1);

namespace Ease;

/**
 * Anonymous User Class
 */
class Anonym extends Brick implements Person
{
    /**
     * User type.
     *
     * @var string
     */
    public $type = 'Anonymous';

    /**
     * An Anonymous user ID is always null
     *
     * @var int|null
     */
    public $userID = null;

    /**
     * Login indicator.
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
     * User object name setting.
     *
     * @param string $objectName forced object name
     *
     * @return string
     */
    public function setObjectName($objectName = null)
    {
        if (is_null($objectName) && isset($_SERVER['REMOTE_ADDR'])) {
            $name = parent::setObjectName(get_class($this) . '@' . self::remoteToIdentity());
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
            $identity = $_SERVER['REMOTE_ADDR'] . ' [' . $_SERVER['REMOTE_USER'] . ']';
        } else {
            $identity = $_SERVER['REMOTE_ADDR'];
        }
        return $identity;
    }

    /**
     * Anonymous has a level
     *
     * @return int
     */
    public function getUserLevel()
    {
        return -1;
    }

    /**
     * Anonymous has no ID.
     */
    public function getUserID()
    {
        return;
    }

    /**
     * Anonymous has no login.
     */
    public function getUserLogin()
    {
        return;
    }

    /**
     * Anonymous cannot be logged.
     *
     * @return bool FALSE
     */
    public function isLogged()
    {
        return $this->logged;
    }

    /**
     * Anonymous has no settings.
     *
     * @param string $settingName settings-key name
     */
    public function getSettingValue(/** @scrutinizer ignore-unused */ $settingName = null)
    {
        return;
    }

    /**
     * Sets to: has no settings.
     *
     * @param string $settingName  settings keyword (name)
     * @param mixed  $settingValue setting value
     */
    public function setSettingValue($settingName, $settingValue)
    {
        $this->settings[$settingName] = $settingValue;
    }

    /**
     * Anonymous has no mail.
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
