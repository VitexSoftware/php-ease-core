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

final class Euri
{
    private function __construct()
    {
        // Static-only utility class
    }

    /**
     * Validate EaseURI and return parsed metadata.
     *
     * Expected format:
     *   ease:Namespace/Class#IDENTIFIER?key=value
     *
     * Encoding: path uses literal / (no %2F) for human readability; query argument
     * values are URL-encoded for usability with special characters.
     *
     * Identifier rules:
     * - treated as string
     * - interpretation (id / name / uuid) is responsibility of the target object
     *
     * @throws \InvalidArgumentException
     *
     * @return array{
     *   class: string,
     *   id: string,
     *   args: array<string, mixed>
     * }
     */
    public static function validate(string $uri): array
    {
        $parsed = parse_url($uri);

        if ($parsed === false) {
            throw new \InvalidArgumentException('Malformed URI');
        }

        if (($parsed['scheme'] ?? null) !== 'ease') {
            throw new \InvalidArgumentException('Invalid URI scheme');
        }

        // Path may be in "opaque" (ease:...) or "path" (ease://...)
        $path = $parsed['opaque']
            ?? $parsed['host'].'/'.ltrim($parsed['path'] ?? '', '/');

        if ($path === '') {
            throw new \InvalidArgumentException('Missing object path');
        }

        $path = urldecode($path);

        // Validate Namespace/Class path
        if (!preg_match('~^[A-Za-z_][A-Za-z0-9_/]*$~', $path)) {
            throw new \InvalidArgumentException('Invalid object path format');
        }

        // Identifier is stored in fragment (legacy format may put "id?query" in fragment)
        if (!isset($parsed['fragment']) || $parsed['fragment'] === '') {
            throw new \InvalidArgumentException('Missing record identifier');
        }

        $fragment = $parsed['fragment'];
        $queryFromFragment = '';

        if (str_contains($fragment, '?')) {
            [$identifierPart, $queryFromFragment] = explode('?', $fragment, 2);
            $fragment = $identifierPart;
        }

        $identifier = urldecode($fragment);

        // Allow numeric IDs, names, UUIDs, etc.
        if (!preg_match('~^[A-Za-z0-9._:-]+$~', $identifier)) {
            throw new \InvalidArgumentException('Invalid record identifier format');
        }

        // Parse query parameters (from query component and/or legacy fragment)
        $args = [];

        if ($queryFromFragment !== '') {
            parse_str($queryFromFragment, $args);
        }

        if (!empty($parsed['query'])) {
            $queryArgs = [];
            parse_str($parsed['query'], $queryArgs);
            $args = array_merge($args, $queryArgs);
        }

        // Convert path to fully-qualified PHP class name
        $class = str_replace('/', '\\', $path);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class does not exist: {$class}");
        }

        return [
            'class' => $class,
            'id' => $identifier, // always string
            'args' => $args,
        ];
    }

    /**
     * Create EaseURI from an Ease object.
     *
     * @param object               $object Object exposing getMyKey()
     * @param array<string, mixed> $args   Optional query parameters
     *
     * @throws \InvalidArgumentException
     *
     * @return string EaseURI
     */
    public static function fromObject(object $object, array $args = []): string
    {
        if (!method_exists($object, 'getMyKey')) {
            throw new \InvalidArgumentException('Object must implement getMyKey()');
        }

        $ref = new \ReflectionClass($object);

        $namespace = $ref->getNamespaceName();
        $class = $ref->getShortName();
        $id = (string) $object->getMyKey();

        if ($id === '') {
            throw new \InvalidArgumentException('Object identifier is empty');
        }

        $path = str_replace('\\', '/', $namespace);
        $path = $path ? $path.'/'.$class : $class;

        // Path unencoded (readable); query values URL-encoded
        $base = 'ease://'.$path;

        if ($args) {
            $base .= '?'.http_build_query($args, '', '&', \PHP_QUERY_RFC3986);
        }

        return $base.'#'.rawurlencode($id);
    }

    /**
     * Exctract Class part only.
     *
     * @return string Class
     */
    public static function getClass(string $uri)
    {
        $meta = self::validate($uri);

        return $meta['class'];
    }

    /**
     * Instantiate object from EaseURI.
     *
     * @throws \InvalidArgumentException
     */
    public static function toObject(string $uri): object
    {
        $meta = self::validate($uri);

        /**
         * Expected constructor signature:
         *   new ClassName(string $identifier, array $args = [])
         */
        $object = new $meta['class']($meta['id'], $meta['args']);

        if (method_exists($object, 'setMyKey')) {
            $object->setMyKey($meta['id']);
        }

        return $object;
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

    public static function normalize(string $uri): string
    {
        $meta = self::validate($uri);

        $args = $meta['args'];
        ksort($args);

        return self::build(
            $meta['class'],
            $meta['id'],
            $args,
        );
    }

    public static function build(
        string $class,
        string $id,
        array $args = [],
    ): string {
        $base = 'ease://'.str_replace('\\', '/', $class);

        if ($args) {
            $base .= '?'.http_build_query($args, '', '&', \PHP_QUERY_RFC3986);
        }

        return $base.'#'.rawurlencode($id);
    }
}
