<?php

/**
 * Anonymous user class.
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 *
 * @category User Classes
 */

declare(strict_types=1);

/**
 * This file is part of the EaseCore package.
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ease;

/**
 * Anonymous User Class.
 */
class Anonym extends Brick implements Person
{
    /**
     * User type.
     */
    public string $type = 'Anonymous';

    /**
     * An Anonymous user ID is always null.
     */
    public ?int $userID = null;

    /**
     * Login indicator.
     */
    public bool $logged = false;

    /**
     * User Settings array.
     */
    public array $settings = [];

    /**
     * Where to look for settings.
     */
    public string $settingsColumn = 'settings';

    /**
     * User object name setting.
     *
     * @param string $objectName forced object name
     *
     * @return string
     */
    public function setObjectName($objectName = null)
    {
        if (null === $objectName && isset($_SERVER['REMOTE_ADDR'])) {
            $name = parent::setObjectName(\get_class($this).'@'.self::remoteToIdentity());
        } else {
            $name = parent::setObjectName($objectName);
        }

        return $name;
    }

    /**
     * Returns user identity with logname if logged.
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
     * Anonymous has a level.
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
    public function getUserID(): void
    {
    }

    /**
     * Anonymous has no login.
     */
    public function getUserLogin(): void
    {
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
    public function getSettingValue(/** @scrutinizer ignore-unused */ $settingName = null): void
    {
    }

    /**
     * Sets to: has no settings.
     *
     * @param string $settingName  settings keyword (name)
     * @param mixed  $settingValue setting value
     */
    public function setSettingValue($settingName, $settingValue): void
    {
        $this->settings[$settingName] = $settingValue;
    }

    /**
     * Anonymous has no mail.
     */
    public function getUserEmail(): void
    {
    }

    /**
     * Fake permissions.
     *
     * @param string $permKeyword permission keyword
     */
    public function getPermission(/** @scrutinizer ignore-unused */ $permKeyword = null): void
    {
    }

    /**
     * Anonym cannot be signed in.
     *
     * @param array $formData FormData
     *
     * @return bool
     */
    public function tryToLogin($formData)
    {
        return false;
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
