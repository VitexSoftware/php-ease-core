<?php

/**
 * Something between Atom and Sand.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2024 Vitex@hippy.cz (G)
 *
 * PHP 8
 */

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
 * Description of Molecule.
 *
 * @author vitex
 */
class Molecule extends Atom
{
    /**
     * Object name.
     */
    public string $objectName = 'Molecule';

    /**
     * Set object name.
     *
     * @param string $objectName
     *
     * @return string Object name
     */
    public function setObjectName($objectName = null)
    {
        if (empty($objectName)) {
            $this->objectName = \get_class($this);
        } else {
            $this->objectName = $objectName;
        }

        return $this->objectName;
    }

    /**
     * Returns the name of the object.
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * Set up one of the properties by 1) array 2) ENV 3) Constant.
     *
     * @param array<string, string> $options  array of given available properties
     * @param string                $name     name of property to set up
     * @param string                $constant load default property value from constant / ENV
     */
    public function setupProperty(array $options, string $name, string $constant = ''): void
    {
        if (\array_key_exists($name, $options)) {
            $this->{$name} = $options[$name];
        } elseif (\array_key_exists($constant, $options)) {
            $this->{$name} = $options[$constant];
        } else { // If No values specified we must use constants or environment
            $value = Shared::cfg($constant);

            if ($constant && (empty($value) === false)) {
                switch (\gettype($this->{$name})) {
                    case 'boolean':
                        switch (strtolower($value)) {
                            case 'true':
                                $this->{$name} = true;

                                break;
                            case 'false':
                                $this->{$name} = false;

                                break;

                            default:
                                $this->{$name} = (bool) Shared::cfg($constant);

                                break;
                        }

                        break;
                    case 'string':
                        $this->{$name} = (string) Shared::cfg($constant);

                        break;

                    default:
                        $this->{$name} = Shared::cfg($constant);

                        break;
                }
            }
        }
    }

    /**
     * Set up one of the properties by 1) array 2) ENV 3) Constant to int value.
     *
     * @param array<string, int|string> $options  array of given available properties
     * @param string                    $name     name of property to set up
     * @param string                    $constant load default property value from constant / ENV
     */
    public function setupIntProperty(array $options, string $name, string $constant = ''): void
    {
        if (\array_key_exists($name, $options)) {
            $this->{$name} = (int) $options[$name];
        } elseif (\array_key_exists($constant, $options)) {
            $this->{$name} = (int) $options[$constant];
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Shared::cfg($constant)) === false)) {
                $this->{$name} = (int) Shared::cfg($constant);
            }
        }
    }

    /**
     * Set up one of the properties by 1) array 2) ENV 3) Constant to float value.
     *
     * @param array<string, float|int|string> $options  array of given available properties
     * @param string                          $name     name of property to set up
     * @param string                          $constant load default property value from constant / ENV
     * @param null|float                      $default  optional fallback value
     *
     * @return bool was value set ?
     */
    public function setupFloatProperty(array $options, string $name, string $constant = '', ?float $default = null): bool
    {
        $changed = false;

        if (\array_key_exists($name, $options)) {
            $this->{$name} = (float) $options[$name];
            $changed = true;
        } elseif (\array_key_exists($constant, $options)) {
            $this->{$name} = (float) $options[$constant];
            $changed = true;
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Shared::cfg($constant)) === false)) {
                $this->{$name} = (float) Shared::cfg($constant);
            } else {
                if ((null === $default) === false) {
                    $this->{$name} = $default;
                    $changed = true;
                }
            }
        }

        return $changed;
    }

    /**
     * Set up one of the properties by 1) array 2) ENV 3) Constant to bool value.
     *
     * @param array<string, string> $options  array of given availble properties
     * @param string                $name     name of property to set up
     * @param string                $constant load default property value from constant / ENV
     */
    public function setupBoolProperty(array $options, string $name, string $constant = ''): void
    {
        if (\array_key_exists($name, $options)) {
            $this->{$name} = (strtolower($options[$name]) === 'true') || ($options[$name] === '1') || (strtolower($options[$name]) === 'on');
        } elseif (\array_key_exists($constant, $options)) {
            $this->{$name} = (strtolower($options[$constant]) === 'true') || ($options[$constant] === '1') || (strtolower($options[$constant]) === 'on');
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Shared::cfg($constant)) === false)) {
                $val = Shared::cfg($constant);
                $this->{$name} = (strtolower($val) === 'true') || ($val === '1') || (strtolower($val) === 'on');
            }
        }
    }
}
