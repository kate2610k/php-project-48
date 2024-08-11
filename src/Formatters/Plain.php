<?php

namespace Differ\Formatters\Plain;

function format($tree, $way = '')
{
    $result = array_map(function ($node) use ($way) {
        $way1 = "{$way}.{$node['key']}";
        switch ($node['type']) {
            case 'parent':
                return format($node['children'], $way1);
            case 'first':
                $way1 = mb_substr($way1, 1);
                return "Property '{$way1}' was removed\n";
            case 'second':
                $num = arrayToString($node['value']);
                $way1 = mb_substr($way1, 1);
                return "Property '{$way1}' was added with value: {$num}\n";
            case 'equivalent':
                return;
            case 'different':
                $num1 = arrayToString($node['value1']);
                $num2 = arrayToString($node['value2']);
                $way1 = mb_substr($way1, 1);
                return "Property '{$way1}' was updated. From {$num1} to {$num2}\n";
        }
    }, $tree);
    return implode("", $result);
}

function arrayToString($array)
{
    if (is_null($array)) {
        return 'null';
    }
    if (is_bool($array)) {
        return $array ? 'true' : 'false';
    }
    if ($array == '') {
        return "''";
    }
    if (!is_array($array)) {
        return "'{$array}'";
    }
    return "[complex value]";
}
