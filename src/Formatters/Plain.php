<?php

namespace Differ\Formatters\Plain;

function format(array $tree, string $way = '')
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
                $num = valueToString($node['value']);
                $way1 = mb_substr($way1, 1);
                return "Property '{$way1}' was added with value: {$num}\n";
            case 'equivalent':
                return;
            case 'different':
                $num1 = valueToString($node['value1']);
                $num2 = valueToString($node['value2']);
                $way1 = mb_substr($way1, 1);
                return "Property '{$way1}' was updated. From {$num1} to {$num2}\n";
        }
    }, $tree);
    return implode("", $result);
}

function valueToString(mixed $value)
{
    if (is_null($value)) {
        return 'null';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value == '') {
        return "''";
    }
    if (!is_array($value)) {
        return "'{$value}'";
    }
    return "[complex value]";
}
