<?php

namespace Gendiff\renderPretty;

use Funct\Collection;

const INDENT = "    ";
const INDENT_MINUS = "  - ";
const INDENT_PLUS = "  + ";

function renderPretty($ast)
{
    $render = function ($coll, $depth) use (&$render) {
        return array_map(function ($node) use ($depth, $render) {
            $key = $node['key'];
            $beforeValue = $node['beforeValue'];
            $afterValue = $node['afterValue'];
            $children = $node['children'];
            switch ($node['type']) {
                case 'nested':
                    return [
                        makeIndentation($depth + 1) . "{$key}: {",
                        $render($children, $depth + 1),
                        makeIndentation($depth + 1) . "}"
                    ];
                case 'changed':
                    return [
                        stringify($key, $beforeValue, $depth, INDENT_MINUS),
                        stringify($key, $afterValue, $depth, INDENT_PLUS)
                    ];
                case 'unchanged':
                    return stringify($key, $beforeValue, $depth, INDENT);
                case 'added':
                    return stringify($key, $afterValue, $depth, INDENT_PLUS);
                case 'removed':
                    return stringify($key, $beforeValue, $depth, INDENT_MINUS);
            }
        }, $coll);
    };
    return implode(PHP_EOL, array_merge(['{'], Collection\flattenAll($render($ast, 0)), ['}']));
}

function stringify($key, $value, $depth, $type = "")
{
    if (!is_array($value)) {
        $data = is_bool($value) ? var_export($value, true) : $value;
        $indentation = makeIndentation($depth);
        return "{$indentation}{$type}{$key}: {$data}";
    } else {
        $data = array_map(function ($key) use ($value, $depth) {
            return stringify($key, $value[$key], $depth + 2);
        }, array_keys($value));
        return [
            makeIndentation($depth) . "{$type}{$key}: {",
            $data,
            makeIndentation($depth + 1) . "}"
        ];
    }
}

function makeIndentation($depth)
{
    return str_repeat(INDENT, $depth);
}
