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

use Ease\Molecule;
/**
 * @codingStandardsIgnoreFile
 *
 * @codeCoverageIgnoreStart
 */
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-09-22 at 07:47:58.
 */
#[CoversClass(Molecule::class)]

class MoleculeTest extends AtomTest
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Molecule();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * t This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers \Ease\Molecule::setObjectName
     */
    public function testSetObjectName(): void
    {
        $this->object->setObjectName('Testing');
        $this->assertEquals('Testing', $this->object->getObjectName());
        $this->object->setObjectName();
        $this->assertEquals(
            \get_class($this->object),
            $this->object->getObjectName(),
        );
    }

    /**
     * @covers \Ease\Molecule::getObjectName
     */
    public function testGetObjectName(): void
    {
        $this->assertNotEmpty($this->object->getObjectName());
    }

    /**
     * @covers \Ease\Molecule::setupProperty
     */
    public function testSetupProperty(): void
    {
        if (!\defined('OBJNAME')) {
            \define('OBJNAME', 'CONSTATNT');
        }

        $this->object->setupProperty(['objectName' => 'Original'], 'objectName', 'OBJNAME');
        $this->assertEquals('Original', $this->object->objectName);

        $this->object->setupProperty(['OBJNAME' => 'Copy'], 'objectName', 'OBJNAME');
        $this->assertEquals('Copy', $this->object->objectName);

        $this->object->objectName = '';
        $this->object->setupProperty(['key' => 'value'], 'objectName', 'OBJNAME');
        $this->assertEquals('CONSTATNT', $this->object->objectName);

        $this->object->setupProperty(['objectName' => 'ARRAY'], 'objectName', 'OBJNAME');
        $this->assertEquals('ARRAY', $this->object->objectName);

        putenv('ENVTEST=TEST');

        $this->object->setupProperty([], 'objectName', 'ENVTEST');
        $this->assertEquals('TEST', $this->object->objectName);
    }
}

// @codeCoverageIgnoreEnd
