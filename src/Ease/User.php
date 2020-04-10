<?php

/**
 * Objekty uživatelů.
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2009-2020 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Třída uživatele.
 *
 * @author  Vítězslav Dvořák <vitex@hippy.cz>
 */
class User extends Anonym {

    /**
     * @var User Singleton is stored here
     */
    public static $instance;

    /**
     * ID prave nacteneho uzivatele.
     *
     * @var int unsigned
     */
    public $userID = null;

    /**
     * Přihlašovací jméno uživatele.
     *
     * @var string
     */
    public $userLogin = null;

    /**
     * Pole uživatelských nastavení.
     *
     * @var array
     */
    public $settings = [];

    /**
     * Sloupeček s loginem.
     *
     * @var string
     */
    public $loginColumn = 'login';

    /**
     * Sloupeček s heslem.
     *
     * @var string
     */
    public $passwordColumn = 'password';

    /**
     * Sloupecek pro docasne zablokovani uctu.
     *
     * @var string
     */
    public $disableColumn = null;

    /**
     * Column for user mail.
     *
     * @var string
     */
    public $mailColumn = 'email';

    /**
     * Sloupeček obsahující serializované rozšířené informace.
     *
     * @var string
     */
    public $settingsColumn = null;

    /**
     * Objekt uživatele aplikace.
     *
     * @param int $userID ID nebo Login uživatele jenž se má načíst při
     *                    inicializaci třídy
     */
    public function __construct($userID = null) {
        $this->userID = $userID;
        $this->setObjectName();
    }

    /**
     * Give you user name.
     *
     * @return string
     */
    public function getUserName() {
        return $this->getDataValue($this->loginColumn);
    }

    /**
     * Retrun user's mail address.
     *
     * @return string
     */
    public function getUserEmail() {
        return $this->getDataValue($this->mailColumn);
    }

    /**
     * Vykreslí GrAvatara uživatele.
     */
    public function draw() {
        echo '<img class="avatar" src="' . $this->getIcon() . '">';
    }

    /**
     * Vrací odkaz na url ikony.
     *
     * @return string url ikony
     */
    public function getIcon() {
        $email = $this->getUserEmail();
        if ($email) {
            return self::getGravatar($email, 800, 'mm', 'g');
        } else {
            return;
        }
    }

    /**
     * Pokusí se o přihlášení.
     * Try to Sign in.
     *
     * @param array $formData pole dat z přihlaš. formuláře např. $_REQUEST
     *
     * @return null|boolean
     */
    public function tryToLogin($formData) {
        if (!count($formData)) {
            return;
        }
        $login = $formData[$this->loginColumn];
        $password = $formData[$this->passwordColumn];
        if (empty($login)) {
            $this->addStatusMessage(_('missing login'), 'error');

            return;
        } else {
            $this->setDataValue($this->loginColumn, $login);
        }
        if (!$password) {
            $this->addStatusMessage(_('missing password'), 'error');

            return;
        } else {
            $this->setDataValue($this->passwordColumn, $password);
        }
        if ($this->authentize()) {
            return true;
        } else {
            $this->addStatusMessage(sprintf(_('user %s does not exist'), $login,
                            'error'));

            return false;
        }
    }

    /**
     * Try to authentize user 
     * 
     * @return boolean
     */
    public function authentize() {
        return false;
    }

    /**
     * Try to validate Password
     * 
     * @param string $password plaintext
     * 
     * @return boolean
     */
    public function validatePassword($password) {
        if ($this->passwordValidation($password,
                        $this->getDataValue($this->passwordColumn))) {
            if ($this->isAccountEnabled()) {
                return $this->loginSuccess();
            } else {
                $this->userID = null;

                return false;
            }
        } else {
            $this->userID = null;
            if (count($this->getData())) {
                $this->addStatusMessage(_('invalid password'), 'error');
            }
            $this->dataReset();

            return false;
        }
    }

    /**
     * Je učet povolen ?
     *
     * @return bool
     */
    public function isAccountEnabled() {
        if (is_null($this->disableColumn)) {
            return true;
        }
        if ($this->getDataValue($this->disableColumn)) {
            $this->addStatusMessage(_('Sign in denied by administrator'),
                    'warning');

            return false;
        }

        return true;
    }

    /**
     * Akce provedené po úspěšném přihlášení
     * pokud tam jeste neexistuje zaznam, vytvori se novy.
     * 
     * @return boolean
     */
    public function loginSuccess() {
        $this->userID = (int) $this->getMyKey();
        $this->setUserLogin($this->getDataValue($this->loginColumn));
        $this->logged = true;
        $this->addStatusMessage(sprintf(_('Signed in as %s'), $this->userLogin),
                'success');
        $this->setObjectName();
        \Ease\Shared::user($this);
        return true;
    }

