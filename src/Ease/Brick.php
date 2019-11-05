<?php

namespace Ease;

/**
 * 
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */
class Brick extends Sand
{

    use RecordKey;
    
    /**
     * Nastavuje jméno objektu
     * Je li známý, doplní jméno objektu hodnotu klíče např User#vitex
     * nebo ProductInCart#4542.
     *
     * @param string $objectName
     *
     * @return string new name
     */
    public function setObjectName($objectName = null)
    {
        if (is_null($objectName)) {
            $key = $this->getMyKey($this->data);
            if ($key) {
                $result = parent::setObjectName(get_class($this).'@'.$key);
            } else {
                $result = parent::setObjectName();
            }
        } else {
            $result = parent::setObjectName($objectName);
        }

        return $result;
    }
    
    /**
     * Přidá zprávu do zásobníku pro zobrazení uživateli.
     *
     * @param string $message  zprava
     * @param string $type     Fronta zprav (warning|info|error|success)
     * @param bool   $addIcons prida UTF8 ikonky na zacatek zprav
     *
     * @return bool Was message added to message handler object ?
     */
    public function addStatusMessage($message, $type = 'info', $addIcons = true)
    {
        if ($addIcons) {
            $message = ' '.Logger\Message::getTypeUnicodeSymbol($type).' '.$message;
        }
        return \Ease\Shared::singleton()->addStatusMessage($message, $type);
    }
    
    /**
     * Clean global status messages
     */
    public function cleanMessages()
    {
        \Ease\Shared::singleton()->cleanMessages();
    }


    /**
     * Obtain global status messages
     *
     * @return array
     */
    public function getStatusMessages()
    {
        return \Ease\Shared::singleton()->getStatusMessages();
    }
    
    
}
