<?php

namespace Gendiff\parsers;

use Symfony\Component\Yaml\Yaml;

function isJson($file)
{
    return(strpos($file, ".json") !== false || strpos($file, ".JSON") !== false);
}
function isYaml($file)
{
    return(strpos($file, ".yml") !== false || strpos($file, ".YML") !== false);
}

function parse($file)
{
    if (isJson($file)) {
        $data = file_get_contents($file, true);
        return json_decode($data, $assoc = true);
    }
    if (isYaml($file)) {
        $data = file_get_contents($file, true);
        return Yaml::parse($data);
    }
}
