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

namespace Test\Ease;

if (!\defined('EASE_LOGGER')) {
    \define('EASE_LOGGER', 'memory');
}

\define('EASE_APPNAME', 'unitTest');
\define('EASE_EMAILTO', 'info@vitexsoftware.cz');

require_once __DIR__.'/../vendor/autoload.php';

$_SESSION['locale'] = 'C';
putenv('LOCALE=C');
