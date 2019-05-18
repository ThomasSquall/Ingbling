<?php

require_once "includes.php";

session_start();

$ingbling = new Ingbling();
$ingbling->execute();

$database = new Ingbling\DB\Database();
$resolver = new Ingbling\MVC\Resolver();

$resolver->resolve();