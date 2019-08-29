<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Ease;

use Ease\Functions;

/**
 * Description of FunctionsTest
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class FunctionsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers Ease\Functions::sysFilename
     */
    public function testsysFilename()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $this->assertContains(
                '\\\\', Functions::sysFilename('/'),
                _('Windows Files conversion')
            );
        } else {
            $this->assertStringContainsString(
                '/', Functions::sysFilename('\\\\'), _('Unix File Conversion')
            );
        }
    }

    /**
     * @covers Ease\Functions::addUrlParams
     */
    public function testAddUrlParams()
    {
        $this->assertEquals('http://vitexsoftware.cz/path?a=b&id=1',
            Functions::addUrlParams('http://vitexsoftware.cz/path?a=b',
                ['id' => 1], TRUE));
        $this->assertEquals('http://vitexsoftware.cz:80?id=1',
            Functions::addUrlParams('http://vitexsoftware.cz:80', ['id' => 1],
                TRUE));
    }

    /**
     * @covers Ease\Functions::linkify
     */
    public function testLinkify()
    {
        $this->assertEquals('<a  href="https://v.s.cz/">v.s.cz/</a>',
            \Ease\Functions::linkify('https://v.s.cz/'));
        $this->assertEquals('<a  a="1" href="mailto:info@vitexsoftware.cz">info@vitexsoftware.cz</a>',
            \Ease\Functions::linkify('info@vitexsoftware.cz', ['mail'], ['a' => 1]));
    }

    /**
     * @covers Ease\Functions::divDataArray
     */
    public function testDivDataArray()
    {
        $sourceArray      = ['a' => 1, 'b' => 2, 'c' => 3];
        $destinationArray = [];

        $this->assertTrue(\Ease\Functions::divDataArray($sourceArray,
                $destinationArray, 'b'));
        $this->assertFalse(\Ease\Functions::divDataArray($sourceArray,
                $destinationArray, 'b'));

        $this->assertEquals(['a' => 1, 'c' => 3], $sourceArray);
        $this->assertEquals(['b' => 2], $destinationArray);
    }

    /**
     * @covers Ease\Functions::isAssoc
     */
    public function testIsAssoc()
    {
        $this->assertTrue(\Ease\Functions::isAssoc(['a' => 'b']));
        $this->assertFalse(\Ease\Functions::isAssoc(['a', 'b']));
    }

    /**
     * @covers Ease\Functions::rip
     */
    public function testRip()
    {
        $this->assertEquals('kuprikladu', Functions::rip('kupříkladu'));
    }

    /**
     * @covers Ease\Functions::easeEncrypt
     */
    public function testEaseEncrypt()
    {
        $enc = Functions::easeEncrypt('secret', 'key');
        $this->assertEquals(Functions::easeDecrypt($enc, 'key'), 'secret');
    }

    /**
     * @covers Ease\Functions::easeDecrypt
     */
    public function testEaseDecrypt()
    {
        $enc = Functions::easeEncrypt('secret', 'key');
        $this->assertEquals(Functions::easeDecrypt($enc, 'key'), 'secret');
    }

    /**
     * @covers Ease\Functions::randomNumber
     */
    public function testRandomNumber()
    {
        $a = Functions::randomNumber();
        $b = Functions::randomNumber();
        $this->assertFalse($a == $b);

        $c = Functions::randomNumber(10, 20);
        $this->assertLessThan(21, $c);
        $this->assertGreaterThan(9, $c);

        $this->assertLessThan(21, Functions::randomNumber(10, 20));

        $this->expectExceptionMessage('Minimum cannot be bigger than maximum');

        Functions::randomNumber(30, 20);
    }

    /**
     * @covers Ease\Functions::randomString
     */
    public function testRandomString()
    {
        $a = Functions::randomString(22);
        $b = Functions::randomString();
        $this->assertFalse($a == $b);
    }

    /**
     * @covers Ease\Functions::recursiveIconv
     */
    public function testRecursiveIconv()
    {
        $original = ["\x80", "\x95"];
        $exepted  = ["\xe2\x82\xac", "\xe2\x80\xa2"];
        $this->assertEquals($exepted,
            Functions::recursiveIconv('cp1252', 'utf-8', $original));

        $this->assertEquals($exepted[0],
            Functions::recursiveIconv('cp1252', 'utf-8', $original[0]));
    }

    /**
     * @covers Ease\Functions::arrayIconv
     */
    public function testArrayIconv()
    {
        $original = "\x80";
        $exepted  = "\xe2\x82\xac";
        Functions::arrayIconv($original, 0, ['cp1252', 'utf-8']);
        $this->assertEquals($exepted, $original);
    }

    /**
     * @covers Ease\Functions::humanFilesize
     *
     * @todo   Implement testHumanFilesize().
     */
    public function testHumanFilesize()
    {
        $this->assertEquals('1.18 MB',
            str_replace(',', '.', Functions::humanFilesize('1234545')));
        $this->assertEquals('11.5 GB',
            str_replace(',', '.', Functions::humanFilesize('12345453453')));
        $this->assertEquals('1.1 PB',
            str_replace(',', '.', Functions::humanFilesize('1234545345332235')));
        $this->assertEquals('0 Byte', Functions::humanFilesize(false));
    }

    /**
     * @covers Ease\Functions::reindexArrayBy
     */
    public function testReindexArrayBy()
    {
        $a = [
            ['id' => '2', 'name' => 'b'],
            ['id' => '1', 'name' => 'a'],
            ['id' => '3', 'name' => 'c'],
        ];
        $c = [
            'a' => ['id' => '1', 'name' => 'a'],
            'b' => ['id' => '2', 'name' => 'b'],
            'c' => ['id' => '3', 'name' => 'c'],
        ];

        $this->assertEquals($c, Functions::reindexArrayBy($a, 'name'));

        $this->expectException('\Exception');

        Functions::reindexArrayBy($a, 'Xname');
    }

    /**
     * @covers Ease\Functions::isSerialized
     */
    public function testIsSerialized()
    {
        $this->assertFalse(Functions::isSerialized(1));
        $this->assertTrue(Functions::isSerialized('N;'));
        Functions::isSerialized(serialize($this));
        Functions::isSerialized('a:6:{s:1:"a";s:6:"string";s:1:"b";i:1;s:1:"c";d:2.4;s:1:"d";i:2222222222222;s:1:"e";O:8:"stdClass":0:{}s:1:"f";b:1;}');
        $this->assertTrue(Functions::isSerialized('a:1:{s:4:"test";b:1;'));
        $this->assertFalse(Functions::isSerialized('XXXX'));
        $this->assertFalse(Functions::isSerialized('s:x'));
        $this->assertFalse(Functions::isSerialized('b:x'));
        $this->assertTrue(Functions::isSerialized('d:19;'));
    }

    /**
     * @covers Ease\Functions::baseClassName
     */
    public function testBaseClassName()
    {
        $this->assertEquals('ToMemory',
            Functions::baseClassName(new \Ease\Logger\ToMemory()));
    }

    /**
     * @covers Ease\Functions::lettersOnly
     */
    public function testLettersOnly()
    {
        $this->assertEquals('1a2b3', Functions::lettersOnly('1a2b_3'));
    }
}
