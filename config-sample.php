<?php

define("DBUSER", '');
define("DBHOST", "localhost");
define("DBPORT", 27017);
define("DBNAME", "ingbling");
define("DBLOGIN", "");
define("DBOPTIONS", "");

if (defined(DBUSER) && DBUSER !== "")
    define("CONNECTION_STRING", "mongodb://" . DBUSER . '@' . DBHOST . ':' . DBPORT . DBLOGIN . DBOPTIONS);
else
    define("CONNECTION_STRING", "mongodb://" . DBHOST . ':' . DBPORT . DBLOGIN . DBOPTIONS);

define("SHOW_ERRORS", TRUE);