#!/usr/bin/env bash

command="$1"
repo="https://github.com/ThomasSquall/Ingbling.git"
dir=$(pwd)/

function check-is-installed {
    command="$1"
    "$command" --version 2>&1 >/dev/null
    IS_AVAILABLE=$?

    if [[ 0 -ne "IS_AVAILABLE" ]]; then
        echo "$command is not installed. Please install it before running again"
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
    rm ingbling.sh
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

    if [[ "" != $3 ]]; then
        echo "    \"basedir\": \"$3\"," >> "$file"
    fi;

    echo "    \"url\": \"$4\"," >> "$file"

    echo "  }" >> "$file"
    echo "}" >> "$file"

    cp config-sample.php config.php

    sed -i "s@ingbling@$1@" defines.php

    mv .htaccess-sample .htaccess
    sed -i "s@ingbling@$1@" .htaccess
    cp .htaccess .htaccess-sample
}

function basedir-config {
    basedir="$1"

    if [[ "" != "$basedir" ]]; then
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

    read -p "URL of your app: " url

    while [[ "" = "$url" ]]; do
        echo "You cannot leave the URL empty"
        read -p "URL of your app: " url
    done;

    echo ""
    echo "Name: $name"
    echo "Version: $version"
    echo "Development directory: $basedir"
    echo "URL of the app: $url"
    echo ""

    read -p "Is this configuration ok? " accept

    if [[ "" = "$accept" || "y" = "$accept" || "Y" = "$accept" || "yes" = "$accept" || "Yes" = "$accept" ]]; then
        install-repo "$name"
        install-dependencies
        create-config "$name" "$version" "$basedir" "$url"
        basedir-config "$basedir"
    else
        read -p "You choose to abort, wanna start over? " restart
        if [[ "" = "$restart" || "y" = "$restart" || "Y" = "$restart" || "yes" = "$restart" || "Yes" = "$restart" ]]; then
            ingbling init
        fi;
        exit 1
    fi;
}

function generate {
    if [[ ! -f ingbling.json ]]; then
        echo "Not an Ingbling project! Please use 'ingbling init' to start one."
        exit 1
    fi


}

function guide {
    echo ""
    echo "List of commands:"
    echo ""
    echo "     init | i:    Creates a new Ingbling project"
    echo "      gen | g:    Starts an interactive component generator"
    echo ""
    echo "--version | -v    Shows the Ingbling script version"
    echo "--help    | -h    Shows the guide"
    echo ""

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