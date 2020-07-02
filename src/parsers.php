<?php

namespace Gendiff\parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $dataType)
{
    switch ($dataType) {
        case 'json':
            return json_decode($data, $assoc = true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($data);
    }
}
