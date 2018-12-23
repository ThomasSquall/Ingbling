<?php

require_once "includes.php";

session_start();

$ingbling = new Ingbling();
$ingbling->execute();

$database = new Ingbling\DB\Database();
$resolver = new Ingbling\MVC\Resolver();

$resolver->resolve
(
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