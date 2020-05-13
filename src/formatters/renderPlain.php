<?php

namespace Gendiff\renderPlain;

function renderPlain($ast)
{
    function getNestedData($node) {
        $key = $node['key'];
        $beforeValue = is_array($node['beforeValue']) ? "complex value" : $node['beforeValue'];
        $afterValue = is_array($node['afterValue']) ? "complex value" : $node['afterValue'];
        $result = ['key' => $key, 'beforeValue' => $beforeValue, 'afterValue' => $afterValue];
        return $result;
    }

    $renderPlain = function($coll, $parentKey = '') use (&$renderPlain, &$getNestedData) {
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
        /*
        function getNestedData($node) 
        {
            $key = $node['key'];
            $beforeValue = is_array($node['beforeValue']) ? "complex value" : $node['beforeValue'];
            $afterValue = is_array($node['afterValue']) ? "complex value" : $node['afterValue'];
            $result = ['key' => $key, 'beforeValue' => $beforeValue, 'afterValue' => $afterValue];
            var_dump($result);
            return $result;
        }

        $result = array_reduce($coll, function($acc, $node) use ($render) {

            $beforeValue = is_bool($node['beforeValue']) ? var_export($node['beforeValue'], true) : $node['beforeValue'];
            $afterValue = is_bool($node['afterValue']) ? var_export($node['afterValue'], true) : $node['afterValue'];

            if ($node['type'] === 'added') {
                if (!is_array($node['afterValue'])) {
                    $acc .= "Property '{$node['key']}' was added with value: {$afterValue}\n";
                } else {
                    $nestedData = getNestedData($node);
                    $acc .= "Property {$node['key']}.{$nestedData['key']} was added with value: {$nestedData['afterValue']}\n";
                }
            }
            if ($node['type'] === 'removed') {
                if (!is_array($node['beforeValue'])) {
                    $acc .= "Property '{$node['key']}' was removed\n";
                } else {
                    $nestedData = getNestedData($node);
                    $acc .= "Property {$node['key']}.{$nestedData['key']} was removed\n";
                }
            }
            if($node['type'] === 'changed') {
                if (!is_array($node['beforeValue'])) {
                    $acc .= "Property '{$node['key']}' was changed. From '{$node['beforeValue']}' to '{$node['afterValue']}'\n";
                } else {
                    $acc .= "Property '{}' was changed. From '{}' to '{}'\n";
                }
            } 
            return $acc;
        }, "");
        return $result;
    };
    */
    $result = $renderPlain($ast);
    return $result;
}