<?php

namespace Gendiff\gendiff;

use function Gendiff\render\render;
use function Gendiff\parsers\parse;
use function Gendiff\renderJson\renderJson;
use function Gendiff\renderPlain\renderPlain;
use function Gendiff\renderPretty\renderPretty;

function gendiff($file1, $file2, $format)
{
    $data1 = parse($file1);
    $data2 = parse($file2);
    $diff = buildAST($data1, $data2);
    $renderJson = 'renderJson';
    $renderPlain = 'renderPlain';
    $renderPretty = 'renderPretty';
    if ($format === 'json') {
        return renderJson($diff);
    }
    if ($format === 'plain') {
        return renderPlain($diff);
    }
    return renderPretty($diff);
}

function buildAST($coll1, $coll2)
{
    $merged = array_merge($coll1, $coll2);

    return array_reduce(
        array_keys($merged),
        function ($acc, $key) use ($coll1, $coll2, $merged) {
            if (array_key_exists($key, $coll1) && array_key_exists($key, $coll2)) {
                if ($coll1[$key] === $coll2[$key]) {
                    $acc[] = ['key' => $key, 'beforeValue' => $coll1[$key], 'afterValue' => $coll2[$key], 'children' => [], 'type' => 'unchanged'];
                }
                if ($coll1[$key] !== $coll2[$key]) {
                    if (is_array($coll1[$key]) && is_array($coll2[$key])) {
                        $acc[] = ['key' => $key, 'beforeValue' => $coll1[$key], 'afterValue' => $coll2[$key], 'children' => [buildAst($coll1[$key], $coll2[$key])], 'type' => 'changed'];
                    } else {
                        $acc[] = ['key' => $key, 'beforeValue' => $coll1[$key], 'afterValue' => $coll2[$key], 'children' => [], 'type' => 'changed'];
                    }
                }
            }
            if (array_key_exists($key, $coll1) && !array_key_exists($key, $coll2)) {
                $acc[] = ['key' => $key, 'beforeValue' => $coll1[$key], 'afterValue' => null, 'children' => [], 'type' => 'removed'];
            }
            if (!array_key_exists($key, $coll1) && array_key_exists($key, $coll2)) {
                $acc[] = ['key' => $key, 'beforeValue' => null, 'afterValue' => $coll2[$key], 'children' => [], 'type' => 'added'];
            }
            return $acc;
        },
        []
    );
}
