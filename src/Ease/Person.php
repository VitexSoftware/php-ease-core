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
    public function getUserID(): ?int;

    /**
     * Anonym nemá login.
     */
    public function getUserLogin(): ?string;

    /**
     * Anonym nemůže být přihlášený.
     *
     * @return bool FALSE
     */
    public function isLogged();

    /**
     * Setting getter.
     */
    public function getSettingValue(string $settingName): ?string;

    /**
     * Nastaví položku nastavení.
     *
     * @param string $settingName  klíčové slovo pro nastavení
     * @param mixed  $settingValue hodnota nastavení
     */
    public function setSettingValue($settingName, $settingValue): bool;

    /**
     * Anonym nemá mail.
     */
    public function getUserEmail(): string;

    /**
     * Fake permissions.
     *
     * @param string $permKeyword permission keyword
     */
    public function getPermission(string $permKeyword): ?string;

    /**
     * Just fake.
     *
     * @return bool true - always logged off
     */
    public function logout();
}
