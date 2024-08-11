<?php

namespace Differ\Formatters\Json;

const INDENT = 2;


function format(array $tree, int $countIndents = 1)
{
    $totalIndent = str_repeat(" ", ($countIndents + 1) * INDENT);
    $totalIndent1 = str_repeat(" ", ($countIndents) * INDENT);
    $result = array_map(function ($node) use ($totalIndent, $totalIndent1, $countIndents) {
        $countIndents1 = $countIndents + 2;
        switch ($node['type']) {
            case 'parent':
                $result1 = format($node['children'], $countIndents1);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"parent\",",
                "{$totalIndent}\"children\": {\n{$result1}{$totalIndent}}\n{$totalIndent1}}\n"]);
            case 'first':
                $num = valueToString($node['value'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"removed\",\n{$totalIndent}\"value\": {$num}",
                "{$totalIndent1}}\n"]);
            case 'second':
                $num = valueToString($node['value'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"added\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n"]);
            case 'equivalent':
                $num = valueToString($node['value'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"unchanged\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n"]);
            case 'different':
                $num1 = valueToString($node['value1'], $countIndents);
                $num2 = valueToString($node['value2'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"updated\",\n{$totalIndent}\"value1\": {$num1},",
                "{$totalIndent}\"value2\": {$num2}\n{$totalIndent1}}\n"]);
        }
    }, $tree);
    return implode("", $result);
}

function valueToString(mixed $value, int $countIndents)
{
    if (is_null($value)) {
        return 'null';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value == '') {
        return "\"\"";
    }
    if (is_numeric($value)) {
        return "{$value}";
    }
    if (!is_array($value)) {
        return "\"{$value}\"";
    }
    $totalIndent = str_repeat(" ", ($countIndents) * INDENT);
    $allKeys = array_keys($value);
    $result = array_map(function ($key) use ($countIndents, $totalIndent, $value) {
        $countIndents1 = $countIndents + 1;
        $result1 = valueToString($value[$key], $countIndents1);
        return "{$totalIndent}    \"{$key}\": {$result1}\n";
    }, $allKeys);

    $result = implode("", $result);
    return "{\n{$result}{$totalIndent}  }";
}
