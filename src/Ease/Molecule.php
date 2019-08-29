<?php
/**
 * Something between Atom and Sand
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */

namespace Ease;

/**
 * Description of Molecule
 *
 * @author vitex
 */
class Molecule extends Atom
{
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
     * Vrací jméno objektu.
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * Set up one of properties
     *
     * @param array  $options  array of given properties
     * @param string $name     name of property to process
     * @param string $constant load default property value from constant
     */
    public function setupProperty($options, $name, $constant = null)
    {
        if (isset($options[$name])) {
            $this->$name = $options[$name];
        } else {
            if (is_null($this->$name) && !empty($constant) && defined($constant)) {
                $this->$name = constant($constant);
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
    public function addToLog($message, $type = 'message')
    {
        return Shared::logger()->addToLog(
            $this->getObjectName(), $message,
            $type
        );
    }

}
