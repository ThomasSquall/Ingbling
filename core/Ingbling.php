<?php

class Ingbling
{
    private $defaults =
    [
        "settings" => [
            "basedir" => "app"
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

        $this->loadedSettings = parse_args($content, $this->defaults);
        unset($this->defaults);
        unset($content);
    }

    private function settingsSetup()
    {
        if (SHOW_ERRORS)
        {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }

        $connectionString = "mongodb://";

        if (defined(DBUSER) && DBUSER !== "")
            $connectionString .= DBUSER . "@";

        $connectionString .= DBHOST . ':' . DBPORT . DBLOGIN . DBOPTIONS;

        define("CONNECTION_STRING", $connectionString);
        define("APP_URL", BASE_URL . $this->loadedSettings["basedir"]);
    }
}