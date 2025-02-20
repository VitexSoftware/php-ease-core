<?php

/**
 * Main Ease Class.
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

class Brick extends Sand
{
    use recordkey;

    /**
     * @var string Name Column
     */
    public string $nameColumn = '';

    /**
     * Ease Brick Class.
     *
     * @param null|mixed $init
     * @param mixed      $properties
     */
    public function __construct($init = null, $properties = [])
    {
        $this->setInit($init);
        $this->setProperties($properties);
        $this->setObjectName();
    }

    /**
     * Use Given value as identifier.
     *
     * @param mixed $identifier
     */
    public function useIdentifier($identifier): void
    {
        switch ($this->howToProcess($identifier)) {
            case 'values':
                $this->takeData($identifier);

                break;
            case 'reuse':
                if ($identifier->getData()) {
                    $this->takeData($identifier->getData());
                } else {
                    $this->dataReset();
                }

                break;
            case 'name':
                $this->setDataValue($this->nameColumn, $identifier);

                break;
            case 'id':
                $this->setMyKey($identifier);

                break;

            default:
                break;
        }
    }

    /**
     * How to process.
     *
     * @param mixed $identifer
     *
     * @return string id|name|values|reuse|unknown
     */
    public function howToProcess($identifer)
    {
        $recognizedAs = 'unknown';

        switch (\gettype($identifer)) {
            case 'integer':
            case 'double':
                if ($this->getKeyColumn()) {
                    $recognizedAs = 'id';
                }

                break;
            case 'string':
                if (!empty($this->nameColumn)) {
                    $recognizedAs = 'name';
                }

                break;
            case 'array':
                $recognizedAs = 'values';

                break;
            case 'object':
                if ($identifer instanceof self) {
                    $recognizedAs = 'reuse';
                }

                break;

            default:
            case 'boolean':
            case 'NULL':
                $recognizedAs = 'unknown';

                break;
        }

        return $recognizedAs;
    }

    /**
     * Sets the object name.
     * If it is known, the name of the object completes the value of the key, e.g. User#vitex
     * or ProductInCart#4542.
     *
     * @param string $objectName
     *
     * @return string new name
     */
    public function setObjectName($objectName = '')
    {
        if (empty($objectName)) {
            $recordId = $this->getMyKey($this->data);

            if ($this->nameColumn && $this->getDataValue($this->nameColumn)) {
                $key = '('.$recordId.')'.$this->getDataValue($this->nameColumn);
            } else {
                $key = $recordId;
            }

            if ($key) {
                $oldName = \Ease\Logger\Message::getCallerName($this);
                $result = parent::setObjectName($key.'@'.substr($oldName, strpos($oldName, '@') ? strpos($oldName, '@') + 1 : 0));
            } else {
                $result = parent::setObjectName();
            }
        } else {
            $result = parent::setObjectName($objectName);
        }

        return $result;
    }

    /**
     * Object init value stub.
     *
     * @param mixed $init
     */
    public function setInit($init): void
    {
    }

    /**
     * Set/override object properties objectName and keyColumn.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    public function setProperties(array $properties = []): void
    {
        if (\array_key_exists('objectName', $properties)) {
            $this->setObjectName($properties['objectName']);
        }

        if (\array_key_exists('keyColumn', $properties)) {
            $this->setKeyColumn($properties['keyColumn']);
        }

        if (\array_key_exists('nameColumn', $properties)) {
            $this->nameColumn = $properties['nameColumn'];
        }
    }
}
