<?php

namespace Gendiff\gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\render\render;
use function Gendiff\gendiff\gendiff;

class UserTest extends TestCase
{
    public function testJson()
    {
        $file1 = './tests/gendiffTest_file1.json';
        $file2 = './tests/gendiffTest_file2.json';
        $expected = file_get_contents('gendiffTest_expected', true);
        $this->assertSame($expected, render(gendiff($file1, $file2)));
    }

    public function testYaml()
    {
        $file1 = './tests/gendiffTest_file1.yml';
        $file2 = './tests/gendiffTest_file2.yml';
        $expected = file_get_contents('gendiffTest_expected', true);
        $this->assertSame($expected, render(gendiff($file1, $file2)));
    }
}