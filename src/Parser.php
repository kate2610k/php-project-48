<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($file)
{
    $name = $file;
    $file = file_get_contents($file);
    $format = explode('.', $name);
    $format = $format[1];

    if ($format == 'yml' || $format == 'yaml') {
        $file = Yaml::parse($file);
        return $file;
    }
    $fileDecode = json_decode($file, true);
    return $fileDecode;
}
