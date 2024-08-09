<?php

namespace Differ\Formatters;

function format($tree, $format)
{
    switch ($format) {
        case "stylish":
            return "{\n" . Stylish\format($tree) . "}\n";
        case "plain":
            return Plain\format($tree);
        case "json":
            return Json\format($tree);
    }
}
