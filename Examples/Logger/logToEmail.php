<?php

declare(strict_types=1);

/**
 * This file is part of the EaseCore package.
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Example\Ease\Logger;

\define('EASE_APPNAME', 'EaseFramework LogToEmail example');
\define('EASE_LOGGER', 'console|email');
\define('EASE_EMAILTO', 'info@vitexsoftware.cz');

if (file_exists('../vendor/autoload.php')) {
    require_once '../vendor/autoload.php'; // Commandline
} else {
    require_once '../../vendor/autoload.php'; // Web
}

$logger = new \Ease\Sand();

$logger->addStatusMessage('Default Message', 'info');
$logger->addStatusMessage('Warning Message', 'warning');
$logger->addStatusMessage('Success Message', 'success');
$logger->addStatusMessage('Error Message', 'error');

// The eMail is send in destructor
