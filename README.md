# Ingbling

Ingbling is an easy to use and light MVC framework that helps you building your apps faster and easier.
Differently from the other MVC frameworks it relies on MongoDB instead of MySQL.

## Installation

Download or clone the repository and then start personalize.

## Prerequisites

Before using this library you should make sure to have installed PHP7.0 or major and MongoDb driver from pecl.

For those using a Linux distribution (make sure to have pecl installed) just run:

``` sh
$ sudo pecl install mongodb
```

After that you should put the following string
``` sh
extension=mongodb.so
```
Inside your php.ini

Also, make sure to have composer installed: https://getcomposer.org/

## Getting started

1) The first thing to do is to rename the folder from 'Ingbling' into your project name.
2) Now you should copy config-sample.php into a file called config.php and insert MongoDB info into the right defines.
3) Copy the .htaccess-sample into the .htaccess being sure to change lines 'RewriteBase /ingbling/' and 'RewriteRule . /ingbling/index.php [L]' to match your project URL.
4) On the defines.php change the line 'define("BASE_URL", "/ingbling/");' to match your project URL.
5) Run composer installation:

``` sh
$ composer install
```

### The application

You essentially will work in the app folder.
This folder is divided in 4 important sub-folders:
- assets folder: This folder contains js, css & img folders by default which are used by the core to find Javascripts, CSS and Images files.
- collections folder: This folder contains all your Mongo models/collections.
- controllers folder: This folder contains all your Controllers, one per page.
- templates folder: This folder contains all your templates.

### Collections

All the collections/models should be put in this folder. In the project you will find an example: let's analyze it!

``` php
<?php

/**
 * Class User.
 * [\MongoDriver\Models\Model(name = "users")]
 */
class User
{
    /**
     * @var string $username
     */
    public $username;

    /**
     * @var string $password
     */
    public $password;

    /**
     * Get the hash of the password.
     * @param string $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Checks if the password matches the hash or not.
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
```

This class is powered by thomas-squall/php-magic-annotations and thomas-squall/php7-mongo-driver libraries.

They are both used at line 4, inside the PHPDocs.
This is the annotation:

``` php
 [\MongoDriver\Models\Model(name = "users")]
 ```
 
This annotation tells the framework that the class will write and read to the mongo collection called users. This annotation is required for all your collections/models.

Now to add a User you just only have to do the following:

``` php
function register()
{
    global $database;
    
    $user = new User();
    $user->username = '<your_username>';
    $user->password = User::hashPassword('<your_password>');
    
    $database->insert($user);
}
```

To retrieve and validate a user you should instead do:

``` php
function login()
{
    global $database;
    
    $user = new User();
    
    // $user will be populated with the retrieved data.
    $database->findOne
    (
        $user,
        new MongoDriver\Filter
        (
            'username', // User field name to compare
            '<your_username>', // username value to find
            \MongoDriver\Filter::IS_EQUAL // comparison operator to use
        )
    );
    
    if (User::verifyPassword('<your_password>', $user->password))
    {
        // if true, the inserted password was correct.
        echo "Logged!";
    }
    else
    {
        // if false, the inserted password was wrong.
        echo "Wrong password!";
    }
}
```

## TO BE CONTINUED