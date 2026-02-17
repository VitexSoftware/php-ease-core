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

namespace Test\Ease;

use Ease\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Collection functionality.
 *
 * @covers \Ease\Collection
 */
final class CollectionTest extends TestCase
{
    public function testCountable(): void
    {
        $bus = new Collection(\stdClass::class);

        $this->assertSame(0, $bus->count());

        $bus->add(new \stdClass());
        $this->assertSame(1, $bus->count());

        $bus->add(new \stdClass());
        $this->assertSame(2, $bus->count());
    }

    public function testIterator(): void
    {
        $bus = new Collection(\stdClass::class);

        $item1 = new \stdClass();
        $item2 = new \stdClass();

        $bus->add($item1);
        $bus->add($item2);

        // Test iterator functionality
        $items = [];

        foreach ($bus as $key => $item) {
            $items[$key] = $item;
        }

        $this->assertCount(2, $items);
        $this->assertSame($item1, $items[0]);
        $this->assertSame($item2, $items[1]);
    }

    public function testTypeValidation(): void
    {
        $bus = new Collection(\stdClass::class);

        // Valid type
        $bus->add(new \stdClass());

        // Invalid type should throw exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Item must be instance of stdClass, Exception given');

        $bus->add(new \Exception());
    }

    public function testClear(): void
    {
        $bus = new Collection(\stdClass::class);

        $bus->add(new \stdClass());
        $bus->add(new \stdClass());

        $this->assertSame(2, $bus->count());

        $bus->clear();

        $this->assertSame(0, $bus->count());
    }

    public function testGetItems(): void
    {
        $bus = new Collection(\stdClass::class);

        $item1 = new \stdClass();
        $item2 = new \stdClass();

        $bus->add($item1);
        $bus->add($item2);

        $items = $bus->getItems();

        $this->assertCount(2, $items);
        $this->assertSame($item1, $items[0]);
        $this->assertSame($item2, $items[1]);
    }

    public function testAddArray(): void
    {
        // Anonymous class that accepts an array in constructor
        $proto = new class(['a' => 1]) {
            public array $data;

            public function __construct(array $data)
            {
                $this->data = $data;
            }
        };

        $className = \get_class($proto);

        $collection = new Collection($className);

        $collection->addArray(['a' => 1, 'b' => 2]);

        $items = $collection->getItems();

        $this->assertCount(1, $items);
        $this->assertSame(['a' => 1, 'b' => 2], $items[0]->data);
    }
}
