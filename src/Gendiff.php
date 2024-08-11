<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\format;

function genDiff($file1, $file2, $format = "stylish")
{
    $fileDecode1 = parse($file1);
    $fileDecode2 = parse($file2);

    $result = findDifferences($fileDecode1, $fileDecode2);
    return format($result, $format);
}

function findDifferences($fileDecode1, $fileDecode2)
{
    $allKeys = array_merge($fileDecode1, $fileDecode2);
    ksort($allKeys);
    $allKeys = array_keys($allKeys);
    return array_map(function ($key) use ($fileDecode1, $fileDecode2) {
        switch (true) {
            case !array_key_exists($key, $fileDecode1):
                return ['key' => $key, 'value' => $fileDecode2[$key], 'type' => 'second'];
            case !array_key_exists($key, $fileDecode2):
                return ['key' => $key, 'value' => $fileDecode1[$key], 'type' => 'first'];
            case is_array($fileDecode1[$key]) && is_array($fileDecode2[$key]):
                $children = findDifferences($fileDecode1[$key], $fileDecode2[$key]);
                return ['key' => $key, 'children' => $children, 'type' => 'parent'];
            case $fileDecode1[$key] === $fileDecode2[$key]:
                return ['key' => $key, 'value' => $fileDecode1[$key], 'type' => 'equivalent'];
            default:
                return ['key' => $key,
                    'value2' => $fileDecode2[$key],
                    'value1' => $fileDecode1[$key],
                    'type' => 'different'
                ];
        }
    }, $allKeys);
}