    /**
     * Ověření hesla.
     *
     * @param string $plainPassword     heslo v nešifrované podobě
     * @param string $encryptedPassword šifrovné heslo
     *
     * @return bool
     */
    public static function passwordValidation($plainPassword, $encryptedPassword) {
        if ($plainPassword && $encryptedPassword) {
            $passwordStack = explode(':', $encryptedPassword);
            if (sizeof($passwordStack) != 2) {
                return false;
            }
            if (md5($passwordStack[1] . $plainPassword) == $passwordStack[0]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Zašifruje heslo.
     *
     * @param string $plainTextPassword nešifrované heslo (plaintext)
     *
     * @return string Encrypted password
     */
    public static function encryptPassword($plainTextPassword) {
        $encryptedPassword = '';
        for ($i = 0; $i < 10; ++$i) {
            $encryptedPassword .= \Ease\Functions::randomNumber();
        }
        $passwordSalt = substr(md5($encryptedPassword), 0, 2);
        $encryptedPassword = md5($passwordSalt . $plainTextPassword) . ':' . $passwordSalt;

        return $encryptedPassword;
    }

    /**
     * Změní uživateli uložené heslo.
     *
     * @param string $newPassword nové heslo
     *
     * @return boolean success
     */
    public function passwordChange(/** @scrutinizer ignore-unused */ $newPassword) {
        return false;
    }

    /**
     * Vraci ID přihlášeného uživatele.
     *
     * @return int ID uživatele
     */
    public function getUserID() {
        return isset($this->userID) ? (int) $this->userID : (int) $this->getMyKey();
    }

    /**
     * Vrací login uživatele.
     *
     * @return string
     */
    public function getUserLogin() {
        return isset($this->userLogin) ? $this->userLogin : $this->getDataValue($this->loginColumn);
    }

    /**
     * Nastavuje login uživatele.
     *
     * @return boolean
     */
    public function setUserLogin($login) {
        $this->userLogin = $login;
        return isset($this->loginColumn) ? $this->setDataValue($this->loginColumn,
                        $login) : true;
    }

    /**
     * Vrací hodnotu uživatelského oprávnění.
     *
     * @param string $permKeyword klíčové slovo oprávnění
     *
     * @return mixed
     */
    public function getPermission($permKeyword = null) {
        if (isset($this->permissions[$permKeyword])) {
            return $this->permissions[$permKeyword];
        } else {
            return;
        }
    }

    /**
     * Provede odhlášení uživatele.
     */
    public function logout() {
        $this->logged = false;
        $this->addStatusMessage(_('Sign Out successful'), 'success');

        return true;
    }

    /**
     * Vrací hodnotu nastavení.
     *
     * @param string $settingName jméno nastavení
     *
     * @return mixed
     */
    public function getSettingValue($settingName = null) {
        if (isset($this->settings[$settingName])) {
            return $this->settings[$settingName];
        } else {
            return;
        }
    }

    /**
     * Nastavuje nastavení.
     *
     * @param array $settings asociativní pole nastavení
     */
    public function setSettings($settings) {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Nastaví položku nastavení.
     *
     * @param string $settingName  klíčové slovo pro nastavení
     * @param mixed  $settingValue hodnota nastavení
     */
    public function setSettingValue($settingName, $settingValue) {
        $this->settings[$settingName] = $settingValue;
    }

    /**
     * Načte oprávnění.
     *
     * @return mixed
     */
    public function loadPermissions() {
        return;
    }

    /**
     * Vrací jméno objektu uživatele.
     *
     * @return string
     */
    public function getName() {
        return $this->getObjectName();
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email
     * address.
     *
     * @param string $email     The email address
     * @param integer $size      Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param string $default   [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $maxRating Maximum rating (inclusive) [ g | pg | r | x ]
     *
     * @return string containing either just a URL or a complete image tag
     *
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function getGravatar(
            $email, $size = 80, $default = 'mm', $maxRating = 'g'
    ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size&d=$default&r=$maxRating";

        return $url;
    }

    /**
     * Nastavení jména objektu uživatele.
     *
     * @param string $objectName vynucené jméno objektu
     *
     * @return string
     */
    public function setObjectName($objectName = null) {

        if (empty($objectName) && isset($_SERVER['REMOTE_ADDR'])) {
            $name = parent::setObjectName(get_class($this) . ':' . $this->getUserName() . '@' . self::remoteToIdentity());
        } else {
            $name = parent::setObjectName($objectName);
        }
        return $name;
    }

    /**
     * Common instance of User class
     * 
     * @return \Ease\User
     */
    public static function singleton($user = null) {
        if (!isset(self::$instance)) {
            self::$instance = is_null($user) ? new self() : $user;
        }
        return self::$instance;
    }

    /**
     * Pro serializaci připraví vše.
     *
     * @return array
     */
    public function __sleep() {
        return ['logged', 'data'];
    }

    public function __wakeup() {
        return;
    }

}
