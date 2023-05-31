<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

class M
{
    public function send_email($address, $content)
    {
        $mail = new PHPMailer(true);

        try {
            // Serwer SMTP Mailtrap
            // $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'b3551d02d9d42d'; // login z Mailtrap
            $mail->Password = '49bd670b97d104'; // hasło z Mailtrap
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Nadawca i odbiorca
            $mail->setFrom('nadawca@example.com', 'Nadawca');
            $mail->addAddress($address, 'Odbiorca');

            // Treść e-maila
            $mail->isHTML(true);
            $mail->Subject = 'Kod do logowania';
            $mail->Body = $content;

            $mail->send();
            echo 'Wiadomość została wysłana';
        } catch (Exception $e) {
            echo "Nie udało się wysłać wiadomości. Błąd: {$mail->ErrorInfo}";
        }
    }
}