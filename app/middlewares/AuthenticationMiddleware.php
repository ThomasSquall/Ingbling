<?php

class AuthenticationMiddleware extends Ingbling\MVC\MiddlewareBase
{
    public function resolve($endpoint)
    {
        switch ($endpoint)
        {
            case 'login':
            case 'register':
                if (isset($_SESSION['username']))
                {
                    header("Location: " . BASE_URL);
                    exit();
                }

                break;
            default:
                if (!isset($_SESSION['username']))
                {
                    header("Location: " . BASE_URL . "login");
                    exit();
                }

                break;
        }
    }
}