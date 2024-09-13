<?php

/**
 * Something between Atom and Sand
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2024 Vitex@hippy.cz (G)
 *
 * PHP 8
 */

declare(strict_types=1);

namespace Ease;

use Ease\Atom;
use Ease\Functions;

/**
 * Description of Molecule
 *
 * @author vitex
 */
class Molecule extends Atom
{
    /**
     * Object name
     *
     * @var string
     */
    public $objectName = 'Molecule';

    /**
     * Set object name
     *
     * @param string $objectName
     *
     * @return string Object name
     */
    public function setObjectName($objectName = null)
    {
        if (empty($objectName)) {
            $this->objectName = get_class($this);
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
     * Set up one of the properties by 1) array 2) ENV 3) Constant
     *
     * @param array  $options  array of given availble properties
     * @param string $name     name of property to set up
     * @param string $constant load default property value from constant / ENV
     */
    public function setupProperty($options, $name, $constant = '')
    {
        if (array_key_exists($name, $options)) {
            $this->$name = $options[$name];
        } elseif (array_key_exists($constant, $options)) {
            $this->$name = $options[$constant];
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Functions::cfg($constant)) === false)) {
                $this->$name = Functions::cfg($constant);
            }
        }
    }

    /**
     * Set up one of the properties by 1) array 2) ENV 3) Constant to int value
     *
     * @param array  $options  array of given availble properties
     * @param string $name     name of property to set up
     * @param string $constant load default property value from constant / ENV
     */
    public function setupIntProperty($options, $name, $constant = '')
    {
        if (array_key_exists($name, $options)) {
            $this->$name = (int) $options[$name];
        } elseif (array_key_exists($constant, $options)) {
            $this->$name = (int) $options[$constant];
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Functions::cfg($constant)) === false)) {
                $this->$name = (int) Functions::cfg($constant);
            }
        }
    }
    
    /**
     * Set up one of the properties by 1) array 2) ENV 3) Constant to bool value
     *
     * @param array  $options  array of given availble properties
     * @param string $name     name of property to set up
     * @param string $constant load default property value from constant / ENV
     */
    public function setupBoolProperty($options, $name, $constant = '')
    {
        if (array_key_exists($name, $options)) {
            $this->$name = (bool) $options[$name];
        } elseif (array_key_exists($constant, $options)) {
            $this->$name = (bool) $options[$constant];
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Functions::cfg($constant)) === false)) {
                $this->$name = (bool) Functions::cfg($constant);
            }
        }
    }
    
}
