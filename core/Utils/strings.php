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