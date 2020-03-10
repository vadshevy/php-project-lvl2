<?php

namespace Gendiff\index;

use function Gendiff\render\render;
use function Gendiff\gendiff\gendiff;

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
    -f --format <fmt>                Report format [default: pretty]
DOCOPT;

    $opts = \Docopt::handle($doc, array('version' => '0.1'));

    $result = gendiff($opts);

    print_r(render($result));
}
