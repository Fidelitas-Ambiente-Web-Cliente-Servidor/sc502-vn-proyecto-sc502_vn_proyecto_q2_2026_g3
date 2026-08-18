<?php

class AuthController
{
    public function showLogin(): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function showRecuperar(): void
    {
        require __DIR__ . '/../views/auth/recuperar.php';
    }
}
