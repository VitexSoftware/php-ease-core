<?php

/**
 * Zakladni objekt urceny k rodicovstvi pokročilým objektum.
 *
 * @author    Vitex <info@vitexsoftware.cz>
 * @copyright 2009-2021 info@vitexsoftware.cz (G)
 *
 * PHP 7
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
 * Zakladni objekt urceny k rodicovstvi vsem pouzivanym objektum.
 *
 * @author    Vitex <info@vitexsoftware.cz>
 * @copyright 2009-2023 info@vitexsoftware.cz (G)
 */
class Sand extends Molecule
{
    use Logger\Logging;

    /**
     * Default Language Code.
     */
    public ?string $langCode = null;

    /**
     * Common object data holder.
     */
    public ?array $data = null;

    /**
     * Odkaz na vlastnící objekt.
     *
     * @var mixed|Sand object
     */
    public $parentObject;

    /**
     * Sdílený objekt frameworku.
     */
    public Shared $easeShared;

    /**
     * Vynuluje všechny pole vlastností objektu.
     */
    public function dataReset(): void
    {
        $this->data = [];
    }

    /**
     * Načte $data do polí objektu.
     *
     * @param array $data  asociativní pole dat
     * @param bool  $reset vyprazdnit pole před naplněním ?
     *
     * @return int počet načtených položek
     */
    public function setData(array $data, $reset = false)
    {
        $ret = 0;

        if (!empty($data)) {
            if ($reset) {
                $this->dataReset();
            }

            $this->data = empty($this->data) ? $data : array_merge($this->data, $data);
            $ret = \count($data);
        }

        return $ret;
    }

    /**
     * Obtain data holded by object.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Vrací počet položek dat objektu.
     *
     * @return int
     */
    public function getDataCount()
    {
        return empty($this->data) ? 0 : \count($this->data);
    }

    /**
     * Vrací hodnotu z pole dat pro MySQL.
     *
     * @param string $columnName název hodnoty/sloupečku
     *
     * @return mixed
     */
    public function getDataValue($columnName)
    {
        return !empty($columnName) && \is_array($this->data)
            && \array_key_exists($columnName, $this->data) ? $this->data[$columnName] : null;
    }

    /**
     * Set data filed value.
     *
     * @param string $columnName název datové kolonky
     * @param mixed  $value      hodnota dat
     *
     * @return bool Success
     */
    public function setDataValue(string $columnName, $value)
    {
        $this->data[$columnName] = $value;

        return true;
    }

    /**
     * Odstrani polozku z pole dat pro MySQL.
     *
     * @param string $columnName název klíče k vymazání
     *
     * @return bool success
     */
    public function unsetDataValue(string $columnName)
    {
        $result = false;

        if (\is_array($this->data) && \array_key_exists($columnName, $this->data)) {
            unset($this->data[$columnName]);
            $result = true;
        }

        return $result;
    }

    /**
     * Převezme data do aktuálního pole dat.
     *
     * @param array $data asociativní pole dat
     *
     * @return int
     */
    public function takeData($data)
    {
        if (\is_array($this->data) && \is_array($data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }

        return empty($data) ? null : \count($data);
    }
}
