#!/usr/bin/php -f
<?php
/**
 * Example Mailer.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2020 Vitex@hippy.cz (G)
 */
namespace Example\Ease;

require_once '../vendor/autoload.php';
define('EASE_LOGGER', 'console');

$testMail = new \Ease\Mailer(isset($argv[1]) ? $argv[1] : constant('EASE_EMAILTO'),
    'Příliš žluťoučký kůň úpěl ďábelské ódy', 'Test mail body' );

$testMail->addFile(__FILE__);

if ($testMail->send()) {
    $testMail->addStatusMessage('Test mail sent');
} else {
    $testMail->addStatusMessage('Test mail not sent', 'error');
}
