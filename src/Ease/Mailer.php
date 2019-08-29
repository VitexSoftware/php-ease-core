<?php
/**
 * Třídy pro odesílání Mailu ✉.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2012 Vitex@hippy.cz (G)
 * 
 * PHP 7
 */

namespace Ease;

/**
 * Build & Send email
 * Sestaví a odešle mail.
 *
 * @author Vitex <vitex@hippy.cz>
 */
class Mailer extends Sand
{
    /**
     * Objekt pro odesílání pošty.
     *
     * @var
     */
    public $mailer = null;

    /**
     * MIME Helper
     *
     * @var \Mail_mime 
     */
    public $mimer = null;

    /**
     *
     * @var string 
     */
    public $textBody = null;

    /**
     * Mail Headers
     *
     * @var array 
     */
    public $mailHeaders = [];

    /**
     * Processed Headers
     *
     * @var array
     */
    public $mailHeadersDone = [];

    /**
     * Line divider
     *
     * @var string
     */
    public $crLf = "\n";

    /**
     *
     * @var array 
     */
    public $mailBody = null;

    /**
     * 
     * @var boolean 
     */
    public $finalized = false;

    /**
     * Adresa odesilatele zprávy.
     *
     * @var string
     */
    public $emailAddress = 'postmaster@localhost';

    /**
     * Email subject holder
     *
     * @var string 
     */
    public $emailSubject = null;

    /**
     * Emailová adresa odesilatele.
     *
     * @var string
     */
    public $fromEmailAddress = null;

    /**
     * Zobrazovat uživateli informaci o odeslání zprávy ?
     *
     * @var bool
     */
    public $notify = true;

    /**
     * Byla již zpráva odeslána ?
     *
     * @var bool
     */
    public $sendResult = false;

    /**
     * Parametry odchozí pošty.
     *
     * @var array
     */
    public $parameters = [];

    /**
     * Ease Mail - sestaví a odešle.
     *
     * @param string $emailAddress  adresa
     * @param string $mailSubject   předmět
     * @param mixed  $emailContents tělo - libovolný mix textu a EaseObjektů
     */
    public function __construct(string $emailAddress, string $mailSubject,
        $emailContents = null
    ) {
        if (defined('EASE_SMTP')) {
            $this->parameters = (array) json_decode(constant('EASE_SMTP'));
        }


        $this->setMailHeaders(
            [
                'To' => $emailAddress,
                'From' => $this->fromEmailAddress,
                'Reply-To' => $this->fromEmailAddress,
                'Subject' => $mailSubject,
                'Content-Type' => 'text/plain; charset=utf-8',
                'Content-Transfer-Encoding' => '8bit',
            ]
        );


        $mimer_params = array(
            'html_charset' => 'utf-8',
            'text_charset' => 'utf-8',
            'head_charset' => 'utf-8',
            'eol' => $this->crLf,
        );

        $this->mimer    = new \Mail_mime($mimer_params);
        $this->textBody = $emailContents;
    }

    /**
     * Set mail text body
     * 
     * @param string $text
     * 
     * @return boolean|\Pear_Err
     */
    public function setMailBody($text)
    {
        return $this->mimer->setTXTBody($text);
    }

    /**
     * Obtain mail header content
     *
     * @param string $headername requested header name
     *
     * @return string|null requested header value
     */
    public function getMailHeader($headername)
    {
        return array_key_exists($headername, $this->mailHeaders) ? $this->mailHeaders[$headername]
                : null;
    }

    /**
     * Nastaví hlavičky mailu.
     *
     * @param mixed $mailHeaders asociativní pole hlaviček
     *
     * @return bool true pokud byly hlavičky nastaveny
     */
    public function setMailHeaders(array $mailHeaders)
    {
        $this->mailHeaders = array_merge($this->mailHeaders, $mailHeaders);
        if (isset($this->mailHeaders['To'])) {
            $this->emailAddress = $this->mailHeaders['To'];
        }
        if (isset($this->mailHeaders['From'])) {
            $this->fromEmailAddress = $this->mailHeaders['From'];
        }
        if (isset($this->mailHeaders['Subject'])) {
            if (!strstr($this->mailHeaders['Subject'], '=?UTF-8?B?')) {
                $this->emailSubject           = $this->mailHeaders['Subject'];
                $this->mailHeaders['Subject'] = '=?UTF-8?B?'.base64_encode($this->mailHeaders['Subject']).'?=';
            }
        }
        $this->finalized = false;

        return true;
    }

    /**
     * Připojí k mailu přílohu ze souboru.
     *
     * @param string $filename cesta/název souboru k přiložení
     * @param string $mimeType MIME typ přílohy
     * 
     * @return boolean|\PEAR_Error
     */
    public function addFile($filename, $mimeType = 'text/plain')
    {
        return $this->mimer->addAttachment($filename, $mimeType);
    }

    /**
     * Send mail.
     */
    public function send()
    {
        $this->setMailBody($this->textBody);
        $oMail = new \Mail();
        if (count($this->parameters)) {
            $this->mailer = $oMail->factory('smtp', $this->parameters);
        } else {
            $this->mailer = $oMail->factory('mail');
        }
        $this->sendResult = $this->mailer->send(
            $this->emailAddress,
            $this->mailHeadersDone, $this->mailBody
        );

        if ($this->notify === true) {
            $mailStripped = str_replace(['<', '>'], '', $this->emailAddress);
            if ($this->sendResult === true) {
                $this->addStatusMessage(
                    sprintf(
                        _('Message %s was sent to %s'),
                        $this->emailSubject, $mailStripped
                    ), 'success'
                );
            } else {
                $this->addStatusMessage(
                    sprintf(
                        _('Message %s, for %s was not sent because of %s'),
                        $this->emailSubject, $mailStripped,
                        $this->sendResult->message
                    ), 'warning'
                );
            }
        }

        return $this->sendResult;
    }

    /**
     * Nastaví návěští uživatelské notifikace.
     *
     * @param bool $notify požadovaný stav notifikace
     */
    public function setUserNotification(bool $notify)
    {
        $this->notify = $notify;
    }
}
