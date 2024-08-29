<?php
/**
 * Zaváděcí soubor pro provádění PHPUnit testů na EaseFrameworkem.
 *
 * @author     Vitex <info@vitexsoftware.cz>
 * @copyright  2012-2021 info@vitexsoftware.cz (G)
 */

namespace Test\Ease;

if (!defined('EASE_LOGGER')) {
    define('EASE_LOGGER', 'memory');
}

define('EASE_APPNAME', 'unitTest');
define('EASE_EMAILTO', 'info@vitexsoftware.cz');

require_once '/var/lib/composer/php-vitexsoftware-ease-core-dev/autoload.php';

