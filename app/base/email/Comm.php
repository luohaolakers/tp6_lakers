<?php

namespace app\base\email;


use PHPMailer\PHPMailer\PHPMailer;

class Comm
{
    const ADMIN_MAIL = ['1147852676@qq.com'];

    public static function sendToAdmin($title, $body)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Port = 25;
        $mail->SMTPAuth = false;
        $mail->SMTPAutoTLS = false;
        $mail->Host = 'mta.subsidia.org';
        $mail->From = 'testlakers.com';
        $mail->FromName = "test Monitoring";
        foreach (self::ADMIN_MAIL as $mail_to) {
            if (!empty($mail_to)) {
                $mail->AddAddress($mail_to);
            }
        }
        $mail->Subject = $title;
        $mail->Body = $body;

        $mail->IsHTML(true);
        return $mail->Send();
    }
}
