<?php

namespace Gendiff\renderPlain;

use Funct\Collection;

function renderPlain($ast)
{

    $render = function ($ast, $parentKey) use (&$render) {
        return array_map(function ($node) use ($parentKey, $render) {
            $before = stringify($node['beforeValue']);
            $after = stringify($node['afterValue']);
            $propertyName = "'{$parentKey}{$node['key']}'";
            switch ($node['type']) {
                case 'nested':
                    $parentKey = "{$node['key']}.";
                    return $render($node['children'], $parentKey);
                case 'changed':
                    return "Property {$propertyName} was changed. From {$before} to {$after}";
                case 'added':
                    return "Property {$propertyName} was added with value: {$after}";
                case 'removed':
                    return "Property {$propertyName} was removed";
                case 'unchanged':
                    return [];
            }
        }, $ast);
    };
    $coll = Collection\flattenAll($render($ast, ""));
    return implode(PHP_EOL, $coll);
}

function stringify($value)
{
    return is_object($value) || is_array($value) ? "'complex value'" : "'$value'";
}
