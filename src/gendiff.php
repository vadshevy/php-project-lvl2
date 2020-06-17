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
    $outputFormat = getFormatter($format);
    return $outputFormat($diff);
}

function buildAST($data1, $data2)
{
    $unionKeys = Collection\union(array_keys($data1), array_keys($data2));
    return array_reduce(
        $unionKeys,
        function ($acc, $key) use ($data1, $data2) {
            if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                if (is_array($data1[$key]) && is_array($data2[$key])) {
                    $acc[] = ['key' => $key,
                            'beforeValue' => $data1[$key],
                            'afterValue' => $data2[$key],
                            'children' => buildAst($data1[$key], $data2[$key]),
                            'type' => 'nested'
                            ];
                } elseif ($data1[$key] === $data2[$key]) {
                        $acc[] = ['key' => $key,
                                'beforeValue' => $data1[$key],
                                'afterValue' => $data2[$key],
                                'children' => [],
                                'type' => 'unchanged'
                                ];
                } else {
                    if ($data1[$key] !== $data2[$key]) {
                        $acc[] = ['key' => $key,
                                 'beforeValue' => $data1[$key],
                                'afterValue' => $data2[$key],
                                'children' => [],
                                'type' => 'changed'
                                ];
                    }
                }
            }
            if (array_key_exists($key, $data1) && !array_key_exists($key, $data2)) {
                $acc[] = ['key' => $key,
                        'beforeValue' => $data1[$key],
                        'afterValue' => null,
                        'children' => [],
                        'type' => 'removed'
                        ];
            }
            if (!array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                $acc[] = ['key' => $key,
                        'beforeValue' => null,
                        'afterValue' => $data2[$key],
                        'children' => [],
                        'type' => 'added'
                        ];
            }
            return $acc;
        },
        []
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
        }
        return renderPretty($diff);
    };
}
