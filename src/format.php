<?php

namespace Format\Format;

use function Plain\Plain\renderPlain;
use function Stylish\Stylish\renderStylish;
use function Json\Json\renderJson;

function format(array $ast, string $formatName): string
{
    switch ($formatName) {
        case 'stylish':
            return renderStylish($ast);

        case 'plain':
            return renderPlain($ast);

        case 'json':
            return renderJson($ast);

        default:
            throw new \Exception("Unknown format: '{$formatName}'!");
    }
}
