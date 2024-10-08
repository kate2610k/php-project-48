<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $file)
{
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    $content = (string) file_get_contents($file);

    if ($extension == 'yml' || $extension == 'yaml') {
        return Yaml::parse($content);
    }
    return json_decode($content, true);
}
