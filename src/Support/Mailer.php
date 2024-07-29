<?php

declare(strict_types = 1);

namespace App\Support;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mailer.
 */
final class Mailer
{
    private array $settings;

    /**
     * Constructor.
     *
     * @param array $settings The mail settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Send mail.
     *
     * @param array $toarr The destination array
     * @param string $subject The mail subject
     * @param string $text The simple text
     * @param string $html The HTML format content
     *
     * @return string|null
     */
    public function sendMail(array $toarr, string $subject, string $text, string $html = null): ?string
    {
        $mail = new PHPMailer(true);                    // Passing `true` enables exceptions
        try {
            // Server settings
            $mail->SMTPDebug = 0;                       // Enable verbose debug output
            $mail->isSMTP();                            // Set mailer to use SMTP
            $mail->CharSet = 'UTF-8';                   // set utf-8 character set

            $mail->Host = $this->settings['mail_host'];     // Specify main and backup SMTP servers
            $mail->Port = $this->settings['mail_port'];     // TCP port to connect to
            if ('' != $this->settings['mail_pass']) {
                $mail->SMTPAuth = true;                     // Enable SMTP authentication
                $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
                $mail->Username = $this->settings['mail_user']; // SMTP username
                $mail->Password = base64_decode($this->settings['mail_pass']); // SMTP password
            }
            // Recipients
            $mail->setFrom($this->settings['mail_user'], $this->settings['send_mail_sender']); // Sender
            foreach ($toarr as &$to) {
                $mail->addAddress($to);            // Add all recipient
            }

            // other fields
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->Subject = $subject;
            if ($html != null) {
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Body = $html;
                $mail->AltBody = $text;
            } else {
                $mail->Body = $text;
            }

            if ($this->settings['send_mail_enable']) {
                @$mail->send();
            }

            return null;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }
}
