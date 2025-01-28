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

namespace Test\Ease\Local;

/**
 * Description of SandTester.
 *
 * @author vitex
 */
class MoleculeTester extends \Ease\Molecule
{
    public string $string = '';
    public int $integer = 0;
    public float $float = 0.0;
    public ?bool $boolean = null;
}
