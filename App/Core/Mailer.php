<?php

namespace App\Core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public function send($to, $subject, $body, $isHtml = false)
    {
        // Una instància nova per a cada correu evita acumular destinataris.
        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';

            // SMTP de Mailpit per a l'entorn local, sense autenticació ni TLS.
            $mail->isSMTP();
            $mail->Host = 'mailpit';
            $mail->Port = 1025;
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
            $mail->setFrom('no-reply@miniapp.test', 'MiniApp PHPMailer');

            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->isHTML($isHtml);

            return $mail->send();
        } catch (Exception $e) {
            error_log('Error en enviar el correu: ' . $e->getMessage());
            return false;
        }
    }
}
