<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($file)
{
    $name = $file;
    $content = file_get_contents($file);
    $extension = explode('.', $name);
    $extension = $extension[1];

    if ($extension == 'yml' || $extension == 'yaml') {
        return Yaml::parse($content);
    }
    return json_decode($content, true);
}
