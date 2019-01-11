#!/usr/bin/php -q
<?php
require '/var/lib/ease-core/autoload.php';

$mailer = new \Ease\Mailer('info@vitexsoftware.cz', 'Test', 'Send from debian'); 
$mailer->send();
