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

use Ease\Euri;
use PHPUnit\Framework\TestCase;

/**
 * Dummy brick for Ease URI tests (validate() requires class to exist).
 */
class DummyBrick extends \Ease\Brick
{
}

final class EaseUriTest extends TestCase
{
    public function testFromObjectToUriAndBack(): void
    {
        $object = new \Ease\Brick('42', ['keyColumn' => 'id']);
        $object->setDataValue('id', '42');

        $uri = Euri::fromObject($object);

        $this->assertStringStartsWith('ease:', $uri);
        $this->assertStringContainsString('Brick', $uri);
        $this->assertStringContainsString('#42', $uri);

        $reconstructed = Euri::toObject($uri);

        $this->assertInstanceOf(\Ease\Brick::class, $reconstructed);
        $this->assertSame('42', $reconstructed->getMyKey());
    }

    public function testStringIdentifier(): void
    {
        $object = new \Ease\Brick('abc123', ['keyColumn' => 'id']);
        $object->setDataValue('id', 'abc123');

        $uri = Euri::fromObject($object);

        $this->assertStringContainsString('#abc123', $uri);

        $reconstructed = Euri::toObject($uri);

        $this->assertSame('abc123', $reconstructed->getMyKey());
    }

    public function testUriWithQueryParameters(): void
    {
        $object = new \Ease\Brick('99', ['keyColumn' => 'id']);
        $object->setDataValue('id', '99');

        $uri = Euri::fromObject($object, [
            'limit' => 10,
            'detail' => 'full',
        ]);

        $this->assertStringContainsString('?limit=10', $uri);
        $this->assertStringContainsString('detail=full', $uri);

        $meta = Euri::validate($uri);

        $this->assertSame(\Ease\Brick::class, $meta['class']);
        $this->assertSame('99', $meta['id']);
        $this->assertSame(
            ['limit' => '10', 'detail' => 'full'],
            $meta['args'],
        );
    }

    public function testInvalidScheme(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Euri::validate('http://example.com/Foo#1');
    }

    public function testMissingIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Euri::validate('ease:DummyBrick');
    }

    public function testIsValid(): void
    {
        $this->assertTrue(
            Euri::isValid('ease:DummyBrick#1'),
        );

        $this->assertFalse(
            Euri::isValid('http://DummyBrick/1'),
        );
    }

    public function testNormalize(): void
    {
        $uri = 'ease:DummyBrick#001?b=2&a=1';

        $normalized = Euri::normalize($uri);

        $this->assertSame(
            'ease:DummyBrick?a=1&b=2#001',
            $normalized,
        );
    }

    public function testBuild(): void
    {
        $uri = Euri::build(
            \Ease\Brick::class,
            'uuid-123',
            ['x' => 'y'],
        );

        $this->assertSame(
            'ease:Brick?x=y#uuid-123',
            $uri,
        );
    }
}
