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

namespace Ease;

/**
 * URI identifying a document in an external source system.
 *
 * Extends the Euri pattern but maps host+path to a source system and document
 * type instead of a PHP class name, so no class_exists() check is performed.
 * Instance-specific parameters (server URL, company, tenant, etc.) travel in
 * the query string — their names and semantics are defined by each adapter.
 *
 * Format:
 *   ease://{sourceSystem}/{documentType}?{instanceArgs}#{recordId}
 *
 * Examples:
 *   ease://abraflexi/faktura-vydana?serverUrl=https%3A%2F%2Fserver%3A5434&company=demo#12345
 *   ease://pohoda/faktura?serverUrl=http%3A%2F%2Fpohoda%3A5336&ico=12345678#FA2026%2F0042
 *   ease://money-s3/vydane-faktury?serverUrl=http%3A%2F%2Fmoney%3A81#INV-2026-001
 */
class DocumentUri
{
    /**
     * Validate and parse a DocumentUri string.
     *
     * @throws \InvalidArgumentException on any format violation
     *
     * @return array{
     *   source_system: string,
     *   document_type: string,
     *   record_id:     string,
     *   args:          array<string, string>
     * }
     */
    public static function validate(string $uri): array
    {
        $parsed = parse_url($uri);

        if ($parsed === false) {
            throw new \InvalidArgumentException('Malformed DocumentUri');
        }

        if (($parsed['scheme'] ?? null) !== 'ease') {
            throw new \InvalidArgumentException('DocumentUri must use the ease: scheme');
        }

        $sourceSystem = $parsed['host'] ?? '';

        if ($sourceSystem === '' || !preg_match('~^[a-z][a-z0-9-]*$~', $sourceSystem)) {
            throw new \InvalidArgumentException(
                "Invalid source system identifier '{$sourceSystem}'; must be lowercase alphanumeric with hyphens",
            );
        }

        $documentType = ltrim($parsed['path'] ?? '', '/');

        if ($documentType === '') {
            throw new \InvalidArgumentException('Missing document type in DocumentUri path');
        }

        $recordId = urldecode($parsed['fragment'] ?? '');

        if ($recordId === '') {
            throw new \InvalidArgumentException('Missing record identifier (URI fragment)');
        }

        $args = [];

        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $args);
        }

        return [
            'source_system' => $sourceSystem,
            'document_type' => $documentType,
            'record_id' => $recordId,
            'args' => $args,
        ];
    }

    /**
     * Build a DocumentUri string.
     *
     * @param string               $sourceSystem Adapter identifier, e.g. 'abraflexi', 'pohoda'
     * @param string               $documentType Evidence / entity name within the source system
     * @param string               $recordId     Record identifier (numeric ID, code, etc.)
     * @param array<string, mixed> $args         Instance-specific parameters (serverUrl, company, …)
     */
    public static function build(
        string $sourceSystem,
        string $documentType,
        string $recordId,
        array $args = [],
    ): string {
        $base = 'ease://'.$sourceSystem.'/'.ltrim($documentType, '/');

        if ($args) {
            $base .= '?'.http_build_query($args, '', '&', \PHP_QUERY_RFC3986);
        }

        return $base.'#'.rawurlencode($recordId);
    }

    /**
     * Return the source system identifier from a URI.
     *
     * @throws \InvalidArgumentException
     */
    public static function getSourceSystem(string $uri): string
    {
        return self::validate($uri)['source_system'];
    }

    /**
     * Return the document type from a URI.
     *
     * @throws \InvalidArgumentException
     */
    public static function getDocumentType(string $uri): string
    {
        return self::validate($uri)['document_type'];
    }

    /**
     * Return the record identifier from a URI.
     *
     * @throws \InvalidArgumentException
     */
    public static function getRecordId(string $uri): string
    {
        return self::validate($uri)['record_id'];
    }

    /**
     * Return the instance-specific arguments from a URI.
     *
     * @throws \InvalidArgumentException
     *
     * @return array<string, string>
     */
    public static function getArgs(string $uri): array
    {
        return self::validate($uri)['args'];
    }

    public static function isValid(string $uri): bool
    {
        try {
            self::validate($uri);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
