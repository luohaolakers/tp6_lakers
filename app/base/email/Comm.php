<?php

namespace app\base\email;


use PHPMailer\PHPMailer\PHPMailer;

class Comm
{
    const ADMIN_MAIL = ['haoyuan.luo.partner@decathlon.com'];

    public static function sendToAdmin($title, $body)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Port = 25;
        $mail->SMTPAuth = false;
        $mail->SMTPAutoTLS = false;
        $mail->Host = 'mta.subsidia.org';
        $mail->From = 'oms-monitoring@decathlon.com';
        $mail->FromName = "Decathlon Monitoring";
        foreach (self::ADMIN_MAIL as $mail_to) {
            if (!empty($mail_to))
                $mail->AddAddress($mail_to);
        }
        $mail->Subject = $title;
        $mail->Body = $body;

        $mail->IsHTML(true);
        $mail_send = $mail->Send();
        return $mail_send;
    }
}
