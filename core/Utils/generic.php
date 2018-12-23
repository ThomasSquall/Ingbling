<?php

/**
 * Build a new array out of the $options one with defaults values taken
 * from $defaults when nothing is found.
 * @param array $options
 * @param array $defaults
 * @return array
 */
function parse_args($options, $defaults)
{
    if (is_null($options)) $options = [];
    elseif (is_object($options)) $options = (array)$options;
    elseif (!is_array($options)) $options = [];

    if (is_null($defaults)) $defaults = [];
    elseif (is_object($defaults)) $defaults = (array)$defaults;
    elseif (!is_array($defaults)) $defaults = [];

    foreach ($defaults as $k=>$v)
        if (is_null($v))
            unset($defaults[$k]);

    foreach ($options as $k=>$v)
        if (isset($defaults[$k]))
            if (is_null($v))
                unset($options[$k]);
            elseif (is_string($v) && ($v==='') && isset($defaults[$k]) && is_array($defaults[$k]))
                unset($options[$k]);
            else
            {
                if (is_array($v))
                {
                    $recursiveDefaults=$defaults[$k];
                    $options[$k]=parse_args($v, $recursiveDefaults);
                }

                unset($defaults[$k]);
            }

    foreach ($defaults as $k=>$v)
        $options[$k]=$v;

    return $options;
}