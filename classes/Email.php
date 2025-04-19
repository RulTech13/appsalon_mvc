<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct( $email , $nombre , $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        //VARIABLES DE ENTORNO $_ENV creadas en includes/.env
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host       = $_ENV['EMAIL_HOST']; //Set the SMTP server to send through
        $mail->SMTPAuth   = true;  //Enable SMTP authentication
        $mail->Username   = $_ENV['EMAIL_USER']; //SMTP username
        $mail->Password   = $_ENV['EMAIL_PASS']; //SMTP password
        $mail->SMTPSecure = 'tls'; //Enable implicit TLS encryption
        $mail->Port       = $_ENV['EMAIL_PORT'];

        $mail->setFrom('company@management.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma cuenta Salón.';

        //Set html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<style>.enlace{background-color:rgba(115,167,7,0.32);border-radius:0.5rem;color:rgba(115,79,7,0.8);padding:0.5rem;text-decoration:none;font-weight:700;}";
        $contenido .= ".titulo{background-color:rgba(115,79,7,0.8);color:white;padding:1rem;border-radius:0.5rem;font-weight:700;}";
        $contenido .= ".texto{background-color:rgba(115,79,7,0.32);color:black;padding:1rem;border-radius:0.5rem;margin-top:1rem;}</style>";
        $contenido .= "<div class='titulo'><p><strong>Bienvenido " . $this->nombre . ",</strong></p></div>";
        $contenido .= "<div class='texto'><p>Has creado una cuenta en nuestra web Salón. Confirmala en el siguiente enlace:</p><br>";
        // " . $_ENV['APP_URL'] . "  --> injeccion de la url con la variable global
        $contenido .= "<a class='enlace' href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>CONFIRMAR CUENTA</a><br>";
        $contenido .= "<br><p>Si no solicitaste esta cuenta, IGNORA EL MENSAJE.</p></div>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();

    }//-----------------------------------------------------------------------------------------------

    public function enviarInstrucciones() {
        
        //VARIABLES DE ENTORNO $_ENV creadas en includes/.env
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host       = $_ENV['EMAIL_HOST']; //Set the SMTP server to send through
        $mail->SMTPAuth   = true;  //Enable SMTP authentication
        $mail->Username   = $_ENV['EMAIL_USER']; //SMTP username
        $mail->Password   = $_ENV['EMAIL_PASS']; //SMTP password
        $mail->SMTPSecure = 'tls'; //Enable implicit TLS encryption
        $mail->Port       = $_ENV['EMAIL_PORT'];

        $mail->setFrom('company@management.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Regeneración de Contraseña de la App Salón.';

        //Set html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<style>.enlace{background-color:rgba(115,167,7,0.32);border-radius:0.5rem;color:rgba(115,79,7,0.8);padding:0.5rem;text-decoration:none;font-weight:700;}";
        $contenido .= ".titulo{background-color:rgba(115,79,7,0.8);color:white;padding:1rem;border-radius:0.5rem;font-weight:700;}";
        $contenido .= ".texto{background-color:rgba(115,79,7,0.32);color:black;padding:1rem;border-radius:0.5rem;margin-top:1rem;}</style>";
        $contenido .= "<div class='titulo'><p><strong>Apreciad@ " . $this->nombre . ",</strong></p></div>";
        $contenido .= "<div class='texto'><p>Has solicitado una nueva contraseña. Confirmala en el siguiente enlace:</p><br>";
        // " . $_ENV['APP_URL'] . "  --> injeccion de la url con la variable global
        $contenido .= "<a class='enlace' href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>CONFIRMAR CONTRASEÑA</a><br>";
        $contenido .= "<br><p>Si no solicitaste esta contraseña, IGNORA EL MENSAJE.</p></div>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }

}

?>