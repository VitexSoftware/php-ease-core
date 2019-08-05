<?php
/**
 * Record Key methods
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2019 Vitex@hippy.cz (G)
 * 
 * @package EasePHP
 * 
 * PHP 7
 */

namespace Ease;

/**
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
trait RecordKey
{
    /**
     * Key Column for Current Record
     *
     * @var string
     */
    public $keyColumn = 'id';

    /**
     * Gives you value of KEY Column
     *
     * @param array $data data z nichž se vrací hodnota klíče
     *
     * @return int key column value
     */
    public function getMyKey($data = null)
    {
        $key = null;
        if (is_null($data)) {
            $data = $this->getData();
        }
        if (isset($data) && isset($data[$this->keyColumn])) {
            $key = $data[$this->keyColumn];
        }

        return $key;
    }

    /**
     * Nastavuje hodnotu klíčového políčka pro SQL.
     *
     * @param int|string $myKeyValue
     *
     * @return bool
     */
    public function setMyKey($myKeyValue)
    {
        return $this->setDataValue($this->keyColumn, $myKeyValue);
    }

    /**
     * Gives you Current KeyColumn Name
     *
     * @return string
     */
    public function getKeyColumn()
    {
        return $this->keyColumn;
    }

    /**
     * Nastaví jméno klíčového sloupečku v shopu.
     *
     * @param string $keyColumn
     */
    public function setkeyColumn($keyColumn)
    {
        $this->keyColumn = $keyColumn;
    }
}
