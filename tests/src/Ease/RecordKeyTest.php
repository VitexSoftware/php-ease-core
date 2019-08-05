<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Ease;

/**
 * Description of RecordKeyTest
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class RecordKeyTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var SandTest
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new Local\SandTester();
    }

    /**
     * @covers Ease\RecordKey::getMyKey
     */
    public function testGetMyKey()
    {
        $this->object->setmyKey('test');
        $this->assertEquals('test', $this->object->getmyKey());
        $this->assertEquals('X', $this->object->getmyKey(['id' => 'X']));
    }

    /**
     * @covers Ease\RecordKey::setMyKey
     */
    public function testSetMyKey()
    {
        $this->object->setmyKey('test');
        $this->assertEquals('test', $this->object->getmyKey());

        $this->object->setkeyColumn(null);
        $this->assertNull($this->object->getmyKey());
    }

    /**
     * @covers Ease\RecordKey::getkeyColumn
     */
    public function testGetkeyColumn()
    {
        $this->object->setkeyColumn('test');
        $this->assertEquals('test', $this->object->getKeyColumn());
    }

    /**
     * @covers Ease\RecordKey::setkeyColumn
     */
    public function testSetkeyColumn()
    {
        $this->object->setkeyColumn('test');
        $this->assertEquals('test', $this->object->getKeyColumn());
    }
}
