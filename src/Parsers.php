<?php
// ./bin/gendiff ./tests/fixtures/f1.json ./tests/fixtures/f2.json
// ./bin/gendiff --format plain ./tests/fixtures/f1.json ./tests/fixtures/f2.json
// ./bin/gendiff -h
namespace Parsers\Parsers;

use Symfony\Component\Yaml\Yaml;

function parser(mixed $data, string $format): array
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);

        case 'yml':
            return Yaml::parse($data);

        default:
            throw new \Exception("Unknown format: '{$format}'!");
    }
}
