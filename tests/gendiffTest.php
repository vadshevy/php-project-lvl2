<?php

namespace Gendiff\gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\renderPretty\renderPretty;
use function Gendiff\renderPlain\renderPlain;
use function Gendiff\renderJson\renderJson;
use function Gendiff\gendiff\gendiff;
use function Gendiff\parsers\parse;

class UserTest extends TestCase
{
    public function testJsonPretty()
    {
        $file1 = './tests/fixtures/before.json';
        $file2 = './tests/fixtures/after.json';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('./tests/fixtures/gendiffTestPretty_expected', true);
        $this->assertSame($expected, renderPretty(gendiff($content1, $content2)));
    }

    public function testJsonPlain()
    {
        $file1 = './tests/fixtures/before.json';
        $file2 = './tests/fixtures/after.json';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('./tests/fixtures/gendiffTestPlain_expected', true);
        $this->assertSame($expected, renderPlain(gendiff($content1, $content2)));
    }

    public function testJsonJSON()
    {
        $file1 = './tests/fixtures/before.json';
        $file2 = './tests/fixtures/after.json';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('./tests/fixtures/gendiffTestJson_expected', true);
        $this->assertSame($expected, renderJson(gendiff($content1, $content2)));
    }
/*
    public function testYmlPretty()
    {
        $file1 = './tests/fixtures/before.yml';
        $file2 = './tests/fixtures/after.yml';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('./tests/fixtures/gendiffTestPretty_expected', true);
        $this->assertSame($expected, renderPretty(gendiff($content1, $content2)));
    }

    public function testYmlPlain()
    {
        $file1 = './tests/fixtures/before.yml';
        $file2 = './tests/fixtures/after.yml';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('./tests/fixtures/gendiffTestPlain_expected', true);
        $this->assertSame($expected, renderPlain(gendiff($content1, $content2)));
    }

    public function testYmlJSON()
    {
        $file1 = './tests/fixtures/before.yml';
        $file2 = './tests/fixtures/after.yml';
        $content1 = parse($file1);
        $content2 = parse($file2);
        $expected = file_get_contents('./tests/fixtures/gendiffTestJson_expected', true);
        $this->assertSame($expected, renderJson(gendiff($content1, $content2)));
    }
*/
}
