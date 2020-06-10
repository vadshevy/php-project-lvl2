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
        $expected = file_get_contents($this->getFixturePath("diff.{$outputFormat}"));
        $file1 = $this->getFixturePath("before.{$fileExtension}");
        $file2 = $this->getFixturePath("after.{$fileExtension}");
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

    private function getFixturePath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode(DIRECTORY_SEPARATOR, $parts));
    }
}
