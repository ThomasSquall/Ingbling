#!/usr/bin/env bash

repo="https://github.com/ThomasSquall/Ingbling.git"
dir=$(pwd)

echo Welcome to Ingbling project generator!

read -p 'Project name: ' name
read -p 'Project version (1.0.0): ' version

echo Name: "$name"
echo Version: "$version"

read -p 'Do you accept? ' accept

if [[  "y" = "$accept" || "" = "$accept" || "Y" = "$accept" || "yes" = "$accept" || "Yes" = "$accept" ]]; then
    install
else
    exit
fi;

function install {
    git clone "$repo" "$dir$name"
}