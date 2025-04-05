<?php

/**
 * Zakladni objekt urceny k rodicovstvi pokročilým objektum.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2021 Vitex@hippy.cz (G)
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
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
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
     *
     * @var array<string, mixed>
     */
    public ?array $data = null;

    /**
     * Odkaz na vlastnící objekt.
     *
     * @var mixed|Sand object
     */
    public ?Atom $parentObject;

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
     *
     * @phpstan-ignore missingType.iterableValue
     */
    public function setData(array $data, $reset = false)
    {
        $ret = 0;

        if ($data !== []) {
            if ($reset) {
                $this->dataReset();
            }

            $this->data = $this->data === null || $this->data === [] ? $data : array_merge($this->data, $data);
            $ret = \count($data);
        }

        return $ret;
    }

    /**
     * Obtain data holded by object.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Vrací počet položek dat objektu.
     */
    public function getDataCount(): int
    {
        return $this->data === null || $this->data === [] ? 0 : \count($this->data);
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
    public function setDataValue(string $columnName, $value): bool
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
    public function unsetDataValue(string $columnName): bool
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
     * @var array<string, mixed>
     */
    public function takeData(array $data): int
    {
        $this->data = \is_array($this->data) ? array_merge($this->data, $data) : $data;

        return $data === [] ? 0 : \count($data);
    }
}
