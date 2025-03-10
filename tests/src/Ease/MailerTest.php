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

use Ease\Mailer;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-10-23 at 14:10:35.
 */
#[CoversClass(Mailer::class)]

class MailerTest extends SandTest
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new Mailer('info@vitexsoftware.cz', 'Unit Test');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers \Ease\Mailer::__construct
     */
    public function testConstructor(): void
    {
        $classname = \get_class($this->object);

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->__construct('info@vitexsoftware.cz', 'Unit Test');

        $mock->__construct('vitex@hippy.cz', 'Hallo', 'PHPUnit works well!');

        $this->assertEquals('PHPUnit works well!', $mock->textBody);
    }

    /**
     * @covers \Ease\Mailer::setMailHeaders
     */
    public function testSetMailHeaders(): void
    {
        $this->object->mailHeaders['From'] = 'ease@framework.cz';
        $this->object->setMailHeaders(['x-mail' => 'test']);
        $this->assertEquals('test', $this->object->getMailHeader('x-mail'));
        $this->assertEquals(
            'ease@framework.cz',
            $this->object->getMailHeader('From'),
        );
    }

    /**
     * @covers \Ease\Mailer::getMailHeader
     */
    public function testGetMailHeader(): void
    {
        $this->assertEquals(
            'info@vitexsoftware.cz',
            $this->object->getMailHeader('To'),
        );
    }

    /**
     * @covers \Ease\Mailer::setMailBody
     */
    public function testSetMailBody(): void
    {
        $this->assertTrue($this->object->setMailBody('mail body'));
    }

    /**
     * @covers \Ease\Mailer::addFile
     */
    public function testAddFile(): void
    {
        $this->assertTrue($this->object->addFile(__FILE__, 'text/x-php'));
    }

    /**
     * @covers \Ease\Mailer::draw
     *
     * @param null|mixed $whatWant
     */
    public function testDraw($whatWant = null): void
    {
        $this->assertEmpty($this->object->draw());
    }

    /**
     * @covers \Ease\Mailer::send
     */
    public function testSend(): void
    {
        $this->object->setMailBody('test');

        if (file_exists('/usr/sbin/sendmail')) {
            $this->assertTrue($this->object->send());
        } else {
            $this->markTestSkipped('Sendmail not found');
        }
    }

    /**
     * @covers \Ease\Mailer::setUserNotification
     */
    public function testSetUserNotification(): void
    {
        $this->object->setUserNotification(true);
        $this->assertTrue($this->object->notify);
        $this->object->setUserNotification(false);
        $this->assertFalse($this->object->notify);
    }
}
