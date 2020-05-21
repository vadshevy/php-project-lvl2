<?php

namespace Gendiff\parsers;

use Symfony\Component\Yaml\Yaml;

function isJson($file)
{
    return(substr($file, -4) === "json" || strpos($file, -4) === "JSON");
}
function isYaml($file)
{
    return(substr($file, -3) === "yml" || substr($file, -3) === "YML");
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
