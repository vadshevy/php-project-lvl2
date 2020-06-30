<?php

namespace Gendiff\genDiff;

use Funct\Collection;

use function Gendiff\render\render;
use function Gendiff\parsers\parse;
use function Gendiff\renderJson\renderJson;
use function Gendiff\renderPlain\renderPlain;
use function Gendiff\renderPretty\renderPretty;

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
                        return buildNode(['key' => $key,
                                'beforeValue' => $data1[$key],
                                'afterValue' => $data2[$key],
                                'children' => $buildDiff($data1[$key], $data2[$key]),
                                'type' => 'nested'
                                ]);
                    } elseif ($data1[$key] === $data2[$key]) {
                        return buildNode(['key' => $key,
                                'beforeValue' => $data1[$key],
                                'afterValue' => $data2[$key],
                                'type' => 'unchanged'
                                ]);
                    } else {
                        return buildNode(['key' => $key,
                                 'beforeValue' => $data1[$key],
                                'afterValue' => $data2[$key],
                                'type' => 'changed'
                                ]);
                    }
                }
                if (!array_key_exists($key, $data2)) {
                    return buildNode(['key' => $key,
                            'beforeValue' => $data1[$key],
                            'type' => 'removed'
                            ]);
                }
                if (!array_key_exists($key, $data1)) {
                    return buildNode(['key' => $key,
                            'afterValue' => $data2[$key],
                            'type' => 'added'
                            ]);
                }
            },
            $unionKeys
        );
    };

    return $buildDiff($data1, $data2);
}

function buildNode($nodeData)
{
    return array_merge(
        [
            'key' => null,
            'beforeValue' => null,
            'afterValue' => null,
            'children' => [],
            'type' => null
        ],
        $nodeData
    );
}

function getFormatter($format)
{
    return function ($diff) use ($format) {
        switch ($format) {
            case 'json':
                return renderJson($diff);
            case 'plain':
                return renderPlain($diff);
            case 'pretty':
                return renderPretty($diff);
            default:
                throw new \Exception("Invalid format {$format}. Try 'gendiff --help' for reference");
        }
    };
}
