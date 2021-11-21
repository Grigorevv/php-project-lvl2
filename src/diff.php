<?php

//echo $firstFilePath, PHP_EOL;

// ./gendiff ../tests/fixtures/f1.json ../tests/fixtures/f2.json
// ./gendiff ../tests/fixtures/f1.yml ../tests/fixtures/f2.yml
// ./gendiff --format plain ../tests/fixtures/f1.yml ../tests/fixtures/f2.yml 

namespace Differ\Differ;

use function Parsers\Parsers\parser;
use function Ast\Ast\buildAst;
use function Format\Format\format;

function genDiff($firstFilePath, $secondFilePath, $formatName = 'stylish')
{
    $getFileData = fn($filepath) => file_get_contents($filepath);
    $getFileExtension = fn($filepath) => pathinfo($filepath, PATHINFO_EXTENSION);
    $data1 = parser($getFileData($firstFilePath), $getFileExtension($firstFilePath));
    $data2 = parser($getFileData($secondFilePath), $getFileExtension($secondFilePath));
    $ast = buildAst($data1, $data2);
    return format($ast, $formatName);
}
