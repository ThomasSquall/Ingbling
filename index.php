<?php

require_once "includes.php";
use PHPEasyAPI\Resolver;

session_start();

$resolver = new Resolver();
$database = new Database();

$resolver->setBaseUrl(BASE_URL);
$resolver->bindListener('', new HomeController());
$resolver->bindListener('login', new LoginController());
$resolver->bindListener('logout', new LogoutController());

$resolver->resolve
(
    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],

    function ($endpoint)
    {
        switch ($endpoint)
        {
            case 'register':
            case 'login':
                if (isset($_SESSION['username']))
                {
                    header("Location: " . APP_URL);
                    exit();
                }

                break;
            default:
                if (!isset($_SESSION['username']))
                {
                    header("Location: " . APP_URL . "login");
                    exit();
                }

                break;
        }
    }
);