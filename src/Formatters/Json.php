<?php

namespace Differ\Formatters\Json;

const INDENT = 2;


function format(array $tree)
{
    return json_encode($tree);
}
