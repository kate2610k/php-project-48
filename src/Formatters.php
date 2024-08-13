<?php

namespace Differ\Formatters;

function format(array $tree, string $format)
{
    switch ($format) {
        case "stylish":
            $result = Stylish\format($tree);
            return "{\n{$result}}\n";
        case "plain":
            return Plain\format($tree);
        case "json":
            $result = Json\format($tree);
            return  "{$result}\n";
    }
}
