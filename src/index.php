<?php

namespace Gendiff\index;

use function Gendiff\render\render;
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
    -f --format <fmt>             Report format [default: pretty]
DOCOPT;

    $opts = \Docopt::handle($doc, array('version' => '0.1'));
    $file1 = $opts['<firstFile>'];
    $file2 = $opts['<secondFile>'];
    $format = $opts['--format'];
    $coll1 = parse($file1);
    $coll2 = parse($file2);
    $ast = gendiff($coll1, $coll2);
    print_r(render($ast));
}
