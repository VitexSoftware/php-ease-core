<?php

/**
 * User objects.
 *
 * @author    Vítězslav Dvořák <vitex@hippy.cz>
 * @copyright 2009-2024 Vitex@hippy.cz (G)
 *
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
 * User Class.
 *
 * @author  Vítězslav Dvořák <vitex@hippy.cz>
 */
class User extends Anonym
{
    /**
     * @var null|User Singleton is stored here
     */
    public static ?User $instance;

    /**
     * ID prave nacteneho uzivatele.
     *
     * @var null|int unsigned
     */
    public ?int $userID = null;

    /**
     * Přihlašovací jméno uživatele.
     */
    public ?string $userLogin = null;

    /**
     * Pole uživatelských nastavení.
     *
     * @var array<string, mixed>
     */
    public array $settings = [];

    /**
     * Sloupeček s loginem.
     */
    public ?string $loginColumn = 'login';

    /**
     * Password Column.
     */
    public string $passwordColumn = 'password';

    /**
     * Plaintext Form input name.
     */
    public string $plaintextField = 'password';

    /**
     * Sloupecek pro docasne zablokovani uctu.
     */
    public ?string $disableColumn = null;

    /**
     * Column for user mail.
     */
    public ?string $mailColumn = 'email';

    /**
     * Sloupeček obsahující serializované rozšířené informace.
     */
    public string $settingsColumn = '';

