<?php

namespace Differ\Formatters\Stylish;

const INDENT = 4;

function format($tree, $countIndents = 0)
{
    $totalIndent = str_repeat(" ", $countIndents * INDENT);
    $result = array_map(function ($node) use ($totalIndent, $countIndents) {
        $countIndents1 = $countIndents + 1;
        switch ($node['type']) {
            case 'parent':
                return "{$totalIndent}    {$node['key']}: {\n"
                 . format($node['children'], $countIndents1)
                 . "{$totalIndent}    }\n";
            case 'first':
                $num = arrayToString($node['value'], $countIndents);
                return "{$totalIndent}  - {$node['key']}:{$num}\n";
            case 'second':
                $num = arrayToString($node['value'], $countIndents);
                return "{$totalIndent}  + {$node['key']}:{$num}\n";
            case 'equivalent':
                $num = arrayToString($node['value'], $countIndents);
                return "{$totalIndent}    {$node['key']}:{$num}\n";
            case 'different':
                $num1 = arrayToString($node['value1'], $countIndents);
                $num2 = arrayToString($node['value2'], $countIndents);
                return "{$totalIndent}  - {$node['key']}:{$num1}\n{$totalIndent}  + {$node['key']}:{$num2}\n";
        }
    }, $tree);
    return implode("", $result);
}

function arrayToString($array, $countIndents)
{
    if (is_null($array)) {
        return ' null';
    }
    if (is_bool($array)) {
        return $array ? ' true' : ' false';
    }
    if ($array == '') {
        return '';
    }
    if (!is_array($array)) {
        return " {$array}";
    }

    $totalIndent = str_repeat(" ", ($countIndents) * INDENT);
    $allKeys = array_keys($array);

    $result = array_map(function ($key) use ($countIndents, $totalIndent, $array) {
        $countIndents1 = $countIndents + 1;
        if (is_array($array[$key])) {
            $result1 = arrayToString($array[$key], $countIndents1);
            return "{$totalIndent}        {$key}:{$result1}\n";
        }
        return "{$totalIndent}        {$key}: {$array[$key]}\n";
    }, $allKeys);

    $result = implode("", $result);
    return " {\n{$result}{$totalIndent}    }";
}
