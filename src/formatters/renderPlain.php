<?php

namespace Gendiff\renderPlain;

function renderPlain($ast)
{
    function getNestedData($node)
    {
        $key = $node['key'];
        $beforeValue = is_array($node['beforeValue']) ? "complex value" : $node['beforeValue'];
        $afterValue = is_array($node['afterValue']) ? "complex value" : $node['afterValue'];
        $result = ['key' => $key, 'beforeValue' => $beforeValue, 'afterValue' => $afterValue];
        return $result;
    }

    $renderPlain = function ($coll, $parentKey = '') use (&$renderPlain, &$getNestedData) {
        return array_reduce($coll, function ($acc, $node) use ($renderPlain, $parentKey) {

            if ($node['type'] === 'added') {
                $afterValue = getNestedData($node)['afterValue'];
                $acc .= "Property '{$parentKey}{$node['key']}' was added with value: '{$afterValue}'\n";
            }
            if ($node['type'] === 'removed') {
                $acc .= "Property '{$parentKey}{$node['key']}' was removed\n";
            }
            if ($node['type'] === 'changed') {
                if (empty($node['children'])) {
                    $afterValue = getNestedData($node)['afterValue'];
                    $beforeValue = getNestedData($node)['beforeValue'];
                    $acc .= "Property '{$parentKey}{$node['key']}' was changed. From '{$beforeValue}' to '{$afterValue}'\n";
                } else {
                    $parentKey = "{$node['key']}.";
                    $acc .= $renderPlain(array_values($node['children'][0]), $parentKey);
                }
            }
            return $acc;
        }, "");
    };
    $result = $renderPlain($ast);
    return $result;
}
