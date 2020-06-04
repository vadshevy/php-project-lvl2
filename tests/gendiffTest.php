<?php

namespace Gendiff\gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\gendiff\gendiff;

class UserTest extends TestCase
{
    /**
     * @dataProvider dataSet
     */

    public function testGenDiff($fileExtension, $outputFormat)
    {
        $file1 = $this->getFixtureBefore($fileExtension);
        $file2 = $this->getFixtureAfter($fileExtension);
        $expected = file_get_contents($this->getFixtureExpected($outputFormat), true);
        $diff = gendiff($file1, $file2, $outputFormat);
        $this->assertSame($expected, $diff);
    }

    public function dataSet()
    {
        return [
            ['json','pretty'],
            ['json','plain'],
            ['json','json'],
            ['yml','pretty'],
//            ['yml','plain'],
            ['yml','json']
        ];
    }

    private function getFixtureDir()
    {
        return 'tests/fixtures/';
    }

    private function getFixtureBefore($fileExtension)
    {
        return "{$this->getFixtureDir()}/before.{$fileExtension}";
    }

    private function getFixtureAfter($fileExtension)
    {
        return "{$this->getFixtureDir()}/after.{$fileExtension}";
    }

    private function getFixtureExpected($outputFormat)
    {
        return "{$this->getFixtureDir()}/diff.{$outputFormat}";
    }
}
