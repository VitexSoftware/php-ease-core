<?php

/**
 * Record Key methods.
 *
 * @author    Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright 2019 info@vitexsoftware.cz (G)
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

/**
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
trait RecordKey
{
    /**
     * Key Column for Current Record.
     */
    public string $keyColumn = 'id';

    /**
     * Obtain data holded by object.
     *
     * @return array
     */
    abstract public function getData();

    /**
     * Set data filed value.
     *
     * @param string $columnName název datové kolonky
     * @param mixed  $value      hodnota dat
     *
     * @return bool Success
     */
    abstract public function setDataValue(string $columnName, $value);

    /**
     * Gives you value of KEY Column.
     *
     * @param array $data data z nichž se vrací hodnota klíče
     *
     * @return int key column value
     */
    public function getMyKey($data = null)
    {
        return null === $data ? $this->getDataValue($this->getKeyColumn()) :
            (\array_key_exists($this->getKeyColumn(), $data) ?
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
     * Gives you Current KeyColumn Name.
     *
     * @return string
     */
    public function getKeyColumn()
    {
        return $this->keyColumn;
    }

    /**
     * Nastaví jméno klíčového sloupečku v shopu.
     */
    public function setKeyColumn(string $keyColumn): void
    {
        $this->keyColumn = $keyColumn;
    }
}
