<?php

namespace Gendiff\renderPretty;

use function cli\line;
use function cli\prompt;
function renderPretty($ast)
{
    $render = function($coll, $depth = 0) use (&$render) {
        $renderLines = function($node) {
            if (is_array($node)) {
                return array_reduce(array_keys($node), function ($acc, $n) use ($node) {
                    $offset = str_repeat("    ", 2);
                    $value = is_bool($node[$n]) ? var_export($node[$n], true) : $node[$n];
                    $acc .= "{$offset}{$n}: {$value}\n";
                    return $acc;
                }, "");
            } else {
                return $node;
            }
        };
        $result = array_reduce($coll, function($acc, $node) use ($renderLines, $render, $depth) {
            $offset = str_repeat("    ", $depth);

            $beforeValue = is_bool($node['beforeValue']) ? var_export($node['beforeValue'], true) : $node['beforeValue'];
            $afterValue = is_bool($node['afterValue']) ? var_export($node['afterValue'], true) : $node['afterValue'];

            if ($node['type'] === 'added') {
                if (!is_array($node['afterValue'])) {
                    $acc .= "{$offset}  + {$node['key']}: {$afterValue}\n";
                } else {
                    $acc .= "{$offset}  + {$node['key']}: {\n";
                    $acc .= "{$offset}{$renderLines($node['afterValue'])}";
                    $acc .= "{$offset}    }\n";
                }
            }
            if ($node['type'] === 'removed') {
                if (!is_array($node['beforeValue'])) {
                    $acc .= "{$offset}  - {$node['key']}: {$beforeValue}\n";
                } else {
                    $acc .= "{$offset}  - {$node['key']}: {\n";
                    $acc .= "{$offset}{$renderLines($node['beforeValue'])}";
                    $acc .= "{$offset}    }\n";
                }
            }
            if ($node['type'] === 'unchanged') {
                if (!is_array($node['beforeValue'])) {
                    $acc .= "{$offset}    {$node['key']}: {$beforeValue}\n";
                } else {
                    $acc .= "{$offset}    {$node['key']}: {\n";
                    $acc .= "{$offset}{$renderLines($node['beforeValue'])}";
                    $acc .= "{$offset}    }\n";
                }
            } 
            if($node['type'] === 'changed') {
                if (!is_array($node['beforeValue'])) {
                    $acc .= "{$offset}  - {$node['key']}: {$beforeValue}\n";
                    $acc .= "{$offset}  + {$node['key']}: {$afterValue}\n";
                } else {
                    $depth += 1;
                    $acc .= "{$offset}    {$node['key']}: {\n";
                    $acc .= "{$offset}{$render($node['children'][0], $depth)}";
                    $acc .= "{$offset}    }\n";
                }
            } 
            return $acc;
        }, "");
        return $result;
    };
    $result = "{\n{$render($ast)}}";
    return $result;
}