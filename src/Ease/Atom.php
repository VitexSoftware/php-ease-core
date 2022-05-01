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
class Atom
{

    /**
     * Version of EasePHP Framework
     *
     * @var string
     */
    public static $frameworkVersion = '0.5';

    /**
     * Debug mode flag.
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Returns object name
     *
     * @return string
     */
    public function getObjectName()
    {
        return get_class();
    }

    /**
     * Magical function for all descendants (childern).
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    /**
     * Default draw method.
     *
     * @return string
     */
    public function draw()
    {
        return $this->__toString();
    }

}
