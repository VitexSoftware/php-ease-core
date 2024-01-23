<?php

/**
 * Common EaseFramework class
 *
 * @category Common
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT
 *
 * PHP 8
 */

namespace Ease;

/**
 * Basic Class of EasePHP
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Atom
{
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
        return get_class($this);
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
