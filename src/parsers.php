<?php

namespace Gendiff\parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $dataType)
{
    switch ($dataType) {
        case 'json':
            return parseJson($data);
            break;
        case 'yml' || 'yaml':
            return parseYaml($data);
        break;
    }
}

function parseJson($data)
{
    return json_decode($data, $assoc = true);
}

function parseYaml($data)
{
    return Yaml::parse($data);
}
