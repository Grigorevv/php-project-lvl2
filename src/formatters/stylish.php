<?php

namespace Stylish\Stylish;

function pairs(array $collection): array
{
    return array_map(
        function ($key, $value) {
            return [$key, $value];
        },
        array_keys($collection),
        $collection
    );
}

function getIndent(int $depth): array
{
    $replacer = ' ';
    $spacesCount = 4;
    $indentSize = $depth * $spacesCount;
    $currentIndent = str_repeat($replacer, $indentSize - 2);
    $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);
    return ['currentIndent' => $currentIndent, 'bracketIndent' => $bracketIndent];
}

function toStr(int $depth, mixed $value): string
{
    ['currentIndent' => $currentIndent, 'bracketIndent' => $bracketIndent] = getIndent($depth);
    $boolToStr = [true => 'true', null => 'null', false => 'false'];

    if (is_array($value)) {
        $keysValues = pairs($value);
        $lines = array_map(function ($item) use ($currentIndent, $depth) {
            [$key, $value] = $item;
            $valueToStr = toStr($depth + 1, $value);
            return "{$currentIndent}  {$key}: {$valueToStr}";
        }, $keysValues);
        return join("\n", ["{", ...$lines, "{$bracketIndent}}"]);
    }
    return gettype($value) === 'boolean' || $value === null ? $boolToStr[$value] : $value;
}

function iter(mixed $currentValue, int $depth): string
{
    ['currentIndent' => $curInd, 'bracketIndent' => $bracketIndent] = getIndent($depth);
    $lines = array_map(function ($item) use ($curInd, $depth) {
        switch ($item['type']) {
            case 'changed':
                 $valToStrBef = toStr($depth + 1, $item['value']);
                 $valToStrAft = toStr($depth + 1, $item['value2']);
                return "{$curInd}- {$item['key']}: {$valToStrBef}\n{$curInd}+ {$item['key']}: {$valToStrAft}";

            case 'added':
                 $valToStr = toStr($depth + 1, $item['value']);
                return "{$curInd}+ {$item['key']}: {$valToStr}";

            case 'deleted':
                 $valToStr = toStr($depth + 1, $item['value']);
                return "{$curInd}- {$item['key']}: {$valToStr}";

            case 'unchanged':
                 $valToStr = toStr($depth + 1, $item['value']);
                return "{$curInd}  {$item['key']}: {$valToStr}";

            case 'nested':
                 $valToStr = iter($item['children'], $depth + 1);
                return "{$curInd}  {$item['key']}: {$valToStr}";

            default:
                throw new \Exception("Unknown format: '{$item['type']}'!");
        }
    }, $currentValue);
    return join("\n", ["{", ...$lines, "{$bracketIndent}}"]);
}
function renderStylish(array $ast): string
{
    return iter($ast, 1);
}
