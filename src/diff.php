<?php

// ./gendiff ../tests/fixtures/f1.json ../tests/fixtures/f2.json
// ./gendiff --format json ../tests/fixtures/f1.yml ../tests/fixtures/f2.yml

namespace Differ\Differ;

use function Parsers\Parsers\parser;
use function Ast\Ast\buildAst;
use function Format\Format\format;

function genDiff($firstFilePath, $secondFilePath, $formatName = 'stylish')
{
    $getFileData = fn($filepath) => file_get_contents($filepath);
    $getFileExtension = function ($filepath) {
        $extension =  pathinfo($filepath, PATHINFO_EXTENSION);
        return $extension === 'yaml' ? 'yml' : $extension;
    };
    $data1 = parser($getFileData($firstFilePath), $getFileExtension($firstFilePath));
    $data2 = parser($getFileData($secondFilePath), $getFileExtension($secondFilePath));
    $ast = buildAst($data1, $data2);
    return format($ast, $formatName);
}
