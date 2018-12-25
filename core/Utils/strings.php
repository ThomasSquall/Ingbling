<?php

/**
 * Returns true if the $haystack string starts with the $needle string
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function starts_with($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * Returns true if the $haystack string ends with the $needle string
 * @param string $haystack
 * @param string $needle
 * @return string bool
 */
function ends_with($haystack, $needle)
{
    $length = strlen($needle);
    return $length == 0 || (substr($haystack, -$length) === $needle);
}

function string_between($string, $start, $end)
{
    $result = false;

    $string = ' ' . $string;
    $ini = strpos($string, $start);

    if ($ini != 0)
    {
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        $result = substr($string, $ini, $len);
    }

    return $result;
}


function strings_between($string, $start, $end)
{
    $s = string_between($string, $start, $end);

    $result = [];

    while (is_string($s))
    {
        $result[] = $s;
        $string = replace_tokens($string, ["$start$s$end" => "----$$$$$$$----"]);
        $s = string_between($string, $start, $end);
    }

    return $result;
}

function string_contains($where, $find)
{
    return strpos($where, $find) !== false;
}

function replace_tokens($text, array $replace)
{
    foreach ($replace as $token => $value)
    {
        $text = str_replace($token, $value, $text);
    }

    return $text;
}