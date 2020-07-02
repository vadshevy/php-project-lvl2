<?php

namespace Gendiff\formatters;

use function Gendiff\renderJson\renderJson;
use function Gendiff\renderPlain\renderPlain;
use function Gendiff\renderPretty\renderPretty;

function getFormatter($format)
{
    return function ($diff) use ($format) {
        switch ($format) {
            case 'json':
                return renderJson($diff);
            case 'plain':
                return renderPlain($diff);
            case 'pretty':
                return renderPretty($diff);
            default:
                throw new \Exception("Invalid format {$format}. Try 'gendiff --help' for reference");
        }
    };
}
