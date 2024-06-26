<?php

namespace Differ\Differ;

function genDiff($file1, $file2)
{
    $file1 = file_get_contents($file1);
    $file2 = file_get_contents($file2);

    $fileDecode1 = json_decode($file1, true);
    $fileDecode2 = json_decode($file2, true);

    $allKeys = array_merge($fileDecode1, $fileDecode2);
    ksort($allKeys);
    $allKeys = array_keys($allKeys);

    $result = array_reduce($allKeys, function ($acc, $key) use ($fileDecode1, $fileDecode2) {
        if (array_key_exists($key, $fileDecode1)) {
            $fileDecode1[$key] = match ($fileDecode1[$key]) {
                true => 'true',
                false => 'false',
                default => $fileDecode1[$key],
            };
        } elseif (array_key_exists($key, $fileDecode2)) {
            $fileDecode2[$key] = match ($fileDecode2[$key]) {
                true => 'true',
                false => 'false',
                default => $fileDecode2[$key],
            };
        }
        if (array_key_exists($key, $fileDecode1) && !array_key_exists($key, $fileDecode2)) {
            $acc = "{$acc}  - {$key}: {$fileDecode1[$key]}\n";
        } elseif (!array_key_exists($key, $fileDecode1) && array_key_exists($key, $fileDecode2)) {
            $acc = "{$acc}  + {$key}: {$fileDecode2[$key]}\n";
        } elseif ($fileDecode1[$key] === $fileDecode2[$key]) {
            $acc = "{$acc}    {$key}: {$fileDecode1[$key]}\n";
        } else {
            $acc = "{$acc}  - {$key}: {$fileDecode1[$key]}\n";
            $acc = "{$acc}  + {$key}: {$fileDecode2[$key]}\n";
        }
        return $acc;
    });
    return "{\n{$result}}\n";
}
