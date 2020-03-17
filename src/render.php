<?php

namespace Gendiff\render;

use function cli\line;
use function cli\prompt;

/*
function render($coll)
{
    line("{");
    array_map(function ($row) {
        line("  {$row['state']} {$row['key']}: {$row['value']}");
    }, $coll);
    line("}");
}
*/
function render($coll)
{
    $result = "";
    array_map(function ($row) use (&$result) {
        $result .= "  {$row['state']} {$row['key']}: {$row['value']}\n";
    }, $coll);
    return "{\n {$result}}";
}
