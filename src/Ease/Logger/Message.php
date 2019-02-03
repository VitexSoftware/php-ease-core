<?php
/**
 * Message Classs
 * 
 * @category Logging
 * 
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2019 Vitex@hippy.cz (G)
 * @license https://opensource.org/licenses/MIT MIT
 * 
 * PHP 7
 */
namespace Ease\Logger;

/**
 * Description of Message
 *
 * @author vitex
 */
class Message
{
    /**
     * Message body
     * @var string
     */
    public $body;

    /**
     * Message type
     * @var string info|succes|warning|danger|mail
     */
    public $type;

    /**
     * 
     * @var \Ease\Atom
     */
    public $caller;

    /**
     * Message Time
     * @var string
     */
    public $when;

    /**
     * @param string $caller
     */
    public function __construct($message, $type = 'info', $caller = null,
                                $when = null)
    {
        $this->body   = $message;
        $this->type   = $type;
        $this->caller = $caller;
        if (is_null($when)) {
            $this->when = time();
        } else {
            $this->when = $when;
        }
    }

    /**
     * Unicode Symbol for given message type
     *
     * @param string $type
     * @return string
     */
    public static function getTypeUnicodeSymbol($type)
    {
        switch ($type) {
            case 'mail':                       // Envelope
                $symbol = '✉';
                break;
            case 'warning':                    // Vykřičník v trojůhelníku
                $symbol = '⚠';
                break;
            case 'error':                      // Lebka
                $symbol = '☠';
                break;
            case 'success':                    // Kytička
                $symbol = '❁';
                break;
            case 'debug':                      // Gear
                $symbol = '⚙';
                break;
            default:                           // i v kroužku
                $symbol = 'ⓘ';
                break;
        }
        return $symbol;
    }
}
