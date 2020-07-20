<?php

namespace Gendiff\renderPretty;

function renderPretty($ast)
{
    $iter = function ($coll, $level) use (&$iter) {
        $mapped = array_map(function ($node) use ($level, $iter) {
            $indent = getIndent($level);
            $indentChanged = str_repeat(' ', 4 * $level - 2);
            $formattedAfterValue = stringify($node['afterValue'], $level);
            $formattedBeforeValue = stringify($node['beforeValue'], $level);
            switch ($node['type']) {
                case 'nested':
                    $result =  $iter($node['children'], $level + 1);
                    return "{$indent}{$node['key']}: {\n{$result}\n{$indent}}";
                case 'changed':
                    $lines =  [
                        "{$indentChanged}- {$node['key']}: {$formattedBeforeValue}",
                        "{$indentChanged}+ {$node['key']}: {$formattedAfterValue}"
                    ];
                    return implode("\n", $lines);
                case 'unchanged':
                    return "{$indent}{$node['key']}: {$formattedBeforeValue}";
                case 'added':
                    return "{$indentChanged}+ {$node['key']}: {$formattedAfterValue}";
                case 'removed':
                    return "{$indentChanged}- {$node['key']}: {$formattedBeforeValue}";
                default:
                    throw new \Exception("Unknown type: {$node['type']}");
            }
        }, $coll);
        return implode("\n", $mapped);
    };
    $result = $iter($ast, 1);
    return "{\n{$result}\n}";
}

function getIndent($level)
{
    return str_repeat(' ', 4 * $level);
}

function stringify($value, $level)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (!is_array($value) && !is_object($value)) {
        return $value;
    }
    $keys = array_keys(get_object_vars($value));
    $indent = getIndent($level + 1);
    $bracketIndent = getIndent($level);

    $mapped = array_map(function ($key) use ($value, $level, &$indent) {
        $formattedValue = stringify($value->$key, $level);
        return "{$key}: {$formattedValue}";
    }, $keys);
    $result = implode("\n", $mapped);
    return "{\n{$indent}{$result}\n{$bracketIndent}}";
}
