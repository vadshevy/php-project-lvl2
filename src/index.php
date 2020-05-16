<?php

namespace Gendiff\index;

use function Gendiff\renderPretty\renderPretty;
use function Gendiff\renderPlain\renderPlain;
use function Gendiff\renderJson\renderJson;
use function Gendiff\gendiff\gendiff;
use function Gendiff\parsers\parse;

function index()
{
    $doc = <<<'DOCOPT'
    Generate diff

    Usage:
    gendiff (-h|--help)
    gendiff (-v|--version)
    gendiff [--format <fmt>] <firstFile> <secondFile>

    Options:
    -h --help                     Show this screen
    -v --version                  Show version
    -f --format <fmt>             Report format [default: pretty]. Available formats: pretty, plain, json
DOCOPT;

    $opts = \Docopt::handle($doc, array('version' => '0.1'));
    $file1 = $opts['<firstFile>'];
    $file2 = $opts['<secondFile>'];
    $format = $opts['--format'];
    $coll1 = parse($file1);
    $coll2 = parse($file2);
    $ast = gendiff($coll1, $coll2);
    if ($format === 'plain') {
        print_r(renderPlain($ast));
    } elseif ($format === 'json' || $format === 'JSON') {
        print_r(renderJson($ast));
    } else {
        print_r(renderPretty($ast));
    }
}
