<?php

namespace Differ\Phpunit\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        //$file1 = __DIR__ . "/fixtures/file1.json";
        //$file2 = __DIR__ . "/fixtures/file2.json";
        $result = genDiff(__DIR__ . "/fixtures/file1.json", __DIR__ . "/fixtures/file2.json");
        $this->assertEquals($expected, $result);
    }
}
