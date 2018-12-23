<?php

class Ingbling
{
    private $defaults =
    [
        "settings" => [
            "basedir" => "app",
            "url" => "http://localhost/ingbling"
        ]
    ];

    private $loadedSettings = [];

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

        unset($this->defaults);
        unset($content);
    }

    private function settingsSetup()
    {
        if (SHOW_ERRORS)
        {
            ini_set("display_errors", 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
        }

        $connectionString = "mongodb://";

        if (defined(DBUSER) && DBUSER !== "")
            $connectionString .= DBUSER . "@";

        $connectionString .= DBHOST . ":" . DBPORT . DBLOGIN . DBOPTIONS;

        $url = $this->loadedSettings["url"];
        $basedir = $this->loadedSettings["basedir"];

        if (!ends_with($url, "/")) $url .= "/";
        if (!ends_with($basedir, "/")) $basedir .= "/";

        define("CONNECTION_STRING", $connectionString);
        define("BASE_URL", $url);
        define("APP_URL", BASE_URL . $basedir);

        define("PROJECT_DIR", dirname(__FILE__) . "/../");
        define("APP_DIR", PROJECT_DIR . $basedir);
        define("CORE_DIR", PROJECT_DIR . "core/");
    }
}