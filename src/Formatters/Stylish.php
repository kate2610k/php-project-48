<?php

namespace Differ\Formatters\Stylish;

const INDENT = 4;

function format(array $tree, int $countIndents = 0)
{
    $totalIndent = str_repeat(" ", $countIndents * INDENT);
    $result = array_map(function ($node) use ($totalIndent, $countIndents) {
        $countIndents1 = $countIndents + 1;
        switch ($node['type']) {
            case 'parent':
                $result1 = format($node['children'], $countIndents1);
                return "{$totalIndent}    {$node['key']}: {\n{$result1}{$totalIndent}    }\n";
            case 'first':
                $num = valueToString($node['value'], $countIndents);
                return "{$totalIndent}  - {$node['key']}:{$num}\n";
            case 'second':
                $num = valueToString($node['value'], $countIndents);
                return "{$totalIndent}  + {$node['key']}:{$num}\n";
            case 'equivalent':
                $num = valueToString($node['value'], $countIndents);
                return "{$totalIndent}    {$node['key']}:{$num}\n";
            case 'different':
                $num1 = valueToString($node['value1'], $countIndents);
                $num2 = valueToString($node['value2'], $countIndents);
                return "{$totalIndent}  - {$node['key']}:{$num1}\n{$totalIndent}  + {$node['key']}:{$num2}\n";
        }
    }, $tree);
    return implode("", $result);
}

function valueToString(mixed $value, int $countIndents)
{
    if (is_null($value)) {
        return ' null';
    }
    if (is_bool($value)) {
        return $value ? ' true' : ' false';
    }
    if ($value == '') {
        return '';
    }
    if (!is_array($value)) {
        return " {$value}";
    }
    $totalIndent = str_repeat(" ", ($countIndents) * INDENT);
    $allKeys = array_keys($value);
    $result = array_map(function ($key) use ($countIndents, $totalIndent, $value) {
        $countIndents1 = $countIndents + 1;
        if (is_array($value[$key])) {
            $result1 = valueToString($value[$key], $countIndents1);
            return "{$totalIndent}        {$key}:{$result1}\n";
        }
        return "{$totalIndent}        {$key}: {$value[$key]}\n";
    }, $allKeys);
    $result = implode("", $result);
    return " {\n{$result}{$totalIndent}    }";
}
