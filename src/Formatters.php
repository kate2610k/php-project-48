<?php

namespace Differ\Formatters;

function format(array $tree, string $format)
{
    switch ($format) {
        case "stylish":
            return "{\n" . Stylish\format($tree) . "}\n";
        case "plain":
            return Plain\format($tree);
        case "json":
            return "{" . Json\format($tree) . "}\n";
    }
}
