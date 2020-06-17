<?php

namespace Gendiff\renderPlain;

function renderPlain($ast)
{
    $getNestedData = function ($node) {
        $key = $node['key'];
        $beforeValue = is_array($node['beforeValue']) ? "complex value" : $node['beforeValue'];
        $afterValue = is_array($node['afterValue']) ? "complex value" : $node['afterValue'];
        $result = ['key' => $key, 'beforeValue' => $beforeValue, 'afterValue' => $afterValue];
        return $result;
    };

    $renderPlain = function ($coll, $parentKey = '') use (&$renderPlain, $getNestedData) {
        return array_reduce($coll, function ($acc, $node) use ($renderPlain, $parentKey, $getNestedData) {
            switch ($node['type']) {
                case 'nested':
                    $parentKey = "{$node['key']}.";
                    $acc .= $renderPlain(array_values($node['children']), $parentKey);
                    break;
                case 'changed':
                    $afterValue = $getNestedData($node)['afterValue'];
                    $beforeValue = $getNestedData($node)['beforeValue'];
                    $acc .= "Property '{$parentKey}{$node['key']}' was changed. From '{$beforeValue}' to '{$afterValue}'\n";
                    break;
                case 'added':
                    $afterValue = $getNestedData($node)['afterValue'];
                    $acc .= "Property '{$parentKey}{$node['key']}' was added with value: '{$afterValue}'\n";
                    break;
                case 'removed':
                    $acc .= "Property '{$parentKey}{$node['key']}' was removed\n";
                    break;
            }
            return $acc;
        }, "");
    };
    $result = $renderPlain($ast);
    return $result;
}
