<?php
/**
 * Základní pojící element všech objektů v EaseFrameWorku. Jeho hlavní schopnost je:
 * Pojímat do sebe zprávy.
 * 
 * @category Common
 * 
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 * @license   https://opensource.org/licenses/MIT GPL-2
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Basic Class of EasePHP0
 * 
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Atom
{
    /**
     * Version of EasePHP Framework
     *
     * @var string
     */
    public static $frameworkVersion = '0.5';

    /**
     * Flag debugovacího režimu.
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Pole informaci urcenych k logovani inebo zobrazovanych uzivateli.
     *
     * @var array
     */
    public $statusMessages = [];

    /**
     * Vrací jméno objektu.
     *
     * @return string
     */
    public function getObjectName()
    {
        return get_class();
    }

    /**
     * Add message to stack to show or write to file
     * Přidá zprávu do zásobníku pro zobrazení uživateli inbo do logu.
     *
     * @param string $message text zpravy
     * @param string $type    fronta
     * 
     * @return boolean message added
     */
    public function addStatusMessage($message, $type = 'info')
    {
        $this->statusMessages[$type][microtime()] = $message;
        return true;
    }

    /**
     * Přidá zprávy z pole uživateli do zásobníku.
     *
     * @param array $statusMessages pole zpráv
     *
     * @return int Počet zpráv přidaných do fronty
     */
    public function addStatusMessages($statusMessages)
    {
        if (is_array($statusMessages) && count($statusMessages)) {
            $allMessages = [];
            foreach ($statusMessages as $quee => $messages) {
                foreach ($messages as $mesgID => $message) {
                    $allMessages[$mesgID][$quee] = $message;
                }
            }
            foreach ($allMessages as $message) {
                $quee = key($message);
                $this->addStatusMessage(reset($message), $quee);
            }
            return count($statusMessages);
        }

        return;
    }

    /**
     * Vymaže zprávy.
     */
    public function cleanMessages()
    {
        $this->statusMessages = [];
    }

    /**
     * Obtain status messages
     *
     * @return array
     */
    public function getStatusMessages()
    {
            return $this->statusMessages;
    }

    /**
     * Add Info about used PHP and EasePHP Library
     *
     * @param string $prefix banner prefix text
     * @param string $suffix banner suffix text
     */
    public function logBanner($prefix = null, $suffix = null)
    {
        $this->addStatusMessage(
            trim($prefix.' PHP v'.phpversion().' EasePHP Framework v'.self::$frameworkVersion.' '.$suffix),
            'debug'
        );
    }

    /**
     * Magická funkce pro všechny potomky.
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    /**
     * Default Draw method.
     *
     * @return string
     */
    public function draw()
    {
        return method_exists($this, '__toString') ? $this->__toString() : null;
    }
}
