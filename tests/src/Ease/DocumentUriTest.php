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

use Ease\DocumentUri;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ease\DocumentUri
 */
final class DocumentUriTest extends TestCase
{
    // ── build ────────────────────────────────────────────────────────────────

    public function testBuildMinimal(): void
    {
        $uri = DocumentUri::build('abraflexi', 'faktura-vydana', '12345');

        $this->assertSame('ease://abraflexi/faktura-vydana#12345', $uri);
    }

    public function testBuildWithArgs(): void
    {
        $uri = DocumentUri::build(
            'abraflexi',
            'faktura-vydana',
            '12345',
            ['serverUrl' => 'https://server:5434', 'company' => 'demo'],
        );

        $this->assertStringStartsWith('ease://abraflexi/faktura-vydana?', $uri);
        $this->assertStringContainsString('serverUrl=', $uri);
        $this->assertStringContainsString('company=demo', $uri);
        $this->assertStringEndsWith('#12345', $uri);
    }

    public function testBuildRecordIdIsPercentEncoded(): void
    {
        $uri = DocumentUri::build('pohoda', 'faktura', 'FA2026/0042');

        $this->assertStringEndsWith('#FA2026%2F0042', $uri);
    }

    public function testBuildLeadingSlashInDocumentTypeIsStripped(): void
    {
        $uri = DocumentUri::build('abraflexi', '/faktura-vydana', '1');

        $this->assertSame('ease://abraflexi/faktura-vydana#1', $uri);
    }

    // ── validate ─────────────────────────────────────────────────────────────

    public function testValidateReturnsCorrectFields(): void
    {
        $uri = 'ease://abraflexi/faktura-vydana?serverUrl=https%3A%2F%2Fserver%3A5434&company=demo#12345';

        $result = DocumentUri::validate($uri);

        $this->assertSame('abraflexi', $result['source_system']);
        $this->assertSame('faktura-vydana', $result['document_type']);
        $this->assertSame('12345', $result['record_id']);
        $this->assertSame('https://server:5434', $result['args']['serverUrl']);
        $this->assertSame('demo', $result['args']['company']);
    }

    public function testValidateWithoutArgs(): void
    {
        $result = DocumentUri::validate('ease://pohoda/faktura#FA2026%2F0042');

        $this->assertSame('pohoda', $result['source_system']);
        $this->assertSame('faktura', $result['document_type']);
        $this->assertSame('FA2026/0042', $result['record_id']);
        $this->assertSame([], $result['args']);
    }

    public function testValidateBuildRoundTrip(): void
    {
        $uri = DocumentUri::build(
            'money-s3',
            'vydane-faktury',
            'INV-2026-001',
            ['serverUrl' => 'http://money:81', 'tenant' => 'acme'],
        );

        $result = DocumentUri::validate($uri);

        $this->assertSame('money-s3', $result['source_system']);
        $this->assertSame('vydane-faktury', $result['document_type']);
        $this->assertSame('INV-2026-001', $result['record_id']);
        $this->assertSame('http://money:81', $result['args']['serverUrl']);
        $this->assertSame('acme', $result['args']['tenant']);
    }

    public function testValidateThrowsOnWrongScheme(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/scheme/i');

        DocumentUri::validate('http://abraflexi/faktura-vydana#1');
    }

    public function testValidateThrowsOnMissingSourceSystem(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        DocumentUri::validate('ease:///faktura-vydana#1');
    }

    public function testValidateThrowsOnUpperCaseSourceSystem(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/source system/i');

        DocumentUri::validate('ease://AbraFlexi/faktura-vydana#1');
    }

    public function testValidateThrowsOnMissingDocumentType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/document type/i');

        DocumentUri::validate('ease://abraflexi/#1');
    }

    public function testValidateThrowsOnMissingRecordId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/record identifier/i');

        DocumentUri::validate('ease://abraflexi/faktura-vydana');
    }

    public function testValidateThrowsOnMalformedUri(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        DocumentUri::validate('not a uri at all ://');
    }

    // ── convenience getters ──────────────────────────────────────────────────

    public function testGetSourceSystem(): void
    {
        $this->assertSame(
            'abraflexi',
            DocumentUri::getSourceSystem('ease://abraflexi/faktura-vydana#1'),
        );
    }

    public function testGetDocumentType(): void
    {
        $this->assertSame(
            'faktura-vydana',
            DocumentUri::getDocumentType('ease://abraflexi/faktura-vydana#1'),
        );
    }

    public function testGetRecordId(): void
    {
        $this->assertSame(
            '42',
            DocumentUri::getRecordId('ease://abraflexi/faktura-vydana#42'),
        );
    }

    public function testGetArgs(): void
    {
        $uri = DocumentUri::build('abraflexi', 'faktura-vydana', '1', ['company' => 'demo']);

        $this->assertSame(['company' => 'demo'], DocumentUri::getArgs($uri));
    }

    public function testGetArgsEmptyWhenNone(): void
    {
        $this->assertSame([], DocumentUri::getArgs('ease://pohoda/faktura#1'));
    }

    // ── isValid ──────────────────────────────────────────────────────────────

    public function testIsValidAcceptsWellFormedUri(): void
    {
        $this->assertTrue(DocumentUri::isValid('ease://abraflexi/faktura-vydana#12345'));
    }

    public function testIsValidRejectsHttpScheme(): void
    {
        $this->assertFalse(DocumentUri::isValid('http://abraflexi/faktura-vydana#1'));
    }

    public function testIsValidRejectsEuriWithPhpClass(): void
    {
        // Euri-style URIs (PHP class path) are not valid DocumentUri
        $this->assertFalse(DocumentUri::isValid('ease://Ease/Brick#42'));
    }

    public function testIsValidRejectsUriWithNoFragment(): void
    {
        $this->assertFalse(DocumentUri::isValid('ease://abraflexi/faktura-vydana'));
    }

    // ── source system identifiers ────────────────────────────────────────────

    public function testHyphenatedSourceSystemIsAccepted(): void
    {
        $uri = DocumentUri::build('money-s3', 'invoice', '1');

        $this->assertSame('money-s3', DocumentUri::getSourceSystem($uri));
    }

    public function testNumericSuffixInSourceSystemIsAccepted(): void
    {
        $uri = DocumentUri::build('pohoda2', 'faktura', '1');

        $this->assertSame('pohoda2', DocumentUri::getSourceSystem($uri));
    }
}
