<?php

class Ingbling
{
    private $defaults =
    [
        "settings" => [
            "basedir" => "app",
            "url" => "http://localhost/ingbling",
            "errors" => false
        ],
        "db" => [
            "host" => "localhost",
            "port" => 27017,
            "name" => "ingbling",
            "user" => "",
            "password" => ""
        ]
    ];

    private $loadedSettings = [];
    private $loadedDBSettings = [];

    public function execute()
    {
        $this->loadSettings();
        $this->settingsSetup();
    }

    private function loadSettings()
    {
        $content = file_get_contents("ingbling.json");
        $content = json_decode($content, true);

        $content = parse_args($content, $this->defaults);
        $this->loadedSettings = $content["settings"];
        $this->loadedDBSettings = $content["db"];

        unset($this->defaults);
        unset($content);
    }

    private function settingsSetup()
    {
        $errors = $this->loadedSettings["errors"];

        if ($errors === true || $errors === "true")
        {
            ini_set("display_errors", 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
        }

        define("DB_HOST", $this->loadedDBSettings["host"]);
        define("DB_PORT", $this->loadedDBSettings["port"]);
        define("DB_NAME", $this->loadedDBSettings["name"]);
        define("DB_USER", $this->loadedDBSettings["user"]);
        define("DB_PASSWORD", $this->loadedDBSettings["password"]);

        $connectionString = "mongodb://";

        if (DB_USER !== "" && DB_PASSWORD !== "")
            $connectionString .= DB_USER . ":" . DB_PASSWORD . "@";

        $connectionString .= DB_HOST . ":" . DB_PORT;

        $url = $this->loadedSettings["url"];
        $basedir = $this->loadedSettings["basedir"];

        if (!ends_with($url, "/")) $url .= "/";
        if (!ends_with($basedir, "/")) $basedir .= "/";

        define("CONNECTION_STRING", $connectionString);
        define("BASE_URL", $url);
        define("BASE_DIR", $basedir);
        define("APP_URL", BASE_URL . BASE_DIR);

        define("PROJECT_DIR", dirname(__FILE__) . "/../");
        define("APP_DIR", PROJECT_DIR . BASE_DIR);
        define("CORE_DIR", PROJECT_DIR . "core/");
    }
}