<?php

/**
 * Interface for Anonym, User, Customer, Admin etc ...
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2021 Vitex@hippy.cz (G)
 *
 * PHP 7
 */

namespace Ease;

/**
 *
 * @author vitex
 */
interface Person
{
    /**
     * Anonym má level.
     *
     * @return int
     */
    public function getUserLevel();

    /**
     * Anonym nema ID.
     */
    public function getUserID();

    /**
     * Anonym nemá login.
     */
    public function getUserLogin();

    /**
     * Anonym nemůže být přihlášený.
     *
     * @return bool FALSE
     */
    public function isLogged();

    /**
     * Anonym nemá nastavení.
     *
     * @param string $settingName jméno klíče nastavení
     */
    public function getSettingValue(/** @scrutinizer ignore-unused */ $settingName = null);

    /**
     * Nastaví položku nastavení.
     *
     * @param string $settingName  klíčové slovo pro nastavení
     * @param mixed  $settingValue hodnota nastavení
     */
    public function setSettingValue($settingName, $settingValue);

    /**
     * Anonym nemá mail.
     */
    public function getUserEmail();

    /**
     * Fake permissions.
     *
     * @param string $permKeyword permission keyword
     */
    public function getPermission(/** @scrutinizer ignore-unused */ $permKeyword = null);

    /**
     * Just fake.
     *
     * @return bool true - always logged off
     */
    public function logout();
}
