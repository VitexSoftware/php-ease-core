<?php

/**
 * User objects
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 *
 * PHP 7
 */

declare(strict_types=1);

namespace Ease;

/**
 * User Class
 *
 * @author  Vítězslav Dvořák <vitex@hippy.cz>
 */
class User extends Anonym
{
    /**
     * @var User|null Singleton is stored here
     */
    public static $instance;

    /**
     * ID prave nacteneho uzivatele.
     *
     * @var int|null unsigned
     */
    public $userID = null;

    /**
     * Přihlašovací jméno uživatele.
     *
     * @var string|null
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
     * @var string|null
     */
    public $loginColumn = 'login';

    /**
     * Password Column.
     *
     * @var string
     */
    public $passwordColumn = 'password';

    /**
     * Plaintext Form input name.
     *
     * @var string
     */
    public $plaintextField = 'password';

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
     * Store of user permissions
     * @var array
     */
    public $permissions = [];

    /**
     * Objekt uživatele aplikace.
     *
     * @param int $userID ID nebo Login uživatele jenž se má načíst při
     *                    inicializaci třídy
     */
    public function __construct($userID = null)
    {
        $this->userID = $userID;
        $this->setObjectName();
    }

    /**
     * Give you user name.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->getDataValue($this->loginColumn);
    }

    /**
     * Retrun user's mail address.
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->getDataValue($this->mailColumn);
    }

    /**
     * Vykreslí GrAvatara uživatele.
     */
    public function draw()
    {
        return '<img class="avatar" src="' . $this->getIcon() . '">';
    }

