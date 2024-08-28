#!/usr/bin/php -f
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

namespace Example\Ease;

require_once '../vendor/autoload.php';
\define('EASE_LOGGER', 'console');

$testMail = new \Ease\Mailer(
    $argv[1] ?? \constant('EASE_EMAILTO'),
    'Příliš žluťoučký kůň úpěl ďábelské ódy',
    'Test mail body',
);

$testMail->addFile(__FILE__);

if ($testMail->send()) {
    $testMail->addStatusMessage('Test mail sent');
} else {
    $testMail->addStatusMessage('Test mail not sent', 'error');
}
