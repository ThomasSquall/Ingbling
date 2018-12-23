#!/usr/bin/env bash

command="$1"
repo="https://github.com/ThomasSquall/Ingbling.git"
dir=$(pwd)/

function check-dependencies {
    git --version 2>&1 >/dev/null
    GIT_IS_AVAILABLE=$?

    if [[ 0 -ne "$GIT_IS_AVAILABLE" ]]; then
        echo "git is not installed. Please install it before running again"
        exit 1
    fi;

    composer -v 2>&1 >/dev/null
    COMPOSER_IS_AVAILABLE=$?

     if [[ 0 -ne "$COMPOSER_IS_AVAILABLE" ]]; then
        echo "composer is not installed. Please install it before running again"
        exit 1
    fi;
}

function install-repo {
    name="$1"
    git clone "$repo" "$dir$name"
    cd "$name"
    rm -rf .git
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
        echo "    \"basedir\": \"$3\"" >> "$file"
    fi;

    echo "  }" >> "$file"
    echo "}" >> "$file"
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

    echo Welcome to Ingbling project generator!

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

    echo ""
    echo Name: "$name"
    echo Version: "$version"
    echo Development directory: "$basedir"
    echo ""

    read -p 'Do you accept? ' accept

    if [[ "" = "$accept" || "y" = "$accept" || "Y" = "$accept" || "yes" = "$accept" || "Yes" = "$accept" ]]; then
        install-repo "$name"
        install-dependencies
        create-config "$name" "$version" "$basedir"
        basedir-config "$basedir"
    else
        exit 1
    fi;
}

function guide {
    echo ""
    echo "List of commands:"
    echo ""
    echo "init | i:    Creates a new Ingbling project"
    echo "gen  | g:    Starts an interactive component generator"
    echo ""

    exit 1
}

case "$command" in
    init|i)
        init
        ;;
    --help)
        guide
        ;;
    *)
        echo "No command found! Run 'ingbling --help' for more info."
        exit 1
        ;;
esac