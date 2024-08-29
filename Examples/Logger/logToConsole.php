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

namespace Example\Ease\Logger;

\define('EASE_LOGGER', 'console|syslog');

require_once '../vendor/autoload.php';

$logger = new \Ease\Sand();

$logger->addStatusMessage('Mail Message', 'mail');
$logger->addStatusMessage('Debug Message', 'debug');
$logger->addStatusMessage('Default Message', 'info');
$logger->addStatusMessage('Warning Message', 'warning');
$logger->addStatusMessage('Success Message', 'success');
$logger->addStatusMessage('Error Message', 'error');
