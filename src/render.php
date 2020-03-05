<?php

namespace Gendiff\render;

use function cli\line;
use function cli\prompt;

function render($coll)
{
    line("{");
    array_map(function ($row) {
        print_r("  {$row['state']} {$row['key']}: {$row['value']}\n");
    }, $coll);
    line("{");
}
