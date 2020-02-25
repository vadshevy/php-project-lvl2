<?php

namespace Gendiff\Gendiff;

function gendiff()
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
    --format <fmt>                Report format [default: pretty]
DOCOPT;

    $result = \Docopt::handle($doc, array('version' => '0.1'));
    foreach ($result as $k => $v) {
        echo $k . ': ' . json_encode($v) . PHP_EOL;
    }
}
