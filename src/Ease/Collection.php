<?php

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
 * Class Ease\List.
 *
 * Provides a base class for handling and manipulating lists of data within the Ease Framework.
 * This class offers methods for managing collections, iterating over items, and performing
 * common list operations. It is designed to be extended or used as a utility for managing
 * arrays or other iterable data structures in PHP applications.
 *
 * Typical use cases include storing, filtering, and processing sets of data, as well as
 * providing a consistent interface for list-like structures throughout the framework.
 *
 * @author Vitex <info@vitexsoftware.cz>
 */

/**
 * @template T of object
 *
 * @implements \IteratorAggregate<int, T>
 */
class Collection implements \Countable, \IteratorAggregate
{
    /**
     * @var class-string<T>
     */
    private string $class;

    /**
     * @var list<T>
     */
    private array $items = [];

    /**
     * @param class-string<T> $class
     */
    public function __construct(string $class)
    {
        if (!\class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class %s does not exist', $class));
        }

        try {
            $ref = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException(sprintf('Class %s does not exist', $class));
        }

        if (!$ref->isInstantiable()) {
            throw new \InvalidArgumentException(sprintf('Class %s is not instantiable', $class));
    }

        $this->class = $class;
    }

    /**
     * @param T $item
     * 
     * @return self
     */
    public function add(object $item): self
    {
        if (!$item instanceof $this->class) {
            throw new \InvalidArgumentException(
                sprintf('Item must be instance of %s, %s given', $this->class, $item::class),
            );
        }

        $this->items[] = $item;
        return $this;
    }

    /**
     * Add New item defined by its pure data
     * 
     * @param array $data
     * 
     * @return self
     */
    public function addArray(array $data): self {
        $this->add(new $this->class($data));
        return $this;
    }

    /**
     * @return list<T>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return \ArrayIterator<int, T>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }
}
