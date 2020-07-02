<?php

namespace Gendiff\renderPretty;

use Funct\Collection;

function renderPretty($ast)
{
    $iter = function ($coll, $level) use (&$iter) {
        return array_map(function ($node) use ($level, $iter) {
            $indent = str_repeat(' ', 4 * $level);
            $indentChanged = str_repeat(' ', 4 * $level - 2);
            $formattedAfterValue = stringify($node['afterValue'], $level);
            $formattedBeforeValue = stringify($node['beforeValue'], $level);

            switch ($node['type']) {
                case 'nested':
                    $result = implode("\n", $iter($node['children'], $level + 1));
                    return "{$indent}{$node['key']}: {\n{$result}\n{$indent}}";
                case 'changed':
                    $lines =  [
                        "{$indentChanged}- {$node['key']}: {$formattedBeforeValue}",
                        "{$indentChanged}+ {$node['key']}: {$formattedAfterValue}"
                    ];
                    return implode("\n", $lines);
                case 'unchanged':
                    if (!is_array($node['beforeValue'])) {
                        return "{$indent}{$node['key']}: {$formattedBeforeValue}";
                    } else {
                        $value = processCollection($node['beforeValue'], $level + 1);
                        return "{$indent}{$node['key']}: {\n{$value}\n{$indent}}";
                    }
                case 'added':
                    if (!is_array($node['afterValue'])) {
                        return "{$indentChanged}+ {$node['key']}: {$formattedAfterValue}";
                    } else {
                        $value = processCollection($node['afterValue'], $level + 1);
                        return "{$indentChanged}+ {$node['key']}: {\n{$value}\n{$indent}}";
                    }
                case 'removed':
                    if (!is_array($node['beforeValue'])) {
                        return "{$indentChanged}- {$node['key']}: {$formattedBeforeValue}";
                    } else {
                        $value = processCollection($node['beforeValue'], $level + 1);
                        return "{$indentChanged}- {$node['key']}: {\n{$value}\n{$indent}}";
                    }
            }
        }, $coll);
    };
    $result = implode("\n", ($iter($ast, 1)));
    return "{\n{$result}\n}";
}

function stringify($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}

function processCollection($value, $level)
{
    $indent = str_repeat(' ', 4 * $level);
    $data = array_map(function ($key) use ($value, $indent) {
        $value = stringify($value[$key]);
        return "{$indent}{$key}: {$value}";
    }, array_keys($value));
    return implode("\n", $data);
}
