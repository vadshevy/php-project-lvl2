<?php

namespace Gendiff\differ;

use Funct\Collection;

use function Gendiff\render\render;
use function Gendiff\parsers\parse;
use function Gendiff\formatters\getFormatter;

function genDiff($filePath1, $filePath2, $format)
{
    $data1 = parse(file_get_contents($filePath1), pathinfo($filePath1, PATHINFO_EXTENSION));
    $data2 = parse(file_get_contents($filePath2), pathinfo($filePath2, PATHINFO_EXTENSION));
    $diff = buildAST($data1, $data2);
    $formatDiff = getFormatter($format);
    return $formatDiff($diff);
}

function buildAST($data1, $data2)
{

    $buildDiff = function ($data1, $data2) use (&$buildDiff) {
        $unionKeys = array_values(Collection\union(array_keys($data1), array_keys($data2)));
        return array_map(
            function ($key) use (&$buildDiff, $data1, $data2) {
                if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                    if (is_array($data1[$key]) && is_array($data2[$key])) {
                        return buildNode($key, $data1[$key], $data2[$key], $buildDiff($data1[$key], $data2[$key]), 'nested');
                    } elseif ($data1[$key] === $data2[$key]) {
                        return buildNode($key, $data1[$key], $data2[$key], [], 'unchanged');
                    } else {
                        return buildNode($key, $data1[$key], $data2[$key], [], 'changed');
                    }
                }
                if (!array_key_exists($key, $data2)) {
                    return buildNode($key, $data1[$key], null, [], 'removed');
                }
                if (!array_key_exists($key, $data1)) {
                    return buildNode($key, null, $data2[$key], [], 'added');
                }
            },
            $unionKeys
        );
    };

    return $buildDiff($data1, $data2);
}

function buildNode($key, $beforeValue, $afterValue, $children, $type)
{
    return
        [
            'key' => $key,
            'beforeValue' => $beforeValue,
            'afterValue' => $afterValue,
            'children' => $children,
            'type' => $type
        ];
}
