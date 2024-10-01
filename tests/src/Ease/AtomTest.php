<?php

/**
 * Základní objekty systému.
 *
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2009-2021 Vitex@hippy.cz (G)
 */
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

declare(strict_types=1);

namespace Test\Ease;

use Ease\Atom;

/**
 * Test class for EaseAtom.
 * Generated by PHPUnit on 2012-03-17 at 23:53:07.
 *
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2009-2024 Vitex@hippy.cz (G)
 */
class AtomTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var Atom
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->object = new Atom();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void {
        
    }

    /**
     * @covers Ease\Atom::getObjectName
     */
    public function testgetObjectName() {
        $this->assertNotEmpty($this->object->getObjectName());
    }

    /**
     * @covers Ease\Atom::__toString
     */
    public function test__toString() {
        $this->assertIsString($this->object->__toString());
    }

    /**
     * @covers Ease\Atom::draw
     */
    public function testDraw($whatWant = null) {
        $this->assertEquals('', $this->object->draw());
    }

}

// @codeCoverageIgnoreEnd
