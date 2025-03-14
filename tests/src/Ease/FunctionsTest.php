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

use Ease\Functions;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Description of FunctionsTest.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
#[CoversClass(Functions::class)]
class FunctionsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \Functions::sysFilename
     */
    public function testsysFilename(): void
    {
        if (strtoupper(substr(\PHP_OS, 0, 3)) === 'WIN') {
            $this->assertStringContainsString(
                '\\\\',
                Functions::sysFilename('/'),
                _('Windows Files conversion'),
            );
        } else {
            $this->assertStringContainsString(
                '/',
                Functions::sysFilename('\\\\'),
                _('Unix File Conversion'),
            );
        }
    }

    /**
     * @covers \Functions::addUrlParams
     */
    public function testAddUrlParams(): void
    {
        $this->assertEquals(
            'http://vitexsoftware.cz/path?a=b&id=1',
            Functions::addUrlParams(
                'http://vitexsoftware.cz/path?a=b',
                ['id' => '1'],
                true,
            ),
        );
        $this->assertEquals(
            'http://vitexsoftware.cz:80?id=1',
            Functions::addUrlParams(
                'http://vitexsoftware.cz:80',
                ['id' => '1'],
                true,
            ),
        );
    }

    /**
     * @covers \Functions::divDataArray
     */
    public function testDivDataArray(): void
    {
        $sourceArray = ['a' => 1, 'b' => 2, 'c' => 3];
        $destinationArray = [];

        $this->assertTrue(Functions::divDataArray(
            $sourceArray,
            $destinationArray,
            'b',
        ));
        $this->assertFalse(Functions::divDataArray(
            $sourceArray,
            $destinationArray,
            'b',
        ));

        $this->assertEquals(['a' => 1, 'c' => 3], $sourceArray);
        $this->assertEquals(['b' => 2], $destinationArray);
    }

    /**
     * @covers \Functions::isAssoc
     */
    public function testIsAssoc(): void
    {
        $this->assertTrue(Functions::isAssoc(['a' => 'b']));
        $this->assertFalse(Functions::isAssoc(['a', 'b']));
    }

    /**
     * @covers \Functions::rip
     */
    public function testRip(): void
    {
        $this->assertEquals('kuprikladu', Functions::rip('kupříkladu'));
    }

    /**
     * @covers \Functions::easeEncrypt
     */
    public function testEaseEncrypt(): void
    {
        $enc = Functions::easeEncrypt('secret', 'key');
        $this->assertEquals(Functions::easeDecrypt($enc, 'key'), 'secret');
    }

    /**
     * @covers \Functions::easeDecrypt
     */
    public function testEaseDecrypt(): void
    {
        $enc = Functions::easeEncrypt('secret', 'key');
        $this->assertEquals(Functions::easeDecrypt($enc, 'key'), 'secret');
    }

    /**
     * @covers \Functions::randomNumber
     */
    public function testRandomNumber(): void
    {
        $a = Functions::randomNumber();
        $b = Functions::randomNumber();
        $this->assertFalse($a === $b);

        $c = Functions::randomNumber(10, 20);
        $this->assertLessThan(21, $c);
        $this->assertGreaterThan(9, $c);

        $this->assertLessThan(21, Functions::randomNumber(10, 20));

        $this->expectExceptionMessage('Minimum cannot be bigger than maximum');

        Functions::randomNumber(30, 20);
    }

    /**
     * @covers \Functions::randomString
     */
    public function testRandomString(): void
    {
        $a = Functions::randomString(22);
        $b = Functions::randomString();
        $this->assertFalse($a === $b);
    }

    /**
     * @covers \Functions::recursiveIconv
     */
    public function testRecursiveIconv(): void
    {
        $original = ["\x80", "\x95"];
        $exepted = ["\xe2\x82\xac", "\xe2\x80\xa2"];
        $this->assertEquals(
            $exepted,
            Functions::recursiveIconv('cp1252', 'utf-8', $original),
        );

        $this->assertEquals(
            $exepted[0],
            Functions::recursiveIconv('cp1252', 'utf-8', $original[0]),
        );
    }

    /**
     * @covers \Functions::arrayIconv
     */
    public function testArrayIconv(): void
    {
        $original = "\x80";
        $exepted = "\xe2\x82\xac";
        Functions::arrayIconv($original, 0, ['cp1252', 'utf-8']);
        $this->assertEquals($exepted, $original);
    }

    /**
     * @covers \Functions::humanFilesize
     */
    public function testHumanFilesize(): void
    {
        $this->assertEquals(
            '1.18 MB',
            str_replace(',', '.', Functions::humanFilesize(1234545)),
        );

        if (php_uname('m') !== 'armv7l') {  // TODO #6 fix better
            $this->assertEquals(
                '11.5 GB',
                str_replace(',', '.', Functions::humanFilesize(12345453453)),
            );
            $this->assertEquals(
                '1.1 PB',
                str_replace(',', '.', Functions::humanFilesize(1234545345332235)),
            );
        }

        $this->assertEquals('0 Byte', Functions::humanFilesize(0));
    }

    /**
     * @covers \Functions::reindexArrayBy
     */
    public function testReindexArrayBy(): void
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
     * @covers \Functions::isSerialized
     */
    public function testIsSerialized(): void
    {
        $this->assertTrue(Functions::isSerialized('N;'));
        Functions::isSerialized(serialize(new \stdClass()));
        Functions::isSerialized('a:6:{s:1:"a";s:6:"string";s:1:"b";i:1;s:1:"c";d:2.4;s:1:"d";i:2222222222222;s:1:"e";O:8:"stdClass":0:{}s:1:"f";b:1;}');
        $this->assertTrue(Functions::isSerialized('a:1:{s:4:"test";b:1;'));
        $this->assertFalse(Functions::isSerialized('XXXX'));
        $this->assertFalse(Functions::isSerialized('s:x'));
        $this->assertFalse(Functions::isSerialized('b:x'));
        $this->assertTrue(Functions::isSerialized('d:19;'));
    }

    /**
     * @covers \Functions::baseClassName
     */
    public function testBaseClassName(): void
    {
        $this->assertEquals(
            'ToMemory',
            Functions::baseClassName(new \Ease\Logger\ToMemory()),
        );
    }

    /**
     * @covers \Functions::lettersOnly
     */
    public function testLettersOnly(): void
    {
        $this->assertEquals('1a2b3', Functions::lettersOnly('1a2b_3'));
    }

    /**
     * @covers \Functions::formatBytes
     */
    public function testFormatBytes(): void
    {
        $this->assertEquals('0 B', Functions::formatBytes(0));
        $this->assertEquals('1 B', Functions::formatBytes(1));
        $this->assertEquals('1 KiB', Functions::formatBytes(1024));
        $this->assertEquals('1 MiB', Functions::formatBytes(1048576));
        $this->assertEquals('1 GiB', Functions::formatBytes(1073741824));
        $this->assertEquals('1 TiB', Functions::formatBytes(1099511627776));
        $this->assertEquals('1 PiB', Functions::formatBytes(1125899906842624));
        $this->assertEquals('1 EiB', Functions::formatBytes(1152921504606846976));
        $this->assertEquals(
            '1 ZiB',
            Functions::formatBytes(1180591620717411303424),
        );
        $this->assertEquals(
            '1 YiB',
            Functions::formatBytes(1208925819614629174706176),
        );
    }

    /**
     * @covers \Functions::classesInNamespace
     */
    public function testclassesInNamespace(): void
    {
        $this->assertIsArray(Functions::classesInNamespace('Ease'));
    }

    /**
     * @covers \Functions::guidv4
     */
    public function testguidv4(): void
    {
        $this->assertEquals('74657374-7465-4374-b465-737474657374', Functions::guidv4('testtesttesttest'));
    }
    
    public function testisUuid() {
        $this->assertTrue(Functions::isUuid('25bced87-30df-4e42-b280-d4cdb1c85282'));
        $this->assertFalse(Functions::isUuid("NOUID"));
    }
    
}
