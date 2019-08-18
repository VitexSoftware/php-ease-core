<?php
/**
 * Zaváděcí soubor pro provádění PHPUnit testů na EaseFrameworkem.
 *
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2012-2019 Vitex@hippy.cz (G)
 */

namespace Test\Ease;

if (!defined('EASE_LOGGER')) {
    define('EASE_LOGGER', 'memory');
}

define('EASE_APPNAME', 'unitTest');
define('EASE_EMAILTO', 'info@vitexsoftware.cz');

require_once __DIR__.'/../vendor/autoload.php';

