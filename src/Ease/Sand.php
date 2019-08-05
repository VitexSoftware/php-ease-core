<?php
/**
 * Zakladni objekt urceny k rodicovstvi pokročilým objektum.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2019 Vitex@hippy.cz (G)
 */

namespace Ease;

/**
 * Zakladni objekt urceny k rodicovstvi vsem pouzivanym objektum.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2012 Vitex@hippy.cz (G)
 */
class Sand extends Molecule
{
    /**
     * Default Language Code.
     *
     * @var string
     */
    public $langCode = null;

    /**
     * Common object data holder.
     *
     * @var array
     */
    public $data = null;

//    /**
//     * Obsahuje všechna pole souhrně považovaná za identitu. Toto pole je plněno
//     * v metodě SaveObjectIdentity {volá se automaticky v EaseSand::__construct()}.
//     *
//     * @var array
//     */
//    public $identity = [];
//
//    /**
//     * Původní identita sloužící jako záloha k zrekonstruování počátečního stavu objektu.
//     *
//     * @var array
//     */
//    public $initialIdentity = [];
//
//    /**
//     * Tyto sloupecky jsou uchovavany pri operacich s identitou objektu.
//     * 
//     * @deprecated since version 2.0
//     *
//     * @var array
//     */
//    public $identityColumns = ['ObjectName',
//        'keyColumn',
//        'myTable',
//        'MyIDSColumn',
//        'MyRefIDColumn',
//        'myCreateColumn',
//        'myLastModifiedColumn',];
//    /**
//     * Synchronizační sloupeček. napr products_ids.
//     *
//     * @var string
//     */
//    public $myIDSColumn = null;
//
//    /**
//     * Sloupeček obsahující datum vložení záznamu do shopu.
//     *
//     * @var string
//     */
//    public $myCreateColumn = null;
//
//    /**
//     * Slopecek obsahujici datum poslení modifikace záznamu do shopu.
//     *
//     * @var string
//     */
//    public $myLastModifiedColumn = null;

    /**
     * Objekt pro logování.
     *
     * @var Logger\Regent
     */
    public $logger = null;

    /**
     * Odkaz na vlastnící objekt.
     *
     * @var Sand|mixed object
     */
    public $parentObject = null;

    /**
     * Sdílený objekt frameworku.
     *
     * @var Shared
     */
    public $easeShared = null;

//    /**
//     * Prapředek všech objektů.
//     */
//    public function __construct()
//    {
//        parent::__construct();
//        $this->initialIdentity = $this->saveObjectIdentity();
//    }

    /**
     * Přidá zprávu do sdíleného zásobníku p$this->getLogger()ro zobrazení uživateli.
     *
     * @param string $message  Text zprávy
     * @param string $type     Fronta zpráv (warning|info|error|success)
     *
     * @return 
     */
    public function addStatusMessage($message, $type = 'info')
    {
        return Shared::singleton()->takeMessage(new Logger\Message($message,
                    $type, get_class($this)));
    }
    
    /**
     * Předá zprávy.
     *
     * @return array
     */
    public function getStatusMessages()
    {
        return Shared::singleton()->getStatusMessages();
    }

    /**
     * Vymaže zprávy.
     */
    public function cleanMessages()
    {
        return Shared::singleton()->cleanMessages();
    }
//    /**
//     * Nastaví novou identitu objektu.
//     *
//     * @deprecated since version 0.1
//     * 
//     * @param array $newIdentity
//     */
//    public function setObjectIdentity($newIdentity)
//    {
//        $changes = 0;
//        $this->saveObjectIdentity();
//        foreach ($this->identityColumns as $column) {
//            if (isset($newIdentity[$column])) {
//                $this->$column = $newIdentity[$column];
//                ++$changes;
//            }
//        }
//
//        return $changes;
//    }
//
//    /**
//     * Uloží identitu objektu do pole $this->identity.
//     *
//     * @deprecated since version 0.1
//     * 
//     * @return array pole s identitou
//     */
//    public function saveObjectIdentity()
//    {
//        foreach ($this->identityColumns as $column) {
//            if (isset($this->$column)) {
//                $this->identity[$column] = $this->$column;
//            }
//        }
//
//        return $this->identity;
//    }
//
//    /**
//     * Obnoví uloženou identitu objektu.
//     * 
//     * @deprecated since version 0.1
//     *
//     * @param array $identity pole s identitou např. array('myTable'=>'user');
//     */
//    public function restoreObjectIdentity($identity = null)
//    {
//        if (is_null($identity)) {
//            foreach ($this->identityColumns as $column) {
//                if (isset($this->identity[$column])) {
//                    $this->$column = $this->identity[$column];
//                }
//            }
//        } else {
//            foreach ($identity as $column) {
//                if (isset($this->identity[$column])) {
//                    $this->$column = $this->identity[$column];
//                }
//            }
//        }
//    }
//
//    /**
//     * Obnoví poslední použitou identitu.
//     * 
//     * @deprecated since version 0.1
//     */
//    public function resetObjectIdentity()
//    {
//        $this->identity = $this->initialIdentity;
//        $this->restoreObjectIdentity();
//    }

    /**
     * Vynuluje všechny pole vlastností objektu.
     */
    public function dataReset()
    {
        $this->data = [];
    }

    /**
     * Načte $data do polí objektu.
     *
     * @param array $data  asociativní pole dat
     * @param bool  $reset vyprazdnit pole před naplněním ?
     *
     * @return int počet načtených položek
     */
    public function setData($data, $reset = false)
    {
        $ret = null;
        if (!empty($data) && is_array($data) && count($data)) {
            if ($reset) {
                $this->dataReset();
            }
            if (is_array($this->data)) {
                $this->data = array_merge($this->data, $data);
            } else {
                $this->data = $data;
            }
            $ret = count($data);
        }

        return $ret;
    }

    /**
     * Obtain data holded by object
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Vrací počet položek dat objektu.
     *
     * @return int
     */
    public function getDataCount()
    {
        return count($this->data);
    }

    /**
     * Vrací hodnotu z pole dat pro MySQL.
     *
     * @param string $columnName název hodnoty/sloupečku
     *
     * @return mixed
     */
    public function getDataValue($columnName)
    {
        if (isset($this->data[$columnName])) {
            return $this->data[$columnName];
        }

        return;
    }

    /**
     * Set data filed value
     *
     * @param string $columnName název datové kolonky
     * @param mixed  $value      hodnota dat
     *
     * @return bool Success
     */
    public function setDataValue($columnName, $value)
    {
        $this->data[$columnName] = $value;

        return true;
    }

    /**
     * Odstrani polozku z pole dat pro MySQL.
     *
     * @param string $columnName název klíče k vymazání
     *
     * @return bool success
     */
    public function unsetDataValue($columnName)
    {
        if (array_key_exists($columnName, $this->data)) {
            unset($this->data[$columnName]);

            return true;
        }

        return false;
    }

    /**
     * Převezme data do aktuálního pole dat.
     *
     * @param array $data asociativní pole dat
     *
     * @return int
     */
    public function takeData($data)
    {
        if (is_array($this->data) && is_array($data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }

        return empty($data) ? null : count($data);
    }


}
