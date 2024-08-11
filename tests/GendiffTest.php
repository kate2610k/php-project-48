<?php

namespace Differ\Phpunit\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $result = genDiff(__DIR__ . "/fixtures/file1.json", __DIR__ . "/fixtures/file2.json");
        $this->assertEquals($expected, $result);

        $result = genDiff(__DIR__ . "/fixtures/file1.yaml", __DIR__ . "/fixtures/file2.yaml");
        $this->assertEquals($expected, $result);


        $expected = file_get_contents(__DIR__ . "/fixtures/expected1.txt");
        $result = genDiff(__DIR__ . "/fixtures/file11.json", __DIR__ . "/fixtures/file21.json");
        $this->assertEquals($expected, $result);

        $result = genDiff(__DIR__ . "/fixtures/file11.yaml", __DIR__ . "/fixtures/file21.yaml");
        $this->assertEquals($expected, $result);


        $expected = file_get_contents(__DIR__ . "/fixtures/expected2.txt");
        $result = genDiff(__DIR__ . "/fixtures/file11.yaml", __DIR__ . "/fixtures/file21.yaml", "plain");
        $this->assertEquals($expected, $result);

        $expected = file_get_contents(__DIR__ . "/fixtures/expected3.txt");
        $result = genDiff(__DIR__ . "/fixtures/file11.yaml", __DIR__ . "/fixtures/file21.yaml", "json");
        $this->assertEquals($expected, $result);
    }
}
