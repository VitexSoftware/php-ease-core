<?php

/**
 * Classes for sending Mail ✉.
 *
 * @author    Vitex <vitex@hippy.cz>
 * @copyright 2009-2023 Vitex@hippy.cz (G)
 *
 * PHP 7
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
 * Build & Send email
 * Builds and sends the email.
 *
 * @author Vitex <vitex@hippy.cz>
 */
class Mailer extends Sand
{
    /**
     * Object for mail sending.
     */
    public \Mail $mailer;

    /**
     * MIME Helper.
     */
    public \Mail_mime $mimer;
    public string $textBody = '';

    /**
     * Mail Headers.
     */
    public array $mailHeaders = [];

    /**
     * Processed Headers.
     */
    public array $mailHeadersDone = [];

    /**
     * Line divider.
     */
    public string $crLf = "\n";
    public array $mailBody;
    public bool $finalized = false;

    /**
     * The sender's address.
     */
    public string $emailAddress = 'postmaster@localhost';

    /**
     * Email subject holder.
     */
    public string $emailSubject = '';

    /**
     * The sender's email address.
     */
    public string $fromEmailAddress = '';

    /**
     * Show information about sending the message to the user?
     */
    public bool $notify = true;

    /**
     * Has the message already been sent?
     */
    public bool $sendResult = false;

    /**
     * Outgoing mail parameters.
     */
    public array $parameters = [];

    /**
     * Ease Mail - builds and sends.
     *
     * @param string $emailAddress  address
     * @param string $mailSubject   subject
     * @param mixed  $emailContents body - any text mix and EaseObjects
     */
    public function __construct(string $emailAddress, string $mailSubject, $emailContents = null)
    {
        if (\Ease\Shared::cfg('EASE_SMTP')) {
            $this->parameters = json_decode(\Ease\Shared::cfg('EASE_SMTP'), true);
        }

        $this->setMailHeaders(
            [
                'To' => $emailAddress,
                'From' => empty($this->fromEmailAddress) ? \Ease\Shared::cfg('EASE_FROM') : $this->fromEmailAddress,
                'Reply-To' => $this->fromEmailAddress,
                'Subject' => $mailSubject,
                'Content-Type' => 'text/plain; charset=utf-8',
                'Content-Transfer-Encoding' => '8bit',
            ],
        );

        $mimerParams = [
            'html_charset' => 'utf-8',
            'text_charset' => 'utf-8',
            'head_charset' => 'utf-8',
            'eol' => $this->crLf,
        ];

        $this->mimer = new \Mail_mime($mimerParams);
        $this->textBody = (string) $emailContents;
        $this->mimer->setTXTBody('');
        $this->setObjectName();
    }

    /**
     * Sets mail's text body.
     *
     * @param string $text
     *
     * @return bool|\Pear_Err
     */
    public function setMailBody(string $text)
    {
        $this->textBody = $text;

        return $this->mimer->setTXTBody($text);
    }

    /**
     * Obtains mail header's content.
     *
     * @param string $headername requested header name
     *
     * @return null|string requested header value
     */
    public function getMailHeader($headername)
    {
        return \array_key_exists($headername, $this->mailHeaders) ? $this->mailHeaders[$headername] : null;
    }

    /**
     * Sets mail headers.
     *
     * @param array $mailHeaders associative header array
     *
     * @return bool true if the headers have been set
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
                $this->emailSubject = $this->mailHeaders['Subject'];
                $this->mailHeaders['Subject'] = '=?UTF-8?B?'.base64_encode($this->mailHeaders['Subject']).'?=';
            }
        }

        $this->finalized = false;

        return true;
    }

    /**
     * Attaches file to mail.
     *
     * @param string $filename path / file name to attach
     * @param string $mimeType MIME attachment type
     *
     * @return bool|\PEAR_Error
     */
    public function addFile(string $filename, $mimeType = 'text/plain')
    {
        return $this->mimer->addAttachment($filename, $mimeType);
    }

    /**
     * Add Text to mail.
     *
     * @param string $text
     */
    public function addText($text): void
    {
        $this->textBody .= $text;
    }

    /**
     * Sends mail.
     */
    public function send()
    {
        $this->setMailBody($this->textBody);
        $oMail = new \Mail();

        if (\count($this->parameters)) {
            $this->mailer = $oMail->factory('smtp', $this->parameters);
        } else {
            $this->mailer = $oMail->factory('mail');
        }

        $this->sendResult = $this->mailer->send(
            $this->emailAddress,
            $this->mimer->headers($this->mailHeaders),
            $this->mimer->get(),
        );

        if ($this->notify === true) {
            $mailStripped = str_replace(['<', '>'], '', $this->emailAddress);

            if ($this->sendResult === true) {
                $this->addStatusMessage(
                    sprintf(
                        _('Message %s was sent to %s'),
                        $this->emailSubject,
                        $mailStripped,
                    ),
                    'success',
                );
            } else {
                $this->addStatusMessage(
                    sprintf(
                        _('Message %s, for %s was not sent because of %s'),
                        $this->emailSubject,
                        $mailStripped,
                        $this->sendResult->message,
                    ),
                    'warning',
                );
            }
        }

        return $this->sendResult;
    }

    /**
     * Sets the user notification label.
     *
     * @param bool $notify required notification status
     */
    public function setUserNotification(bool $notify): void
    {
        $this->notify = $notify;
    }
}
