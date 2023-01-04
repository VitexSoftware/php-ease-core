<?php
declare(strict_types=1);

namespace Ease;

/**
 * 
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */
class Brick extends Sand
{

    use RecordKey;
    /**
     * Name Column
     * @var string
     */
    public $nameColumn = null;

    /**
     * Ease Brick Class
     */
    public function __construct($init = null, $properties = [])
    {
        $this->setInit($init);
        $this->setProperties($properties);
        $this->setObjectName();
    }

    /**
     * Use Given value as identifier
     *
     * @param mixed $identifier
     */
    public function useIdentifier($identifier)
    {
        switch ($this->howToProcess($identifier)) {
            case 'values':
                $this->takeData($identifier);
                break;
            case 'reuse':
                $this->takeData($identifier->getData());
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
     * How to process
     *
     * @param \Ease\SQL\Engine $identifer
     *
     * @return string id|name|values|reuse|unknown
     */
    public function howToProcess($identifer)
    {
        $recognizedAs = 'unknown';
        switch (gettype($identifer)) {
            case "integer":
            case "double":
                if ($this->getKeyColumn()) {
                    $recognizedAs = 'id';
                }
                break;
            case "string":
                if (!empty($this->nameColumn)) {
                    $recognizedAs = 'name';
                }
                break;
            case "array":
                $recognizedAs = 'values';
                break;
            case "object":
                if ($identifer instanceof \Ease\Brick) {
                    $recognizedAs = 'reuse';
                }
                break;
            default :
            case "boolean":
            case "NULL":
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
     * Object init value stub
     * @param mixed $init
     */
    public function setInit($init)
    {

    }

    /**
     * Set/override object properties stub
     * 
     * @param array $properties
     */
    public function setProperties($properties)
    {

    }
}