    /**
     * Store of user permissions.
     */
    public array $permissions = [];

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
     * Prepare for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['logged', 'data'];
    }

    /**
     * Give you user name.
     */
    public function getUserName(): string
    {
        return (string) $this->getDataValue($this->loginColumn);
    }

    /**
     * Return user's mail address.
     */
    public function getUserEmail(): string
    {
        return (string) $this->getDataValue($this->mailColumn);
    }

    /**
     * Vykreslí GrAvatara uživatele.
     */
    public function draw(): string
    {
        return '<img class="avatar" src="'.$this->getIcon().'">';
    }

    /**
     * Vrací odkaz na url ikony.
     *
     * @return string url ikony
     */
    public function getIcon(): string
    {
        $email = $this->getUserEmail();

        if ($email) {
            return self::getGravatar($email, 800, 'mm', 'g');
        }

        return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZG'.
                'luZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZC'.
                'B3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tP'.
                'go8c3ZnIGlkPSJzdmcyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvM'.
                'jAwMC9zdmciIGhlaWdodD0iMjAwIiB3aWR0aD0iMjAwIiB2ZXJzaW9uPSIx'.
                'LjAiPgogPHBhdGggaWQ9InBhdGgyMzgyIiBkPSJtMTY1LjMzIDExMy40'.
                'NGExMDMuNjEgMTAzLjYxIDAgMSAxIC0yMDcuMjIgMCAxMDMuNjEgMTAzLjY'.
                'xIDAgMSAxIDIwNy4yMiAweiIgdHJhbnNmb3JtPSJtYXRyaXgoLjkzNzM'.
                '5IDAgMCAuOTM3MzkgNDIuMTQzIC02LjMzOTIpIiBzdHJva2Utd2lkdGg9Ij'.
                'AiIGZpbGw9IiNmZmYiLz4KIDxnIGlkPSJsYXllcjEiPgogIDxwYXRoIG'.
                'lkPSJwYXRoMjQxMyIgZD0ibTEwMCAwYy01NS4yIDAtMTAwIDQ0LjgtMTAwI'.
                'DEwMC01LjA0OTVlLTE1IDU1LjIgNDQuOCAxMDAgMTAwIDEwMHMxMDAtN'.
                'DQuOCAxMDAtMTAwLTQ0LjgtMTAwLTEwMC0xMDB6bTAgMTIuODEyYzQ4LjEz'.
                'IDAgODcuMTkgMzkuMDU4IDg3LjE5IDg3LjE4OHMtMzkuMDYgODcuMTkt'.
                'ODcuMTkgODcuMTktODcuMTg4LTM5LjA2LTg3LjE4OC04Ny4xOSAzOS4wNTg'.
                'tODcuMTg4IDg3LjE4OC04Ny4xODh6bTEuNDcgMjEuMjVjLTUuNDUgMC4'.
                'wMy0xMC42NTMgMC43MzctMTUuMjgyIDIuMDYzLTQuNjk5IDEuMzQ2LTkuMT'.
                'I2IDMuNDg0LTEyLjg3NiA2LjIxOS0zLjIzOCAyLjM2Mi02LjMzMyA1Lj'.
                'M5MS04LjY4NyA4LjUzMS00LjE1OSA1LjU0OS02LjQ2MSAxMS42NTEtNy4wN'.
                'jMgMTguNjg3LTAuMDQgMC40NjgtMC4wNyAwLjg2OC0wLjA2MiAwLjg3N'.
                'iAwLjAxNiAwLjAxNiAyMS43MDIgMi42ODcgMjEuODEyIDIuNjg3IDAuMDUz'.
                'IDAgMC4xMTMtMC4yMzQgMC4yODItMC45MzcgMS45NDEtOC4wODUgNS40'.
                'ODYtMTMuNTIxIDEwLjk2OC0xNi44MTMgNC4zMi0yLjU5NCA5LjgwOC0zLjY'.
                'xMiAxNS43NzgtMi45NjkgMi43NCAwLjI5NSA1LjIxIDAuOTYgNy4zOCA'.
                'yIDIuNzEgMS4zMDEgNS4xOCAzLjM2MSA2Ljk0IDUuODEzIDEuNTQgMi4xNT'.
                'YgMi40NiA0LjU4NCAyLjc1IDcuMzEyIDAuMDggMC43NTkgMC4wNSAyLj'.
                'Q4LTAuMDMgMy4yMTktMC4yMyAxLjgyNi0wLjcgMy4zNzgtMS41IDQuOTY5L'.
                'TAuODEgMS41OTctMS40OCAyLjUxNC0yLjc2IDMuODEyLTIuMDMgMi4wN'.
                'zctNS4xOCA0LjgyOS0xMC43OCA5LjQwNy0zLjYgMi45NDQtNi4wNCA1LjE1'.
                'Ni04LjEyIDcuMzQzLTQuOTQzIDUuMTc5LTcuMTkxIDkuMDY5LTguNTY0'.
                'IDE0LjcxOS0wLjkwNSAzLjcyLTEuMjU2IDcuNTUtMS4xNTYgMTMuMTkgMC4'.
                'wMjUgMS40IDAuMDYyIDIuNzMgMC4wNjIgMi45N3YwLjQzaDIxLjU5OGw'.
                'wLjAzLTIuNGMwLjAzLTMuMjcgMC4yMS01LjM3IDAuNTYtNy40MSAwLjU3LT'.
                'MuMjcgMS40My01IDMuOTQtNy44MSAxLjYtMS44IDMuNy0zLjc2IDYuOT'.
                'MtNi40NyA0Ljc3LTMuOTkxIDguMTEtNi45OSAxMS4yNi0xMC4xMjUgNC45M'.
                'S00LjkwNyA3LjQ2LTguMjYgOS4yOC0xMi4xODcgMS40My0zLjA5MiAyL'.
                'jIyLTYuMTY2IDIuNDYtOS41MzIgMC4wNi0wLjgxNiAwLjA3LTMuMDMgMC0z'.
                'Ljk2OC0wLjQ1LTcuMDQzLTMuMS0xMy4yNTMtOC4xNS0xOS4wMzItMC44'.
                'LTAuOTA5LTIuNzgtMi44ODctMy43Mi0zLjcxOC00Ljk2LTQuMzk0LTEwLjY'.
                '5LTcuMzUzLTE3LjU2LTkuMDk0LTQuMTktMS4wNjItOC4yMy0xLjYtMTM'.
                'uMzUtMS43NS0wLjc4LTAuMDIzLTEuNTktMC4wMzYtMi4zNy0wLjAzMnptLT'.
                'EwLjkwOCAxMDMuNnYyMmgyMS45OTh2LTIyaC0yMS45OTh6Ii8+CiA8L2c+C'.
                'jwvc3ZnPgo=';
    }

    /**
     * Try to Sign in.
     *
     * @param array $formData pole dat z přihlaš. formuláře např. $_REQUEST
     */
    public function tryToLogin(array $formData): bool
    {
        $login = \array_key_exists($this->loginColumn, $formData) ? $formData[$this->loginColumn] : null;
        $password = \array_key_exists($this->plaintextField, $formData) ? $formData[$this->plaintextField] : null;

        if (empty($login)) {
            $this->addStatusMessage(_('missing login'), 'error');

            return false;
        }

        if (empty($password)) {
            $this->addStatusMessage(_('missing password'), 'error');

            return false;
        }

        if ($this->authentize($password)) {
            return true;
        }

        $this->addStatusMessage(sprintf(_('user %s does not exist'), $login, 'error'));

        return false;
    }

    /**
     * Try to authentize user.
     *
     * @param mixed $plaintext
     *
     * @return bool
     */
    public function authentize($plaintext)
    {
        if ($this->validatePassword($plaintext)) {
            if ($this->isAccountEnabled()) {
                return $this->loginSuccess();
            }

            $this->userID = null;

            return false;
        }

        $this->userID = null;

        if (!empty($this->getData())) {
            $this->addStatusMessage(_('invalid password'), 'error');
        }

        $this->dataReset();

        return false;
    }

    /**
     * Try to validate Password.
     *
     * @param string $password plaintext
     *
     * @return bool
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
                'warning',
            );

            return false;
        }

        return true;
    }

    /**
     * Akce provedené po úspěšném přihlášení
     * pokud tam jeste neexistuje zaznam, vytvori se novy.
     *
     * @return bool
     */
    public function loginSuccess()
    {
        $this->userID = (int) $this->getMyKey();
        $this->setUserLogin((string) $this->getDataValue($this->loginColumn));
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

            if (\count($passwordStack) !== 2) {
                return false;
            }

            if (md5($passwordStack[1].$plainPassword) === $passwordStack[0]) {
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

        return md5($passwordSalt.$plainTextPassword).':'.$passwordSalt;
    }

    /**
     * Změní uživateli uložené heslo.
     *
     * @param string $newPassword nové heslo
     *
     * @return bool success
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
    public function getUserID(): int
    {
        return isset($this->userID) ? (int) $this->userID : (int) $this->getMyKey();
    }

    /**
     * Vrací login uživatele.
     */
    public function getUserLogin(): string
    {
        return $this->userLogin ?? (string) $this->getDataValue($this->loginColumn);
    }

    /**
     * Set User Login name.
     */
    public function setUserLogin(string $login): bool
    {
        $this->userLogin = $login;

        return isset($this->loginColumn) ? $this->setDataValue($this->loginColumn, $login) : true;
    }

    /**
     * Set user's permission value.
     */
    public function getPermission(?string $permKeyword = null): ?string
    {
        return $this->permissions[$permKeyword] ?? null;
    }

    /**
     * Sign off.
     */
    public function logout(): bool
    {
        $this->logged = false;
        $this->addStatusMessage(_('Sign Out successful'), 'success');

        return true;
    }

    /**
     * Get Setting.
     */
    public function getSettingValue(string $settingName): ?string
    {
        return \array_key_exists($settingName, $this->settings) ? (string) $this->settings[$settingName] : null;
    }

    /**
     * Nastavuje nastavení.
     *
     * @param array $settings asociativní pole nastavení
     */
    public function setSettings($settings): bool
    {
        $this->settings = array_merge($this->settings, $settings);

        return true;
    }

    /**
     * Nastaví položku nastavení.
     *
     * @param string $settingName  klíčové slovo pro nastavení
     * @param mixed  $settingValue hodnota nastavení
     */
    public function setSettingValue($settingName, $settingValue): bool
    {
        $this->settings[$settingName] = $settingValue;

        return true;
    }

    /**
     * Načte oprávnění.
     *
     * @return mixed
     */
    public function loadPermissions()
    {
    }

    /**
     * Vrací jméno objektu uživatele.
     */
    public function getName(): string
    {
        return $this->getObjectName();
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email
     * address.
     *
     * @param string $email     The email address
     * @param int    $size      Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param string $default   [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $maxRating Maximum rating (inclusive) [ g | pg | r | x ]
     *
     * @return string containing either just a URL or a complete image tag
     *
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function getGravatar($email, $size = 80, $default = 'mm', $maxRating = 'g'): string
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s={$size}&d={$default}&r={$maxRating}";

        return $url;
    }

    /**
     * Nastavení jména objektu uživatele.
     *
     * @param null|mixed $objectName
     */
    public function setObjectName($objectName = null): string
    {
        if (empty($objectName) && isset($_SERVER['REMOTE_ADDR'])) {
            $name = parent::setObjectName(\get_class($this).':'.$this->getUserName().'@'.
                    self::remoteToIdentity());
        } else {
            $name = parent::setObjectName($objectName);
        }

        return $name;
    }

    /**
     * Common instance of User class.
     *
     * @param null|mixed $user
     *
     * @return \Ease\User
     */
    public static function singleton($user = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = null === $user ? new self() : $user;
        }

        return self::$instance;
    }
}
