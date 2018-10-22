<?php

require_once "defines.php";
require_once "config.php";
require_once "settings.php";
require_once PROJECT_DIR . "vendor/autoload.php";
require_once CORE_DIR . "autoload.php";

foreach (glob(APP_DIR . "controllers/*Controller.php") as $file)
    require_once $file;