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
 * (c) Vítězslav Dvořák <info@vitexsoftware.cz>
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
     *
     * @phpstan-ignore missingType.iterableValue
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
     */
    public function setObjectName($objectName = null): string
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
     */
    public function getUserLevel(): int
    {
        return -1;
    }

    /**
     * Anonymous has no ID.
     */
    public function getUserID(): int
    {
        return 0;
    }

    /**
     * Anonymous has no login.
     */
    public function getUserLogin(): string
    {
        return '';
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
    #[\Override]
    public function getSettingValue(string $settingName): ?string
    {
        return $settingName !== '' && $settingName !== '0' ? null : '';
    }

    /**
     * Sets to: has no settings.
     *
     * @param mixed $settingName
     * @param mixed $settingValue
     */
    public function setSettingValue($settingName, $settingValue): bool
    {
        $this->settings[$settingName] = $settingValue;

        return true;
    }

    /**
     * Anonymous has no mail.
     */
    public function getUserEmail(): string
    {
        return '';
    }

    /**
     * Fake permissions.
     *
     * @param string $permKeyword permission keyword
     */
    public function getPermission(string $permKeyword): ?string
    {
        return $permKeyword !== '' && $permKeyword !== '0' ? null : '';
    }

    /**
     * Anonym cannot be signed in.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    public function tryToLogin(array $formData): bool
    {
        return !\is_array($formData);
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
