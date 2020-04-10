<?php

/**
 * Something between Atom and Sand
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Description of Molecule
 *
 * @author vitex
 */
class Molecule extends Atom {

    /**
     * Udržuje v sobě jméno objektu.
     *
     * @var string
     */
    public $objectName = 'Molecule';

    /**
     * Nastaví jméno objektu.
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
    public function setupProperty($options, $name, $constant = null) {
        if (array_key_exists($name, $options)) {
            $this->$name = $options[$name];
        } elseif (array_key_exists($constant, $options)) {
            $this->$name = $options[$constant];
        } else { // If No values specified we must use constants or environment
            if (property_exists($this, $name) && !empty($constant) && defined($constant)) {
                $this->$name = constant($constant);
            } elseif (property_exists($this, $name) && ($env = getenv($constant)) && !empty($env)) {
                $this->$name = getenv($constant);
            }
        }
    }

    /**
     * Zapíše zprávu do logu.
     *
     * @param string $message zpráva
     * @param string $type    typ zprávy (info|warning|success|error|*)
     *
     * @return bool byl report zapsán ?
     */
    public function addToLog($message, $type = 'message') {
        return Shared::logger()->addToLog(
                        $this->getObjectName(), $message,
                        $type
        );
    }

}
