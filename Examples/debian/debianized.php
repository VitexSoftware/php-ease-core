#!/usr/bin/php -q
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

require '/var/lib/ease-core/autoload.php';

$mailer = new \Ease\Mailer('info@vitexsoftware.cz', 'Test', 'Send from debian');
$mailer->send();
