<?php

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
