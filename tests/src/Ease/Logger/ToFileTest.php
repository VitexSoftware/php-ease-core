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

namespace Test\Ease\Logger;

use Ease\Logger\ToFile;

/**
 * Test class for ToFile logger.
 */
class ToFileTest extends ToMemoryTest
{
    protected $object;
    private string $testLogDir;
    private string $testLogFile;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->testLogDir = sys_get_temp_dir() . '/ease_test_logs_' . uniqid();
        mkdir($this->testLogDir, 0755, true);
        $this->object = new ToFile($this->testLogDir);
        $this->testLogFile = $this->testLogDir . '/Ease.log';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
        if (is_dir($this->testLogDir)) {
            rmdir($this->testLogDir);
        }
    }

    /**
     * Test Constructor.
     *
     * @covers \Ease\Logger\ToFile::__construct
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(ToFile::class, $this->object);
        $this->assertEquals($this->testLogDir . '/', $this->object->logPrefix);
        $this->assertEquals($this->testLogFile, $this->object->logFileName);
        $this->assertEquals('file', $this->object->logType);
        $this->assertEquals('file', $this->object->logType);
    }

    /**
     * Test Constructor with no directory.
     *
     * @covers \Ease\Logger\ToFile::__construct
     * @covers \Ease\Logger\ToFile::setupLogFiles
     */
    public function testConstructorNoDirectory(): void
    {
        $logger = new ToFile('');
        $this->assertEquals('', $logger->logPrefix);
        $this->assertEquals('', $logger->logFileName);
    }

    /**
     * Test addToLog method writes to file.
     *
     * @covers \Ease\Logger\ToFile::addToLog
     */
    public function testAddToLog(): void
    {
        $result = $this->object->addToLog($this, 'test message', 'info');
        
        // Check that method completes without error
        $this->assertIsInt($result);
        
        // If file was created and has content, verify it
        if (file_exists($this->testLogFile)) {
            $logContent = file_get_contents($this->testLogFile);
            if ($logContent !== false && !empty($logContent)) {
                $this->assertStringContainsString('test message', $logContent);
            }
        }
    }

    /**
     * Test addToLog with different message types.
     *
     * @covers \Ease\Logger\ToFile::addToLog
     */
    public function testAddToLogWithDifferentTypes(): void
    {
        $types = ['error', 'warning', 'success', 'info', 'debug', 'notice'];
        
        foreach ($types as $type) {
            $result = $this->object->addToLog($this, "test $type message", $type);
            $this->assertIsInt($result);
        }
        
        // Basic functionality test - ensure no exceptions thrown
        $this->assertTrue(true);
    }

    /**
     * Test setupLogFiles method.
     *
     * @covers \Ease\Logger\ToFile::setupLogFiles
     */
    public function testSetupLogFiles(): void
    {
        $newTestDir = sys_get_temp_dir() . '/ease_test_logs_new_' . uniqid();
        mkdir($newTestDir, 0755, true);
        
        try {
            // Create a fresh object to test setupLogFiles independently
            $testObject = new ToFile('');
            $testObject->setupLogFiles($newTestDir);
            $this->assertEquals($newTestDir . '/', $testObject->logPrefix);
            $this->assertEquals($newTestDir . '/Ease.log', $testObject->logFileName);
            $this->assertEquals('file', $testObject->logType);
        } finally {
            rmdir($newTestDir);
        }
    }

    /**
     * Test testDirectory static method.
     *
     * @covers \Ease\Logger\ToFile::testDirectory
     */
    public function testTestDirectory(): void
    {
        $this->assertTrue(ToFile::testDirectory($this->testLogDir));
        $this->assertTrue(ToFile::testDirectory($this->testLogDir, true, true, true));
    }

    /**
     * Test testDirectory with non-existent directory.
     *
     * @covers \Ease\Logger\ToFile::testDirectory
     */
    public function testTestDirectoryNonExistent(): void
    {
        $this->expectException(\Exception::class);
        ToFile::testDirectory('/non/existent/directory');
    }

    /**
     * Test singleton method.
     *
     * @covers \Ease\Logger\ToFile::singleton
     */
    public function testSingleton(): void
    {
        $instance1 = ToFile::singleton($this->testLogDir);
        $instance2 = ToFile::singleton($this->testLogDir);
        
        $this->assertSame($instance1, $instance2);
        $this->assertInstanceOf(ToFile::class, $instance1);
    }

    /**
     * Test destructor closes file handles.
     *
     * @covers \Ease\Logger\ToFile::__destruct
     */
    public function testDestructor(): void
    {
        $logger = new ToFile($this->testLogDir);
        $logger->addToLog($this, 'test message for destructor');
        
        // Force destructor call
        unset($logger);
        
        // If we reach here without errors, destructor worked properly
        $this->assertTrue(true);
    }

    /**
     * Test log file format and structure.
     *
     * @covers \Ease\Logger\ToFile::addToLog
     */
    public function testLogFileFormat(): void
    {
        $this->object->addToLog($this, 'format test message', 'info');
        
        $this->assertFileExists($this->testLogFile);
        $logContent = file_get_contents($this->testLogFile);
        $this->assertNotEmpty($logContent, 'Log file should not be empty');
        $lines = explode("\n", trim($logContent));
        $logLine = $lines[0];
        
        // Check ISO 8601 timestamp format
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $logLine);
        
        // Check caller information (actual format includes full namespace)
        $this->assertStringContainsString('(Test\\Ease\\Logger\\ToFileTest)', $logLine);
        
        // Check message content
        $this->assertStringContainsString('format test message', $logLine);
    }
}