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
 *
 * @property bool $debug Debug mode flag
 *
 * @method string draw()          Default draw method
 * @method string getObjectName() Returns object name
 */
class Atom implements \Stringable
{
    /**
     * Debug mode flag.
     */
    public bool $debug = false;

    /**
     * Magic string conversion for all descendants.
     */
    public function __toString(): string
    {
        return '';
    }

    /**
     * Returns object name.
     *
     * @return string Fully qualified class name
     */
    public function getObjectName(): string
    {
        return \get_class($this);
    }

    /**
     * Default draw method.
     */
    public function draw(): void
    {
        echo $this->__toString();
    }
}