    /**
     * Vrací odkaz na url ikony.
     *
     * @return string url ikony
     */
    public function getIcon()
    {
        $email = $this->getUserEmail();
        if ($email) {
            return self::getGravatar($email, 800, 'mm', 'g');
        } else {
            return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZG' .
                    'luZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZC' .
                    'B3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tP' .
                    'go8c3ZnIGlkPSJzdmcyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvM' .
                    'jAwMC9zdmciIGhlaWdodD0iMjAwIiB3aWR0aD0iMjAwIiB2ZXJzaW9uPSIx' .
                    'LjAiPgogPHBhdGggaWQ9InBhdGgyMzgyIiBkPSJtMTY1LjMzIDExMy40' .
                    'NGExMDMuNjEgMTAzLjYxIDAgMSAxIC0yMDcuMjIgMCAxMDMuNjEgMTAzLjY' .
                    'xIDAgMSAxIDIwNy4yMiAweiIgdHJhbnNmb3JtPSJtYXRyaXgoLjkzNzM' .
                    '5IDAgMCAuOTM3MzkgNDIuMTQzIC02LjMzOTIpIiBzdHJva2Utd2lkdGg9Ij' .
                    'AiIGZpbGw9IiNmZmYiLz4KIDxnIGlkPSJsYXllcjEiPgogIDxwYXRoIG' .
                    'lkPSJwYXRoMjQxMyIgZD0ibTEwMCAwYy01NS4yIDAtMTAwIDQ0LjgtMTAwI' .
                    'DEwMC01LjA0OTVlLTE1IDU1LjIgNDQuOCAxMDAgMTAwIDEwMHMxMDAtN' .
                    'DQuOCAxMDAtMTAwLTQ0LjgtMTAwLTEwMC0xMDB6bTAgMTIuODEyYzQ4LjEz' .
                    'IDAgODcuMTkgMzkuMDU4IDg3LjE5IDg3LjE4OHMtMzkuMDYgODcuMTkt' .
                    'ODcuMTkgODcuMTktODcuMTg4LTM5LjA2LTg3LjE4OC04Ny4xOSAzOS4wNTg' .
                    'tODcuMTg4IDg3LjE4OC04Ny4xODh6bTEuNDcgMjEuMjVjLTUuNDUgMC4' .
                    'wMy0xMC42NTMgMC43MzctMTUuMjgyIDIuMDYzLTQuNjk5IDEuMzQ2LTkuMT' .
                    'I2IDMuNDg0LTEyLjg3NiA2LjIxOS0zLjIzOCAyLjM2Mi02LjMzMyA1Lj' .
                    'M5MS04LjY4NyA4LjUzMS00LjE1OSA1LjU0OS02LjQ2MSAxMS42NTEtNy4wN' .
                    'jMgMTguNjg3LTAuMDQgMC40NjgtMC4wNyAwLjg2OC0wLjA2MiAwLjg3N' .
                    'iAwLjAxNiAwLjAxNiAyMS43MDIgMi42ODcgMjEuODEyIDIuNjg3IDAuMDUz' .
                    'IDAgMC4xMTMtMC4yMzQgMC4yODItMC45MzcgMS45NDEtOC4wODUgNS40' .
                    'ODYtMTMuNTIxIDEwLjk2OC0xNi44MTMgNC4zMi0yLjU5NCA5LjgwOC0zLjY' .
                    'xMiAxNS43NzgtMi45NjkgMi43NCAwLjI5NSA1LjIxIDAuOTYgNy4zOCA' .
                    'yIDIuNzEgMS4zMDEgNS4xOCAzLjM2MSA2Ljk0IDUuODEzIDEuNTQgMi4xNT' .
                    'YgMi40NiA0LjU4NCAyLjc1IDcuMzEyIDAuMDggMC43NTkgMC4wNSAyLj' .
                    'Q4LTAuMDMgMy4yMTktMC4yMyAxLjgyNi0wLjcgMy4zNzgtMS41IDQuOTY5L' .
                    'TAuODEgMS41OTctMS40OCAyLjUxNC0yLjc2IDMuODEyLTIuMDMgMi4wN' .
                    'zctNS4xOCA0LjgyOS0xMC43OCA5LjQwNy0zLjYgMi45NDQtNi4wNCA1LjE1' .
                    'Ni04LjEyIDcuMzQzLTQuOTQzIDUuMTc5LTcuMTkxIDkuMDY5LTguNTY0' .
                    'IDE0LjcxOS0wLjkwNSAzLjcyLTEuMjU2IDcuNTUtMS4xNTYgMTMuMTkgMC4' .
                    'wMjUgMS40IDAuMDYyIDIuNzMgMC4wNjIgMi45N3YwLjQzaDIxLjU5OGw' .
                    'wLjAzLTIuNGMwLjAzLTMuMjcgMC4yMS01LjM3IDAuNTYtNy40MSAwLjU3LT' .
                    'MuMjcgMS40My01IDMuOTQtNy44MSAxLjYtMS44IDMuNy0zLjc2IDYuOT' .
                    'MtNi40NyA0Ljc3LTMuOTkxIDguMTEtNi45OSAxMS4yNi0xMC4xMjUgNC45M' .
                    'S00LjkwNyA3LjQ2LTguMjYgOS4yOC0xMi4xODcgMS40My0zLjA5MiAyL' .
                    'jIyLTYuMTY2IDIuNDYtOS41MzIgMC4wNi0wLjgxNiAwLjA3LTMuMDMgMC0z' .
                    'Ljk2OC0wLjQ1LTcuMDQzLTMuMS0xMy4yNTMtOC4xNS0xOS4wMzItMC44' .
                    'LTAuOTA5LTIuNzgtMi44ODctMy43Mi0zLjcxOC00Ljk2LTQuMzk0LTEwLjY' .
                    '5LTcuMzUzLTE3LjU2LTkuMDk0LTQuMTktMS4wNjItOC4yMy0xLjYtMTM' .
                    'uMzUtMS43NS0wLjc4LTAuMDIzLTEuNTktMC4wMzYtMi4zNy0wLjAzMnptLT' .
                    'EwLjkwOCAxMDMuNnYyMmgyMS45OTh2LTIyaC0yMS45OTh6Ii8+CiA8L2c+C' .
                    'jwvc3ZnPgo=';
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
    public function tryToLogin($formData)
    {
        $login = array_key_exists($this->loginColumn, $formData) ? $formData[$this->loginColumn] : null;
        $password = array_key_exists($this->plaintextField, $formData) ? $formData[$this->plaintextField] : null;
        if (empty($login)) {
            $this->addStatusMessage(_('missing login'), 'error');

            return null;
        }
        if (empty($password)) {
            $this->addStatusMessage(_('missing password'), 'error');

            return null;
        }
        if ($this->authentize($password)) {
            return true;
        } else {
            $this->addStatusMessage(sprintf(_('user %s does not exist'), $login, 'error'));
            return false;
        }
    }

    /**
     * Try to authentize user
     *
     * @return boolean
     */
    public function authentize($plaintext)
    {
        if ($this->validatePassword($plaintext)) {
            if ($this->isAccountEnabled()) {
                return $this->loginSuccess();
            } else {
                $this->userID = null;

                return false;
            }
        } else {
            $this->userID = null;
            if (!empty($this->getData())) {
                $this->addStatusMessage(_('invalid password'), 'error');
            }
            $this->dataReset();

            return false;
        }
    }

    /**
     * Try to validate Password
     *
     * @param string $password plaintext
     *
     * @return boolean
     */
    public function validatePassword($password)
    {
        return self::passwordValidation($password, $this->getDataValue($this->passwordColumn));
    }

    /**
     * Je učet povolen ?
     *
     * @return bool
     */
    public function isAccountEnabled()
    {
        if (empty($this->disableColumn)) {
            return true;
        }
        if ($this->getDataValue($this->disableColumn)) {
            $this->addStatusMessage(
                _('Sign in denied by administrator'),
                'warning'
            );

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
    public function loginSuccess()
    {
        $this->userID = (int) $this->getMyKey();
        $this->setUserLogin($this->getDataValue($this->loginColumn));
        $this->logged = true;
        $this->addStatusMessage(sprintf(_('Signed in as %s'), $this->userLogin), 'success');
        $this->setObjectName();
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
    public static function passwordValidation($plainPassword, $encryptedPassword)
    {
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
    public static function encryptPassword($plainTextPassword)
    {
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
    public function passwordChange(/** @scrutinizer ignore-unused */ $newPassword)
    {
        return false;
    }

    /**
     * Vraci ID přihlášeného uživatele.
     *
     * @return int ID uživatele
     */
    public function getUserID()
    {
        return isset($this->userID) ? (int) $this->userID : (int) $this->getMyKey();
    }

    /**
     * Vrací login uživatele.
     *
     * @return string
     */
    public function getUserLogin()
    {
        return isset($this->userLogin) ? $this->userLogin : $this->getDataValue($this->loginColumn);
    }

    /**
     * Nastavuje login uživatele.
     *
     * @return boolean
     */
    public function setUserLogin($login)
    {
        $this->userLogin = $login;
        return isset($this->loginColumn) ? $this->setDataValue($this->loginColumn, $login) : true;
    }

    /**
     * Vrací hodnotu uživatelského oprávnění.
     *
     * @param string $permKeyword klíčové slovo oprávnění
     *
     * @return mixed
     */
    public function getPermission($permKeyword = null)
    {
        if (isset($this->permissions[$permKeyword])) {
            return $this->permissions[$permKeyword];
        } else {
            return;
        }
    }

    /**
     * Provede odhlášení uživatele.
     */
    public function logout()
    {
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
    public function getSettingValue($settingName = null)
    {
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
    public function setSettings($settings)
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Nastaví položku nastavení.
     *
     * @param string $settingName  klíčové slovo pro nastavení
     * @param mixed  $settingValue hodnota nastavení
     */
    public function setSettingValue($settingName, $settingValue)
    {
        $this->settings[$settingName] = $settingValue;
    }

    /**
     * Načte oprávnění.
     *
     * @return mixed
     */
    public function loadPermissions()
    {
        return;
    }

    /**
     * Vrací jméno objektu uživatele.
     *
     * @return string
     */
    public function getName()
    {
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
    public static function getGravatar($email, $size = 80, $default = 'mm', $maxRating = 'g')
    {
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
    public function setObjectName($objectName = null)
    {

        if (empty($objectName) && isset($_SERVER['REMOTE_ADDR'])) {
            $name = parent::setObjectName(get_class($this) . ':' . $this->getUserName() . '@' .
                    self::remoteToIdentity());
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
    public static function singleton($user = null)
    {
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
    public function __sleep()
    {
        return ['logged', 'data'];
    }
}
