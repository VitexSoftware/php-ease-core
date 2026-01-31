<?php

declare(strict_types=1);

/**
 * This file is part of the EaseCore package.
 *
 * (c) Vítězslav Dvořák <info@vitexsoftware.cz>
 */

use Ease\Brick;
use Ease\Euri;
use PHPUnit\Framework\TestCase;

class DummyBrick extends Brick
{
    public function __construct(string $id, array $args = [])
    {
        parent::__construct($id, ['keyColumn' => 'id']);
        $this->setDataValue('id', $id);
    }
}

final class EuriTest extends TestCase
{
    public function testFromObjectToUriAndBack(): void
    {
        $object = new DummyBrick('42');

        $uri = Euri::fromObject($object);

        $this->assertStringStartsWith('ease:', $uri);
        $this->assertStringContainsString('DummyBrick', $uri);
        $this->assertStringContainsString('#42', $uri);

        $reconstructed = Euri::toObject($uri);

        $this->assertInstanceOf(DummyBrick::class, $reconstructed);
        $this->assertSame('42', $reconstructed->getMyKey());
    }

    public function testStringIdentifier(): void
    {
        $object = new DummyBrick('abc123');

        $uri = Euri::fromObject($object);

        $this->assertStringContainsString('#abc123', $uri);

        $reconstructed = Euri::toObject($uri);

        $this->assertSame('abc123', $reconstructed->getMyKey());
    }

    public function testUriWithQueryParameters(): void
    {
        $object = new DummyBrick('99');

        $uri = Euri::fromObject($object, [
            'limit' => 10,
            'detail' => 'full',
        ]);

        $this->assertStringContainsString('?limit=10', $uri);
        $this->assertStringContainsString('detail=full', $uri);

        $meta = Euri::validate($uri);

        $this->assertSame(DummyBrick::class, $meta['class']);
        $this->assertSame('99', $meta['id']);
        $this->assertSame(
            ['limit' => '10', 'detail' => 'full'],
            $meta['args']
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
            Euri::isValid('ease:DummyBrick#1')
        );

        $this->assertFalse(
            Euri::isValid('http://DummyBrick/1')
        );
    }

    public function testNormalize(): void
    {
        $uri = 'ease:DummyBrick#001?b=2&a=1';

        $normalized = Euri::normalize($uri);

        $this->assertSame(
            'ease:DummyBrick#001?a=1&b=2',
            $normalized
        );
    }

    public function testBuild(): void
    {
        $uri = Euri::build(
            DummyBrick::class,
            'uuid-123',
            ['x' => 'y']
        );

        $this->assertSame(
            'ease:DummyBrick#uuid-123?x=y',
            $uri
        );
    }
}
