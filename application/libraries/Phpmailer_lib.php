<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once FCPATH . 'vendor/autoload.php';  // Composer autoload

class Phpmailer_lib {
    public function load() {
        $mail = new PHPMailer(true);
        return $mail;
    }
}
