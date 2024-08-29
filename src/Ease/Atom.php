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

namespace Ease;

/**
 * Basic Class of EasePHP.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Atom
{
    /**
     * Debug mode flag.
     */
    public bool $debug = false;

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
     * Returns object name.
     *
     * @return string
     */
    public function getObjectName()
    {
        return \get_class($this);
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
