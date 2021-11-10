<?php

declare(strict_types=1);

/**
 * Something between Atom and Sand
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */

namespace Ease;

use Ease\Atom;
use Ease\Functions;

/**
 * Description of Molecule
 *
 * @author vitex
 */
class Molecule extends Atom {

    /**
     * Object name place
     *
     * @var string
     */
    public $objectName = 'Molecule';

    /**
     * Set object name
     *
     * @param string $objectName
     *
     * @return string Jméno objektu
     */
    public function setObjectName($objectName = null) {
        if (empty($objectName)) {
            $this->objectName = get_class($this);
        } else {
            $this->objectName = $objectName;
        }

        return $this->objectName;
    }

    /**
     * Vrací jméno objektu.
     *
     * @return string
     */
    public function getObjectName() {
        return $this->objectName;
    }

    /**
     * Set up one of properties by 1) array 2) ENV 3) Constant
     *
     * @param array  $options  array of given availble properties
     * @param string $name     name of property to set up
     * @param string $constant load default property value from constant / ENV
     */
    public function setupProperty($options, $name, $constant = false) {
        if (array_key_exists($name, $options)) {
            $this->$name = $options[$name];
        } elseif ($constant && array_key_exists($constant, $options)) {
            $this->$name = $options[$constant];
        } else { // If No values specified we must use constants or environment
            if ($constant && (empty(Functions::cfg($constant)) === false)) {
                $this->$name = Functions::cfg($constant);
            }
        }
    }

}
