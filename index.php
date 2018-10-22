<?php

require_once "includes.php";
use PHPEasyAPI\Resolver;

session_start();

$database = new Database();
$resolver = new Resolver();

$resolver->setBaseUrl(BASE_URL);

foreach (glob(APP_DIR . "controllers/*Controller.php") as $file)
{
    $files = explode('/', $file);
    $controller = explode(".php", $files[count($files) - 1])[0];
    $resolver->bindListener(new $controller());
}

$resolver->resolve
(
    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
    function ($endpoint)
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
);