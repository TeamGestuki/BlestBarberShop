<?php
// services/EmailService.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';

class EmailService {
    
    private static function configurarMailer() {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'blestbarber.app@gmail.com'; 
        $mail->Password   = 'PEDIR CONTRASEÑA'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('blestbarber.app@gmail.com', 'Blest Barber');
        
        return $mail;
    }

    public static function enviarConfirmacion($emailDestino, $nombreUsuario, $fecha, $hora, $sede, $servicio) {
        try {
            $mail = self::configurarMailer();
            $mail->addAddress($emailDestino, $nombreUsuario);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmacion de Turno - BLEST BARBER';
            $mail->Body    = "<h3>¡Hola $nombreUsuario!</h3><p>Tu turno ha sido reservado exitosamente.</p><ul><li><strong>Fecha:</strong> $fecha</li><li><strong>Hora:</strong> $hora</li><li><strong>Sede:</strong> $sede</li><li><strong>Servicio:</strong> $servicio</li></ul><p>¡Te esperamos!</p>";
            return $mail->send();
        } catch (Exception $e) { return false; }
    }

    public static function enviarRecordatorio($emailDestino, $nombreUsuario, $hora, $sede) {
        try {
            $mail = self::configurarMailer();
            $mail->addAddress($emailDestino, $nombreUsuario);
            $mail->isHTML(true);
            $mail->Subject = 'Recordatorio de Turno - BLEST BARBER';
            $mail->Body    = "<h3>¡Hola $nombreUsuario!</h3><p>Te recordamos que tienes un turno con nosotros <strong>hoy a las $hora</strong> en nuestra sucursal de <strong>$sede</strong>.</p><p>Por favor, intenta llegar 5 minutos antes.</p><p>¡Nos vemos pronto!</p>";
            return $mail->send();
        } catch (Exception $e) { return false; }
    }
}