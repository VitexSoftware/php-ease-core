<?php

declare(strict_types=1);

/**
 * Common EaseFramework class
 * 
 * @category Common
 * 
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Basic Class of EasePHP0
 * 
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Atom {

    /**
     * Version of EasePHP Framework
     *
     * @var string
     */
    public static $frameworkVersion = '0.5';

    /**
     * Flag debugovacího režimu.
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Vrací jméno objektu.
     *
     * @return string
     */
    public function getObjectName() {
        return get_class();
    }

    /**
     * Magická funkce pro všechny potomky.
     *
     * @return string
     */
    public function __toString() {
        return '';
    }

    /**
     * Default Draw method.
     *
     * @return string
     */
    public function draw() {
        return $this->__toString();
    }

}
