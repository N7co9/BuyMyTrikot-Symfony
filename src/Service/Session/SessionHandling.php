<?php

namespace App\Service\Session;

class SessionHandling
{
    public string $session;

    public function getSessionMail(): string
    {
        $this->session = $_SESSION['mail'] ?? '';
        return $this->session;
    }

    public function unsetSession(): void
    {
        unset($_SESSION['mail']);
        session_destroy();
    }

    public function setSession(string $mail): void
    {
        if (empty(session_id())) {
            session_start();
        }
        $_SESSION['mail'] = $mail;
        $this->session = $_SESSION['mail'];
    }

    public function setOrderSession($dataKeys): void
    {
        foreach ($dataKeys as $key) {
            if (isset($_POST[$key])) {
                $_SESSION[$key] = $_POST[$key];
            }
        }
    }

    public function getOrderSession(): array
    {
        return [
            "firstName" => $_SESSION['first_name'] ?? '',
            "lastName" => $_SESSION['last_name'] ?? '',
            "address" => $_SESSION['address'] ?? '',
            "city" => $_SESSION['city'] ?? '',
            "zip" => $_SESSION['zip'] ?? '',
            "delivery" => $_SESSION['delivery'] ?? '',
            "payment" => $_SESSION['payment'] ?? '',
            "email" => $_SESSION['mail'] ?? ''
        ];
    }
}