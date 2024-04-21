<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarReestablecer(){
        $phpmailer = new PHPMailer();
        
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV["EMAIL_HOST"];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV["EMAIL_PORT"];
        $phpmailer->Username = $_ENV["EMAIL_USER"];
        $phpmailer->Password = $_ENV["EMAIL_PASSWORD"];

        $phpmailer->setFrom($_ENV["EMAIL_USER"]);
        $phpmailer->addAddress($this->email);
        $phpmailer->Subject = 'Reestablece tu contraseña';

        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';
        $contenido = '<html>';
        $contenido .= '<p> Hola '.$this->nombre.' Has olvidado tu contraseña de Lecturómetro, haz click en el enlace para reestablecerla</p>';
        $contenido .= '<a href="'. $_ENV["APP_URL"].'/reestablecer?token='. $this->token .'"> Reestablecer Contraseña</a>';
        $contenido .= '<p>Si tu no solicitaste Reestablecer tu contraseña puedes ignorar este correo</p>';
        $contenido .= '</html>';

        $phpmailer->Body = $contenido;
        $phpmailer->send();
    }
}