<?php

/**
 * Misc functions holder.
 *
 * @category Common
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019-2024 Vitex@hippy.cz (G)
 * @license https://opensource.org/licenses/MIT GPL-2
 *
 * PHP 7
 * PHP 8
 */

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
 * Description of Functions.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Functions
{
    /**
     * Returns PATH modified for current operating system.
     *
     * @param string $path
     *
     * @return string
     */
    public static function sysFilename($path)
    {
        return str_replace(['\\', '/'], \constant('DIRECTORY_SEPARATOR'), $path);
    }

    /**
     * Add params to url.
     *
     * @param string                $url       originall url
     * @param array<string, string> $addParams value to add
     * @param bool                  $override  replace already existing values ?
     *
     * @return string url with parameters added
     */
    public static function addUrlParams($url, array $addParams, $override = false)
    {
        $urlParts = parse_url($url);
        $urlFinal = '';

        if (\array_key_exists('scheme', $urlParts)) {
            $urlFinal .= $urlParts['scheme'].'://'.$urlParts['host'];
        }

        if (\array_key_exists('port', $urlParts)) {
            $urlFinal .= ':'.$urlParts['port'];
        }

        if (\array_key_exists('path', $urlParts)) {
            $urlFinal .= $urlParts['path'];
        }

        if (\array_key_exists('query', $urlParts)) {
            parse_str($urlParts['query'], $queryUrlParams);
            $urlParams = $override ? array_merge($queryUrlParams, $addParams) :
                    array_merge($addParams, $queryUrlParams);
        } else {
            $urlParams = $addParams;
        }

        if ($urlParams !== []) {
            $urlFinal .= '?'.http_build_query($urlParams);
        }

        return $urlFinal;
    }

    /**
     * Move data field $columName from $sourceArray to $destinationArray.
     *
     * @param array<mixed> $sourceArray      source
     * @param array<mixed> $destinationArray destination
     * @param int|string   $columName        item to move
     */
    public static function divDataArray(array &$sourceArray, array &$destinationArray, $columName): bool
    {
        $result = false;

        if (\array_key_exists($columName, $sourceArray)) {
            $destinationArray[$columName] = $sourceArray[$columName];
            unset($sourceArray[$columName]);

            $result = true;
        }

        return $result;
    }

    /**
     * Test for associative array.
     *
     * @param array<mixed> $arr
     */
    public static function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, \count($arr) - 1);
    }

    /**
     * Odstraní z textu diakritiku.
     */
    public static function rip(string $text): string
    {
        $convertTable = [
            'ä' => 'a',
            'Ä' => 'A',
            'á' => 'a',
            'Á' => 'A',
            'à' => 'a',
            'À' => 'A',
            'ã' => 'a',
            'Ã' => 'A',
            'â' => 'a',
            'Â' => 'A',
            'č' => 'c',
            'Č' => 'C',
            'ć' => 'c',
            'Ć' => 'C',
            'ď' => 'd',
            'Ď' => 'D',
            'ě' => 'e',
            'Ě' => 'E',
            'é' => 'e',
            'É' => 'E',
            'ë' => 'e',
            'Ë' => 'E',
            'è' => 'e',
            'È' => 'E',
            'ê' => 'e',
            'Ê' => 'E',
            'í' => 'i',
            'Í' => 'I',
            'ï' => 'i',
            'Ï' => 'I',
            'ì' => 'i',
            'Ì' => 'I',
            'î' => 'i',
            'Î' => 'I',
            'ľ' => 'l',
            'Ľ' => 'L',
            'ĺ' => 'l',
            'Ĺ' => 'L',
            'ń' => 'n',
            'Ń' => 'N',
            'ň' => 'n',
            'Ň' => 'N',
            'ñ' => 'n',
            'Ñ' => 'N',
            'ó' => 'o',
            'Ó' => 'O',
            'ö' => 'o',
            'Ö' => 'O',
            'ô' => 'o',
            'Ô' => 'O',
            'ò' => 'o',
            'Ò' => 'O',
            'õ' => 'o',
            'Õ' => 'O',
            'ő' => 'o',
            'Ő' => 'O',
            'ř' => 'r',
            'Ř' => 'R',
            'ŕ' => 'r',
            'Ŕ' => 'R',
            'š' => 's',
            'Š' => 'S',
            'ś' => 's',
            'Ś' => 'S',
            'ť' => 't',
            'Ť' => 'T',
            'ú' => 'u',
            'Ú' => 'U',
            'ů' => 'u',
            'Ů' => 'U',
            'ü' => 'u',
            'Ü' => 'U',
            'ù' => 'u',
            'Ù' => 'U',
            'ũ' => 'u',
            'Ũ' => 'U',
            'û' => 'u',
            'Û' => 'U',
            'ý' => 'y',
            'Ý' => 'Y',
            'ž' => 'z',
            'Ž' => 'Z',
            'ź' => 'z',
            'Ź' => 'Z',
        ];

        return (string) iconv('UTF-8', 'ASCII//TRANSLIT', strtr($text, $convertTable));
    }

    /**
     * Encrypt.
     * Šifrování.
     *
     * @param string $textToEncrypt plaintext
     * @param string $encryptKey    klíč
     *
     * @return string encrypted text
     */
    public static function easeEncrypt($textToEncrypt, $encryptKey)
    {
        $encryptionKey = base64_decode($encryptKey, true);
        $ivec = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($textToEncrypt, 'aes-256-cbc', $encryptionKey, 0, $ivec);

        return base64_encode($encrypted.'::'.$ivec);
    }

    /**
     * Decryptor.
     *
     * @param string $textToDecrypt šifrovaný text
     * @param string $encryptKey    šifrovací klíč
     *
     * @return string
     */
    public static function easeDecrypt($textToDecrypt, $encryptKey)
    {
        $encryptionKey = base64_decode($encryptKey, true);
        [$encryptedData, $ivec] = explode('::', base64_decode($textToDecrypt, true), 2);

        return openssl_decrypt($encryptedData, 'aes-256-cbc', $encryptionKey, 0, $ivec);
    }

    /**
     * Random number generator.
     *
     * @param int $minimal
     * @param int $maximal
     *
     * @return float
     */
    public static function randomNumber($minimal = null, $maximal = null)
    {
        if (isset($minimal, $maximal)) {
            if ($minimal >= $maximal) {
                throw new Exception('Minimum cannot be bigger than maximum');
            }

            $rand = mt_rand($minimal, $maximal);
        } else {
            $rand = mt_rand();
        }

        return $rand;
    }

    /**
     * Vrací náhodný řetězec dané délky.
     */
    public static function randomString(int $length = 6): string
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }

    /**
     * Array content recusrsive recode.
     *
     * @param array<mixed>|string $arr original array
     *
     * @return array<mixed> array recoded
     */
    public static function recursiveIconv(string $inCharset, string $outCharset, $arr)
    {
        if (!\is_array($arr)) {
            return \iconv($inCharset, $outCharset, $arr);
        }

        $ret = $arr;
        array_walk_recursive($ret, '\Ease\Functions::arrayIconv', [$inCharset, $outCharset, $arr]);

        return $ret;
    }

    /**
     * Pomocná funkce pro překódování vícerozměrného pole.
     *
     * @see recursiveIconv
     *
     * @param mixed  $val
     * @param string $key
     * @param mixed  $encodings
     */
    public static function arrayIconv(&$val, /** @scrutinizer ignore-unused */ $key, $encodings): void
    {
        $val = iconv($encodings[0], $encodings[1], $val);
    }

    /**
     * Zobrazí velikost souboru v srozumitelném tvaru.
     *
     * @param int $filesize bytů
     *
     * @return string
     */
    public static function humanFilesize(int $filesize)
    {
        $decr = 1024;
        $step = 0;
        $prefix = ['Byte', 'KB', 'MB', 'GB', 'TB', 'PB'];

        while (($filesize / $decr) > 0.9) {
            $filesize /= $decr;
            ++$step;
        }

        return round($filesize, 2).' '.$prefix[$step];
    }

    /**
     * Reindex Array by given key.
     *
     * @param array<mixed> $data    array to reindex
     * @param string       $indexBy one of columns in array
     *
     * @return array<mixed> reindexed array
     */
    public static function reindexArrayBy(array $data, string $indexBy)
    {
        $reindexedData = [];

        foreach ($data as $dataRow) {
            if (\array_key_exists($indexBy, $dataRow)) {
                $reindexedData[(string) $dataRow[$indexBy]] = $dataRow;
            } else {
                throw new \Exception(sprintf('Data row does not contain column %s for reindexing', $indexBy));
            }
        }

        return $reindexedData;
    }

    /**
     * Filter Only letters from string.
     *
     * @param mixed $text
     *
     * @return string text bez zvláštních znaků
     */
    public static function lettersOnly($text): string
    {
        return preg_replace('/[^(a-zA-Z0-9)]*/', '', (string) $text);
    }

    /**
     * Confirm that string is serialized.
     */
    public static function isSerialized(string $data): bool
    {
        $data = trim($data);

        if ('N;' === $data) {
            return true;
        }

        if (!\preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }

        switch ($badions[1]) {
            case 'a':
            case 'O':
            case 's':
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }

                break;
            case 'b':
            case 'i':
            case 'd':
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }

                break;
        }

        return false;
    }

    /**
     * Get Classname without namespace prefix.
     *
     * @param object $object
     *
     * @return null|object|string
     */
    public static function baseClassName($object)
    {
        return \is_object($object) ? basename(str_replace('\\', '/', $object::class)) : null;
    }

    /**
     * Human readable bytes repersentation.
     *
     * @param float|int $dbytes
     *
     * @return string
     */
    public static function formatBytes($dbytes)
    {
        $bytes = (float) $dbytes;

        if ($bytes < 1024) {
            $humanReadable = $bytes.' B';
        } elseif ($bytes < 1048576) {
            $humanReadable = round($bytes / 1024, 2).' KiB';
        } elseif ($bytes < 1073741824) {
            $humanReadable = round($bytes / 1048576, 2).' MiB';
        } elseif ($bytes < 1099511627776) {
            $humanReadable = round($bytes / 1073741824, 2).' GiB';
        } elseif ($bytes < 1125899906842624) {
            $humanReadable = round($bytes / 1099511627776, 2).' TiB';
        } elseif ($bytes < 1152921504606846976) {
            $humanReadable = round($bytes / 1125899906842624, 2).' PiB';
        } elseif ($bytes < 1180591620717411303424) {
            $humanReadable = round($bytes / 1152921504606846976, 2).' EiB';
        } elseif ($bytes < 1208925819614629174706176) {
            $humanReadable = round($bytes / 1180591620717411303424, 2).' ZiB';
        } else {
            $humanReadable = round($bytes / 1208925819614629174706176, 2).' YiB';
        }

        return $humanReadable;
    }

    /**
     * Get All Classes in namespace.
     *
     * @param string $namespace
     *
     * @return array<string>
     */
    public static function classesInNamespace($namespace)
    {
        $namespace .= '\\';

        $myClasses = array_filter(get_declared_classes(), static function ($item) use ($namespace) {
            return substr($item, 0, \strlen($namespace)) === $namespace;
        });
        $theClasses = [];

        foreach ($myClasses as $class) {
            $theParts = explode('\\', $class);
            $theClasses[] = end($theParts);
        }

        return $theClasses;
    }

    /**
     * Load all files found for given namespace
     * (based on composer files).
     *
     * @return array<string, string> of loaded files className=>filePath
     */
    public static function loadClassesInNamespace(string $namespace): array
    {
        $loaded = [];
        $autoloader = preg_grep('/autoload\.php$/', get_included_files());

        $psr4load = \dirname(current($autoloader)).'/composer/autoload_psr4.php';

        // First, check for already loaded classes in this namespace to include them in the result
        $alreadyLoaded = get_declared_classes();
        $existingClasses = preg_grep('/^'.preg_quote($namespace, '/').'\\\w+$/', $alreadyLoaded);
        
        if ($autoloader !== [] && $autoloader !== false && file_exists($psr4load)) {
            $psr4dirs = include $psr4load;

            if (\array_key_exists($namespace.'\\', $psr4dirs)) {
                foreach ($psr4dirs[$namespace.'\\'] as $modulePath) {
                    $d = dir($modulePath);
                    $processedFiles = [];

                    while (false !== ($entry = $d->read())) {
                        if (is_file($modulePath.'/'.$entry) && (pathinfo($entry, \PATHINFO_EXTENSION) === 'php')) {
                            $filePath = $modulePath.'/'.$entry;
                            // Record that we've processed this file to avoid duplicates
                            if (isset($processedFiles[$filePath])) {
                                continue;
                            }
                            $processedFiles[$filePath] = true;
                            
                            // Extract potential class name from the file name
                            $className = $namespace.'\\'.pathinfo($entry, \PATHINFO_FILENAME);

                            // If class already exists, add it to loaded without re-including
                            if (class_exists($className, false) || in_array($className, $existingClasses)) {
                                $loaded[$className] = $filePath;
                                continue;
                            }

                            $classesBefore = get_declared_classes();

                            include_once $filePath;
                            $diff = array_diff(get_declared_classes(), $classesBefore);
                            $load = preg_grep('/'.addslashes($namespace).'/', $diff);

                            foreach ($load as $class) {
                                $loaded[$class] = $filePath;
                            }
                        }
                    }

                    $d->close();
                }
            }
        }

        return $loaded;
    }

    /**
     * Generates RFC 4122 compliant Version 4 UUIDs.
     *
     * @param string $data
     */
    public static function guidv4($data = null): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data ??= random_bytes(16);
        \assert(\strlen($data) === 16);

        // Set version to 0100
        $data[6] = \chr(\ord($data[6]) & 0x0F | 0x40);
        // Set bits 6-7 to 10
        $data[8] = \chr(\ord($data[8]) & 0x3F | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Write Result data to output file od stdout.
     *
     * @return false|int<0, max>
     */
    public static function writeResult(array $result, string $destination = 'php://stdout', ?\Ease\Sand $engine = null)
    {
        $written = file_put_contents($destination, json_encode($result, Shared::cfg('DEBUG') ? \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES : 0));

        if ($engine instanceof \Ease\Sand) {
            $engine->addStatusMessage(sprintf(_('Saving result to %s'), $destination), $written ? 'success' : 'error');
        }

        return $written;
    }

    /**
     * Check if a string is a valid UUID.
     */
    public static function isUuid(string $uuid): bool
    {
        return \preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $uuid) === 1;
    }

    /**
     * Validates a JSON string.
     *
     * @param string $json  the JSON string to validate
     * @param int    $depth Maximum depth. Must be greater than zero.
     * @param int    $flags bitmask of JSON decode options
     *
     * @return bool returns true if the string is a valid JSON, otherwise false
     */
    public static function isJson(?string $json, int $depth = 512, int $flags = 0): bool
    {
        $isJson = false;

        if (\function_exists('json_validate')) {
            $isJson = \json_validate($json);
        } else {
            if (!\is_string($json)) {
                $isJson = false;
            }

            try {
                json_decode($json, false, $depth, $flags | \JSON_THROW_ON_ERROR);
                $isJson = true;
            } catch (\JsonException $e) {
                $isJson = false;
            }
        }

        return $isJson;
    }

    /**
     * Convert a string to camelCase.
     *
     * @param string $string The string to convert
     */
    public static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_', ':'], ' ', $string))));
    }
}
