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

declare(strict_types=1);

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
     * Obtain data holded by object
     *
     * @return array
     */
    abstract public function getData();

    /**
     * Set data filed value
     *
     * @param string $columnName název datové kolonky
     * @param mixed  $value      hodnota dat
     *
     * @return bool Success
     */
    abstract public function setDataValue(string $columnName, $value);

    /**
     * Gives you value of KEY Column
     *
     * @param array $data data z nichž se vrací hodnota klíče
     *
     * @return int key column value
     */
    public function getMyKey($data = null)
    {
        return is_null($data) ? $this->getDataValue($this->getKeyColumn()) :
            (array_key_exists($this->getKeyColumn(), $data) ?
                $data[$this->getKeyColumn()] : null);
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
        return empty($this->getKeyColumn()) ? null : $this->setDataValue($this->getKeyColumn(), $myKeyValue);
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
    public function setKeyColumn(string $keyColumn)
    {
        $this->keyColumn = $keyColumn;
    }
}
