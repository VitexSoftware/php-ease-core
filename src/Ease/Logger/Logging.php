<?php

/**
 * Logging trait.
 *
 * @category Logging
 *
 * @author    Vitex <info@vitexsoftware.cz>
 * @copyright 2019 info@vitexsoftware.cz (G)
 * @license   https://opensource.org/licenses/MIT MIT
 *
 * PHP 7
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
 * @author vitex
 */
trait Logging
{
    /**
     * Objekt pro logování.
     */
    public ?Regent $logger = null;

    /**
     * Add message to stack to show or write to file
     * Přidá zprávu do zásobníku pro zobrazení uživateli inbo do logu.
     *
     * @param string $message text zpravy
     * @param string $type    fronta
     * @param object $caller  Message source name
     *
     * @return bool message added
     */
    public function addStatusMessage($message, $type = 'info', $caller = null)
    {
        return (bool) $this->getLogger()->addToLog(empty($caller) ? $this : $caller, $message, $type);
    }

    /**
     * Obtain global status messages.
     *
     * @return array
     */
    public function getStatusMessages()
    {
        return $this->getLogger()->getMessages();
    }

    /**
     * Erase all status messages.
     *
     * @return bool
     */
    public function cleanSatatusMessages()
    {
        return $this->getLogger()->cleanMessages();
    }

    /**
     * Provide logger object.
     *
     * @param array|string $options
     *
     * @return Regent
     */
    public function getLogger($options = null)
    {
        if (null === $this->logger) {
            $this->logger = Regent::singleton($options);
        }

        return $this->logger;
    }

    /**
     * Add Info about used PHP and EasePHP Library.
     *
     * @param string $prefix banner prefix text (default is APPName)
     * @param string $suffix banner suffix text
     */
    public function logBanner($prefix = '', $suffix = ''): void
    {
        if (\Ease\Shared::cfg('DEBUG') === true) {
            $suffix .= ' Loggers: '.\Ease\Shared::cfg('EASE_LOGGER');
        }

        if (method_exists('Composer\InstalledVersions', 'getPrettyVersion')) {
            $version = \Composer\InstalledVersions::getPrettyVersion('vitexsoftware/ease-core');
        } else {
            $version = 'n/a';
        }

        $this->addStatusMessage(
            trim(($prefix ?: \Ease\Shared::appName()).
            ' EaseCore '.$version.' (PHP '.\PHP_VERSION.') '.$suffix),
            'debug',
        );
    }
}
