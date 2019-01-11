<?php
/**
 * Třídy pro odesílání Mailu ✉.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2012 Vitex@hippy.cz (G)
 */

namespace Ease;

/**
 * Build & Send email
 * Sestaví a odešle mail.
 *
 * @author Vitex <vitex@hippy.cz>
 */
class Mailer extends Page
{
    /**
     * Objekt pro odesílání pošty.
     *
     * @var
     */
    public $mailer          = null;
    public $mimer           = null;
    public $textBody        = null;
    public $mailHeaders     = [];
    public $mailHeadersDone = null;
    public $crLf            = "\n";
    public $mailBody        = null;
    public $finalized       = false;

    /**
     * Již vzrendrované HTML.
     *
     * @var string
     */
    public $htmlBodyRendered = null;

    /**
     * Adresa odesilatele zprávy.
     *
     * @var string
     */
    public $emailAddress = 'postmaster@localhost';
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
     * Objekt stránky pro rendrování do mailu.
     *
     * @var Html\HtmlTag
     */
    public $htmlDocument = null;

    /**
     * Ukazatel na BODY html dokumentu.
     *
     * @var Html\BodyTag
     */
    public $htmlBody = null;

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
    public function __construct($emailAddress, $mailSubject,
                                $emailContents = null)
    {
        if (defined('EASE_SMTP')) {
            $this->parameters = (array) json_decode(constant('EASE_SMTP'));
        }

        if (is_array($emailAddress)) {
            $emailAddress = current($emailAddress).' <'.key($emailAddress).'>';
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

        $this->mimer = new \Mail_mime($mimer_params);

        parent::__construct();
        if (isset($emailContents)) {
            $this->setMailBody($emailContents);
        }
    }

    /**
     * Set mail text body
     * 
     * @param string $text
     */
    public function setMailBody($text)
    {
        $this->mimer->setTXTBody($text);
    }

    /**
     * Vrací obsah poštovní hlavičky.
     *
     * @param string $headername název hlavičky
     *
     * @return string
     */
    public function getMailHeader($headername)
    {
        if (isset($this->mailHeaders[$headername])) {
            return $this->mailHeaders[$headername];
        }
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
        if (is_array($this->mailHeaders)) {
            $this->mailHeaders = array_merge($this->mailHeaders, $mailHeaders);
        } else {
            $this->mailHeaders = $mailHeaders;
        }
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
     */
    public function addFile($filename, $mimeType = 'text/plain')
    {
        $this->mimer->addAttachment($filename, $mimeType);
    }

    /**
     * Send mail.
     */
    public function send()
    {
        $oMail = new \Mail();
        if (count($this->parameters)) {
            $this->mailer = $oMail->factory('smtp', $this->parameters);
        } else {
            $this->mailer = $oMail->factory('mail');
        }
        $this->sendResult = $this->mailer->send($this->emailAddress,
            $this->mailHeadersDone, $this->mailBody);

        if ($this->notify === true) {
            $mailStripped = str_replace(['<', '>'], '', $this->emailAddress);
            if ($this->sendResult === true) {
                $this->addStatusMessage(sprintf(_('Message %s was sent to %s'),
                        $this->emailSubject, $mailStripped), 'success');
            } else {
                $this->addStatusMessage(sprintf(_('Message %s, for %s was not sent because of %s'),
                        $this->emailSubject, $mailStripped,
                        $this->sendResult->message), 'warning');
            }
        }

        return $this->sendResult;
    }

    /**
     * Nastaví návěští uživatelské notifikace.
     *
     * @param bool $notify požadovaný stav notifikace
     */
    public function setUserNotification($notify)
    {
        $this->notify = (bool) $notify;
    }
}
