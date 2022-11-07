<?php

declare(strict_types=1);

/**
 * Misc functions holder 
 * 
 * @category Common
 * 
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019-2022 Vitex@hippy.cz (G)
 * @license https://opensource.org/licenses/MIT GPL-2
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Description of Functions
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Functions {

    /**
     * Returns PATH modified for current operating system.
     *
     * @param string $path
     *
     * @return string
     */
    public static function sysFilename($path) {
        return str_replace(['\\', '/'], constant('DIRECTORY_SEPARATOR'), $path);
    }

    /**
     * Add params to url
     *
     * @param string  $url      originall url
     * @param array   $addParams   value to add
     * @param boolean $override replace already existing values ?
     * 
     * @return string url with parameters added
     */
    public static function addUrlParams($url, array $addParams,
            $override = false) {
        $urlParts = parse_url($url);
        $urlFinal = '';
        if (array_key_exists('scheme', $urlParts)) {
            $urlFinal .= $urlParts['scheme'] . '://' . $urlParts['host'];
        }
        if (array_key_exists('port', $urlParts)) {
            $urlFinal .= ':' . $urlParts['port'];
        }
        if (array_key_exists('path', $urlParts)) {
            $urlFinal .= $urlParts['path'];
        }
        if (array_key_exists('query', $urlParts)) {
            parse_str($urlParts['query'], $queryUrlParams);
            $urlParams = $override ? array_merge($queryUrlParams, $addParams) : array_merge($addParams,
                            $queryUrlParams);
        } else {
            $urlParams = $addParams;
        }

        if (!empty($urlParams)) {
            $urlFinal .= '?' . http_build_query($urlParams);
        }
        return $urlFinal;
    }

    /**
     * Turn all URLs in clickable links.
     * 
     * @author Arnold Daniels <arnold@jasny.net>
     * 
     * @param string $value
     * @param array  $protocols  http/https, ftp, mail, twitter
     * @param array  $attributes
     * @param string $mode       normal or all
     * 
     * @return string
     */
    public static function linkify($value, $protocols = array('http', 'mail'),
            array $attributes = array()) {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr = ' ' . strval($key) . '="' . htmlentities(strval($val)) . '"';
        }

        $links = array();

        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i',
                function ($match) use (&$links) {
                    return '<' . array_push($links, $match[1]) . '>';
                }, $value);

        // Extract text links for each protocol
        foreach ((array) $protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https': $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i',
                            function ($match) use ($protocol, &$links, $attr) {
                                if ($match[1])
                                    $protocol = $match[1];
                                $link = $match[2] ?: $match[3];
                                return '<' . array_push($links,
                                        "<a $attr href=\"$protocol://$link\">$link</a>") . '>';
                            }, $value);
                    break;
                case 'mail': $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~',
                            function ($match) use (&$links, $attr) {
                                return '<' . array_push($links,
                                        "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>';
                            }, $value);
                    break;
                default: $value = preg_replace_callback('~' . preg_quote($protocol,
                                    '~') . '://([^\s<]+?)(?<![\.,:])~i',
                            function ($match) use ($protocol, &$links, $attr) {
                                return '<' . array_push($links,
                                        "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>';
                            }, $value);
                    break;
            }
        }

        // Insert all link
        return preg_replace_callback('/<(\d+)>/',
                function ($match) use (&$links) {
                    return $links[$match[1] - 1];
                }, $value);
    }

    /**
     * Move data field $columName from $sourceArray to $destinationArray.
     *
     * @param array  $sourceArray      source
     * @param array  $destinationArray destination
     * @param string $columName        item to move
     */
    public static function divDataArray(&$sourceArray, &$destinationArray,
            $columName) {
        $result = false;
        if (array_key_exists($columName, $sourceArray)) {
            $destinationArray[$columName] = $sourceArray[$columName];
            unset($sourceArray[$columName]);

            $result = true;
        }

        return $result;
    }

    /**
     * Test for associative array.
     *
     * @param array $arr
     *
     * @return bool
     */
    public static function isAssoc(array $arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Odstraní z textu diakritiku.
     *
     * @param string $text
     */
    public static function rip($text) {
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

        return iconv('UTF-8', 'ASCII//TRANSLIT', strtr($text, $convertTable));
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
    public static function easeEncrypt($textToEncrypt, $encryptKey) {
        $encryptionKey = base64_decode($encryptKey);
        $ivec = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($textToEncrypt, 'aes-256-cbc', $encryptionKey, 0, $ivec);
        return base64_encode($encrypted . '::' . $ivec);
    }

    /**
     * Decryptor
     *
     * @param string $textToDecrypt šifrovaný text
     * @param string $encryptKey    šifrovací klíč
     *
     * @return string
     */
    public static function easeDecrypt($textToDecrypt, $encryptKey) {
        $encryptionKey = base64_decode($encryptKey);
        list($encryptedData, $ivec ) = explode('::', base64_decode($textToDecrypt), 2);
        return openssl_decrypt($encryptedData, 'aes-256-cbc', $encryptionKey, 0, $ivec);
    }

    /**
     * Random number generator
     *
     * @param int $minimal
     * @param int $maximal
     *
     * @return float
     */
    public static function randomNumber($minimal = null, $maximal = null) {
        if (isset($minimal) && isset($maximal)) {
            if ($minimal >= $maximal) {
                throw new Exception('Minimum cannot be bigger than maximum');
            } else {
                $rand = mt_rand($minimal, $maximal);
            }
        } else {
            $rand = mt_rand();
        }

        return $rand;
    }

    /**
     * Vrací náhodný řetězec dané délky.
     *
     * @param int $length
     *
     * @return string
     */
    public static function randomString($length = 6) {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
                0, $length);
    }

    /**
     * Array content recusrsive recode
     *
     * @param string        $inCharset
     * @param string        $outCharset
     * @param array|string  $arr         original array
     *
     * @return array array recoded
     */
    public static function recursiveIconv(string $inCharset, string $outCharset, $arr) {
        if (!is_array($arr)) {
            return \iconv($inCharset, $outCharset, $arr);
        }
        $ret = $arr;
        array_walk_recursive($ret, '\Ease\Functions::arrayIconv',
                [$inCharset, $outCharset, $arr]);

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
    public static function arrayIconv(&$val, /** @scrutinizer ignore-unused */
            $key, $encodings) {
        $val = iconv($encodings[0], $encodings[1], $val);
    }

    /**
     * Zobrazí velikost souboru v srozumitelném tvaru.
     *
     * @param int $filesize bytů
     *
     * @return string
     */
    public static function humanFilesize(int $filesize) {
        $decr = 1024;
        $step = 0;
        $prefix = ['Byte', 'KB', 'MB', 'GB', 'TB', 'PB'];

        while (($filesize / $decr) > 0.9) {
            $filesize = $filesize / $decr;
            ++$step;
        }

        return round($filesize, 2) . ' ' . $prefix[$step];
    }

    /**
     * Reindex Array by given key.
     *
     * @param array  $data    array to reindex
     * @param string $indexBy one of columns in array
     *
     * @return array
     */
    public static function reindexArrayBy(array $data, string $indexBy = null) {
        $reindexedData = [];

        foreach ($data as $dataRow) {
            if (array_key_exists($indexBy, $dataRow)) {
                $reindexedData[(string) $dataRow[$indexBy]] = $dataRow;
            } else {
                throw new \Exception(sprintf('Data row does not contain column %s for reindexing',
                                        $indexBy));
            }
        }

        return $reindexedData;
    }

    /**
     * Filter Only letters from string.
     * Pouze malé a velké písmena.
     *
     * @return string text bez zvláštních znaků
     */
    public static function lettersOnly($text) {
        return preg_replace('/[^(a-zA-Z0-9)]*/', '', $text);
    }

    /**
     * Confirm that string is serialized
     * 
     * @param string $data
     *
     * @return boolean
     */
    public static function isSerialized(string $data) {
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (!\preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Get Classname without namespace prefix
     * 
     * @param object $object
     * 
     * @return string
     */
    static public function baseClassName($object) {
        return is_object($object) ? basename(str_replace('\\', '/',
                                get_class($object))) : null;
    }

    /**
     * Human readable bytes repersentation
     * 
     * @param int|double $bytes
     * 
     * @return string
     */
    public static function formatBytes($bytes) {
        $bytes = doubleval($bytes);

        if ($bytes < 1024) {
            $humanReadable = $bytes . ' B';
        } elseif ($bytes < 1048576) {
            $humanReadable = round($bytes / 1024, 2) . ' KiB';
        } elseif ($bytes < 1073741824) {
            $humanReadable = round($bytes / 1048576, 2) . ' MiB';
        } elseif ($bytes < 1099511627776) {
            $humanReadable = round($bytes / 1073741824, 2) . ' GiB';
        } elseif ($bytes < 1125899906842624) {
            $humanReadable = round($bytes / 1099511627776, 2) . ' TiB';
        } elseif ($bytes < 1152921504606846976) {
            $humanReadable = round($bytes / 1125899906842624, 2) . ' PiB';
        } elseif ($bytes < 1180591620717411303424) {
            $humanReadable = round($bytes / 1152921504606846976, 2) . ' EiB';
        } elseif ($bytes < 1208925819614629174706176) {
            $humanReadable = round($bytes / 1180591620717411303424, 2) . ' ZiB';
        } else {
            $humanReadable = round($bytes / 1208925819614629174706176, 2) . ' YiB';
        }
        return $humanReadable;
    }

    /**
     * Get configuration from constant or environment
     * 
     * @param string $constant
     * @param mixed $cfg Default value
     * 
     * @return string
     */
    public static function cfg($constant, $cfg = null) {
        if (!empty($constant) && defined($constant)) {
            $cfg = constant($constant);
        } elseif (isset($_ENV) && array_key_exists($constant, $_ENV)) {
            $cfg = $_ENV[$constant];
        } elseif (($env = getenv($constant)) && !empty($env)) {
            $cfg = getenv($constant);
        }
        return $cfg;
    }

    /**
     * Get All Classes in namespace
     * 
     * @param string $namespace
     * 
     * @return array<string>
     */
    public static function classesInNamespace($namespace) {
        $namespace .= '\\';

        $myClasses = array_filter(get_declared_classes(), function ($item) use ($namespace) {
            return substr($item, 0, strlen($namespace)) === $namespace;
        });
        $theClasses = [];
        foreach ($myClasses AS $class):
            $theParts = explode('\\', $class);
            $theClasses[] = end($theParts);
        endforeach;
        return $theClasses;
    }

}
