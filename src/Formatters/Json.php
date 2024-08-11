<?php

namespace Differ\Formatters\Json;

const INDENT = 2;


function format($differences, $countIndents = 1)
{
    if (!is_array($differences)) {
        return;
    }
    $totalIndent = str_repeat(" ", ($countIndents + 1) * INDENT);
    $totalIndent1 = str_repeat(" ", ($countIndents) * INDENT);
    $saveCount = 0;
    $result = array_map(function ($node) use ($totalIndent, $totalIndent1, $countIndents) {
        
        $countIndents1 = $countIndents + 2;
        
        switch ($node['type']) {
            case 'parent':
                return "{$totalIndent1}\"{$node['key']}\": {\n{$totalIndent}\"type\": \"parent\",\n{$totalIndent}\"children\": {\n"
                 . format($node['children'], $countIndents1)
                 . "{$totalIndent}}\n{$totalIndent1}}\n";
            case 'first':
                $num = arrayToString($node['value'], $countIndents);
                return "{$totalIndent1}\"{$node['key']}\": {\n{$totalIndent}\"type\": \"removed\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n";
            case 'second':
                $num = arrayToString($node['value'], $countIndents);
                return "{$totalIndent1}\"{$node['key']}\": {\n{$totalIndent}\"type\": \"added\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n";
            case 'equivalent':
                $num = arrayToString($node['value'], $countIndents);
                return "{$totalIndent1}\"{$node['key']}\": {\n{$totalIndent}\"type\": \"unchanged\",\n{$totalIndent}\"value\": {$num}\n{$totalIndent1}}\n";
            case 'different':
                $num1 = arrayToString($node['value1'], $countIndents);
                $num2 = arrayToString($node['value2'], $countIndents);
                return "{$totalIndent1}\"{$node['key']}\": {\n{$totalIndent}\"type\": \"updated\",\n{$totalIndent}\"value1\": {$num1},\n{$totalIndent}\"value2\": {$num2}\n{$totalIndent1}}\n";
        }
    }, $differences);
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
