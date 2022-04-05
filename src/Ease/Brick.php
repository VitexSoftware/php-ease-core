<?php

declare(strict_types=1);

namespace Ease;

/**
 * 
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */
class Brick extends Sand
{

    use RecordKey;

    /**
     * Sets the object name.
     * If it is known, the name of the object completes the value of the key, e.g. User#vitex
     * or ProductInCart#4542.
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
                $result = parent::setObjectName(get_class($this) . '@' . $key);
            } else {
                $result = parent::setObjectName();
            }
        } else {
            $result = parent::setObjectName($objectName);
        }

        return $result;
    }

}
