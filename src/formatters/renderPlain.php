<?php

namespace Gendiff\renderPlain;

use Funct\Collection;

function renderPlain($ast)
{

    $iter = function ($ast, $parentKey) use (&$iter) {
        return array_map(function ($node) use ($parentKey, $iter) {
            $before = stringify($node['beforeValue']);
            $after = stringify($node['afterValue']);
            $propertyName = "'{$parentKey}{$node['key']}'";
            switch ($node['type']) {
                case 'nested':
                    $parentKey = "{$node['key']}.";
                    return $iter($node['children'], $parentKey);
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
    $coll = Collection\flattenAll($iter($ast, ""));
    return implode("\n", $coll);
}

function stringify($value)
{
    return is_object($value) || is_array($value) ? "'complex value'" : "'$value'";
}
