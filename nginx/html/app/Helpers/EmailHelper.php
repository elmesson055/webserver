<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper {
    private static function getMailer() {
        global $db;
        $settingModel = new SystemSetting($db);
        $settings = $settingModel->getSmtpSettings();

        $mail = new PHPMailer(true);
        
        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = $settings['host'];
            $mail->Port = $settings['port'];
            $mail->SMTPAuth = true;
            $mail->Username = $settings['username'];
            $mail->Password = $settings['password'];
            $mail->SMTPSecure = $settings['encryption'];
            $mail->CharSet = 'UTF-8';

            // Remetente
            $mail->setFrom($settings['from_email'], $settings['from_name']);
            
            return $mail;
        } catch (Exception $e) {
            error_log("Error configuring mailer: " . $e->getMessage());
            throw new Exception('Erro ao configurar servidor de email: ' . $e->getMessage());
        }
    }

    public static function sendEmail($to, $subject, $body, $isHTML = true) {
        try {
            $mail = self::getMailer();
            
            $mail->addAddress($to);
            $mail->Subject = $subject;
            
            if ($isHTML) {
                $mail->isHTML(true);
                $mail->Body = $body;
                $mail->AltBody = strip_tags($body);
            } else {
                $mail->Body = $body;
            }

            return $mail->send();
        } catch (Exception $e) {
            error_log("Error sending email: " . $e->getMessage());
            throw new Exception('Erro ao enviar email: ' . $e->getMessage());
        }
    }

    public static function sendPasswordResetEmail($email, $name, $resetLink) {
        $subject = 'Recuperação de Senha - Custo Extras';
        
        $body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .button { 
                    display: inline-block; 
                    padding: 10px 20px; 
                    background-color: #3490dc; 
                    color: white !important; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    margin: 20px 0;
                }
                .footer { margin-top: 30px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Olá, {$name}!</h2>
                <p>Você solicitou a recuperação de senha para sua conta no sistema Custo Extras.</p>
                <p>Para criar uma nova senha, clique no botão abaixo:</p>
                <p>
                    <a href='{$resetLink}' class='button'>Criar Nova Senha</a>
                </p>
                <p>Se você não solicitou a recuperação de senha, ignore este email.</p>
                <p>Este link expirará em 1 hora por motivos de segurança.</p>
                <div class='footer'>
                    <p>Este é um email automático, por favor não responda.</p>
                    <p>&copy;2024 Elmesson Analytics. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>";

        return self::sendEmail($email, $subject, $body);
    }
}
