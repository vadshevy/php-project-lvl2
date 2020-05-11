<?php

namespace Gendiff\gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\render\render;
use function Gendiff\gendiff\gendiff;
use function Gendiff\parsers\parse;

class UserTest extends TestCase
{
    public function testJsonFlat()
    {
        $file1 = './tests/gendiffTest_file1.json';
        $file2 = './tests/gendiffTest_file2.json';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('gendiffTestFlat_expected', true);
        $this->assertSame($expected, render(gendiff($content1, $content2)));
    }

    public function testJson()
    {
        $file1 = './tests/gendiffTest_file3.json';
        $file2 = './tests/gendiffTest_file4.json';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('gendiffTest_expected', true);
        $this->assertSame($expected, render(gendiff($content1, $content2)));
    }

    public function testYamlFlat()
    {
        $file1 = './tests/gendiffTest_file1.yml';
        $file2 = './tests/gendiffTest_file2.yml';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('gendiffTestFlat_expected', true);
        $this->assertSame($expected, render(gendiff($content1, $content2)));
    }
}