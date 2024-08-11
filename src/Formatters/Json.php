<?php

namespace Differ\Formatters\Json;

const INDENT = 2;


function format($tree, $countIndents = 1)
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
                $num = arrayToString($node['value'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"removed\",\n{$totalIndent}\"value\": {$num}",
                "{$totalIndent1}}\n"]);
            case 'second':
                $num = arrayToString($node['value'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"added\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n"]);
            case 'equivalent':
                $num = arrayToString($node['value'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"unchanged\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n"]);
            case 'different':
                $num1 = arrayToString($node['value1'], $countIndents);
                $num2 = arrayToString($node['value2'], $countIndents);
                return implode("\n", ["{$totalIndent1}\"{$node['key']}\": {",
                "{$totalIndent}\"type\": \"updated\",\n{$totalIndent}\"value1\": {$num1},",
                "{$totalIndent}\"value2\": {$num2}\n{$totalIndent1}}\n"]);
        }
    }, $tree);
    return implode("", $result);
}

function arrayToString($array, $countIndents)
{
    if (is_null($array)) {
        return 'null';
    }
    if (is_bool($array)) {
        return $array ? 'true' : 'false';
    }
    if ($array == '') {
        return "\"\"";
    }
    if (is_numeric($array)) {
        return "{$array}";
    }
    if (!is_array($array)) {
        return "\"{$array}\"";
    }
    $totalIndent = str_repeat(" ", ($countIndents) * INDENT);
    $allKeys = array_keys($array);
    $result = array_map(function ($key) use ($countIndents, $totalIndent, $array) {
        $countIndents1 = $countIndents + 1;
        $result1 = arrayToString($array[$key], $countIndents1);
        return "{$totalIndent}    \"{$key}\": {$result1}\n";
    }, $allKeys);

    $result = implode("", $result);
    return "{\n{$result}{$totalIndent}  }";
}
