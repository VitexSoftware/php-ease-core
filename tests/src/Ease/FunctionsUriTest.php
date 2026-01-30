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

use Ease\Brick;
use Ease\Functions;
use PHPUnit\Framework\TestCase;

class DummyBrick extends Brick
{
    public function __construct($id)
    {
        parent::__construct($id, ['keyColumn' => 'id']);
        $this->setDataValue('id', $id);
    }
}

class FunctionsUriTest extends TestCase
{
    /**
     * @covers \Ease\Functions::easeObjectToUri
     * @covers \Ease\Functions::uriToEaseObject
     */
    public function testEaseObjectToUriAndBack(): void
    {
        $functions = new Functions();
        $object = new DummyBrick(42);
        $uri = $functions->easeObjectToUri($object);
        $this->assertStringStartsWith('ease:', $uri);
        $this->assertStringContainsString('DummyBrick', $uri);
        $this->assertStringEndsWith('/42', $uri);

        $reconstructed = $functions->uriToEaseObject($uri);
        $this->assertInstanceOf(DummyBrick::class, $reconstructed);
        $this->assertEquals(42, $reconstructed->getMyKey());
    }

    /**
     * @covers \Ease\Functions::uriToEaseObject
     */
    public function testUriToEaseObjectInvalidScheme(): void
    {
        $functions = new Functions();
        $this->expectException(InvalidArgumentException::class);
        $functions->uriToEaseObject('http://foo/bar');
    }

    /**
     * @covers \Ease\Functions::uriToEaseObject
     */
    public function testUriToEaseObjectInvalidFormat(): void
    {
        $functions = new Functions();
        $this->expectException(InvalidArgumentException::class);
        $functions->uriToEaseObject('ease:onlyonepart');
    }

    /**
     * @covers \Ease\Functions::easeObjectToUri
     */
    public function testEaseObjectToUriWithStringKey(): void
    {
        $functions = new Functions();
        $object = new DummyBrick('abc123');
        $uri = $functions->easeObjectToUri($object);
        $this->assertStringEndsWith('/abc123', $uri);
        $reconstructed = $functions->uriToEaseObject($uri);
        $this->assertInstanceOf(DummyBrick::class, $reconstructed);
        $this->assertEquals('abc123', $reconstructed->getMyKey());
    }
}
