<?php

namespace Gendiff\gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\gendiff\gendiff;

class UserTest extends TestCase
{
    /**
     * @dataProvider dataSet
     */

    public function testGenDiff($fileExtension1, $fileExtension2, $outputFormat)
    {
        $expected = file_get_contents($this->getFixturePath("diff.{$outputFormat}"));
        $diff1 = gendiff($this->getFixturePath("before.{$fileExtension1}"), $this->getFixturePath("after.{$fileExtension1}"), $outputFormat);
        $diff2 = gendiff($this->getFixturePath("before.{$fileExtension2}"), $this->getFixturePath("after.{$fileExtension2}"), $outputFormat);
        $this->assertSame($expected, $diff1);
        $this->assertSame($expected, $diff2);
    }

    public function dataSet()
    {
        return [
            ['yml','json','pretty'],
            ['yml','json','plain'],
            ['yml','json','json']
        ];
    }

    private function getFixturePath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode(DIRECTORY_SEPARATOR, $parts));
    }
}
