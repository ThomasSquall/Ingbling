#!/usr/bin/env bash

command="$1"
repo="https://github.com/ThomasSquall/Ingbling.git"
dir=$(pwd)/
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

function check-is-installed {
    command="$1"
    "$command" --version 2>&1 >/dev/null
    IS_AVAILABLE=$?

    if [[ 0 -ne "IS_AVAILABLE" ]]; then
        echo "$command is not installed. Please install it before running again."
        exit 1
    fi;
}

function check-dependencies {
    check-is-installed git
    check-is-installed composer
}

function install-repo {
    name="$1"
    git clone "$repo" "$dir$name"
    cd "$name"
    rm -rf .git
    rm -f ingbling.sh
}

function install-dependencies {
    composer install
    composer update
}

function create-config {
    file="ingbling.json"

    echo "{" > "$file"
    echo "  \"name\": \"$1\"," >> "$file"
    echo "  \"version\": \"$2\"," >> "$file"
    echo "  \"settings\": {" >> "$file"
    echo "    \"basedir\": \"$3\"," >> "$file"
    echo "    \"url\": \"$4\"," >> "$file"
    echo "    \"errors\": false" >> "$file"
    echo "  }," >> "$file"
    echo "  \"db\": {" >> "$file"
    echo "    \"host\": \"$5\"," >> "$file"
    echo "    \"port\": $6," >> "$file"
    echo "    \"name\": \"$7\"," >> "$file"
    echo "    \"user\": \"$8\"," >> "$file"
    echo "    \"password\": \"$9\"" >> "$file"
    echo "  }" >> "$file"
    echo "}" >> "$file"

    mv .htaccess-sample .htaccess
    sed -i "s@ingbling@$1@" .htaccess
    rm -f .htaccess-sample
}

function basedir-config {
    basedir="$1"

    if [[ "app" != "$basedir" && "app/" != "$basedir" ]]; then
        mkdir "$basedir"
        mv app/* "$basedir"/
        rm -rf app/
    fi;
}

function init {
    check-dependencies

    echo "Welcome to Ingbling project generator!"

    read -p "Project name: " name

    while [[ "" = "$name" ]]; do
        echo "You cannot leave the project name empty"
        read -p "Project name: " name
    done;

    read -p "Project version (1.0.0): " version

    if [[ "" = "$version" ]]; then
        version="1.0.0"
    fi;

    read -p "Development directory (app): " basedir

    if [[ "" = "$basedir" ]]; then
        basedir="app"
    fi;

    read -p "URL of your app (http://localhost/$name): " url

    if [[ "" = "$url" ]]; then
        url="http://localhost/$name"
    fi;

    read -p "Database host (localhost): " db_host

    if [[ "" = "$db_host" ]]; then
        db_host="localhost"
    fi;

    read -p "Database port (27017): " db_port

    if [[ "" = "$db_port" ]]; then
        db_port="27017"
    fi;

    read -p "Database name ($name): " db_name

    if [[ "" = "$db_name" ]]; then
        db_name="$name"
    fi;

    read -p "Database user: " db_user
    read -p "Database password: " db_password

    while [[ "" != "$db_user" && "" = "$db_password" ]]; do
        echo "Your password cannot be empty while a user has been defined"
        read -p "Database password: " db_password
    done;

    echo
    echo "Name: $name"
    echo "Version: $version"
    echo "Development directory: $basedir"
    echo "URL of the app: $url"
    echo "Database host: $db_host"
    echo "Database port: $db_port"
    echo "Database name: $db_name"
    echo "Database user: $db_user"
    echo "Database password: $db_password"
    echo

    read -p "Is this configuration ok? " -n 1 -r
    echo

    if [[ ^[Yy]$ =~ "$REPLY" || "" = "$REPLY" ]]; then
        install-repo "$name"
        install-dependencies
        create-config "$name" "$version" "$basedir" "$url" "$db_host" "$db_port" "$db_name" "$db_user" "$db_password"
        basedir-config "$basedir"

        echo
        echo -e "${GREEN}Congratulations! Your project has been successfully created!${NC}"
        echo
    else
        read -p "You choose to abort, wanna start over? " restart
        if [[ "" = "$restart" || "y" = "$restart" || "Y" = "$restart" || "yes" = "$restart" || "Yes" = "$restart" ]]; then
            ingbling init
        fi;
    fi;

    exit 1
}

function generate-controller {
    read -p "Controller name? (Home) " name

    if [[ "" = "$name" ]]; then
        name="Home"
    fi

    name="$(tr '[:upper:]' '[:lower:]' <<< ${name})"
    ucName="$(tr '[:lower:]' '[:upper:]' <<< ${name:0:1})${name:1}"
    cName="$ucName""Controller"

    controller="<?php"
    controller="$controller"$'\n'
    controller="$controller"$'\n/**'
    controller="$controller"$'\n * Class '"$cName."

    if [[ "home" = "$name" ]]; then
        controller="$controller"$'\n * [\PHPEasyAPI\Server]'
    else
        controller="$controller"$'\n * [\PHPEasyAPI\Server("'"$name"'")]'
    fi

    controller="$controller"$'\n */'
    controller="$controller"$'\nclass '"$cName"' extends Ingbling\MVC\ControllerBase'
    controller="$controller"$'\n{'
    controller="$controller"$'\n    /**'
    controller="$controller"$'\n     * Initialize here your controller.'
    controller="$controller"$'\n     */'
    controller="$controller"$'\n    public function init() { $this->Title = "'"$ucName"'"; }'
    controller="$controller"$'\n'
    controller="$controller"$'\n    /**'
    controller="$controller"$'\n     * Renders the main page of the controller.'
    controller="$controller"$'\n'"     * [\PHPEasyAPI\Enrichment\Endpoint"'(method = "GET", url = "")]'
    controller="$controller"$'\n     */'
    controller="$controller"$'\n    public function getIndex() { $this->render(); }'
    controller="$controller"$'\n}'

    echo "$controller" > "$1/controllers/$cName.php"

    echo -e "${GREEN}Controller $cName created successfully!${NC}"
    exit 1
}

function generate-collection {
    echo "collections"
}

function generate {
    if [[ ! -f ingbling.json ]]; then
        echo
        echo -e "${RED}Not an Ingbling project!${NC}"
        echo "Please use 'ingbling init' to start one."
        echo
        exit 1
    fi

    basedir=`cat ingbling.json|grep basedir|awk '{$1=$1};1'`

    if [[ "" = "$basedir" ]]; then
        basedir="app"
    else
         basedir="${basedir//\"basedir\": \"}"
         basedir="${basedir//\",}"
         basedir="${basedir///}"
    fi;

    echo "Choose what you want to generate:"
    echo "[0]: Controller"
    echo "[1]: Collection"
    read -p "Enter the corresponding number: " -n 1 -r
    echo

    case "$REPLY" in
        0)
            generate-controller "$basedir"
            ;;
        1)
            generate-collection
            ;;
    esac
}

function guide {
    echo
    echo "List of commands:"
    echo
    echo "     init | i:    Creates a new Ingbling project"
    echo "      gen | g:    Starts an interactive component generator"
    echo
    echo "--version | -v    Shows the Ingbling script version"
    echo "--help    | -h    Shows the guide"
    echo

    exit 1
}

case "$command" in
    init|i)
        init
        ;;
    gen|g)
        generate
        ;;
    --help|-h)
        guide
        ;;
    --version|-v)
        echo "Ingbling: app builder, version 1.0.0"
        ;;
    *)
        echo "No command found! Run 'ingbling --help' for more info."
        exit 1
        ;;
esac