<?php

namespace Plain\Plain;

function toStr($value, $boolToStr)
{
    if (gettype($value) === 'boolean' || $value === null) {
        return $boolToStr[$value];
    } elseif (gettype($value) === 'integer') {
        return "{$value}";
    }
    return is_array($value) ? '[complex value]' : "'{$value}'";
}

function iter($currentValue, $anchestry = [])
{
    $boolToStr = [true => 'true', null => 'null', false => 'false'];

    $result = array_map(function ($item) use ($anchestry, $boolToStr) {
        switch ($item['type']) {
            case 'unchanged':
                return '';

            case 'added':
                     $property = join('.', [...$anchestry, $item['key']]);
                if (gettype($item['value']) === 'boolean' || $item['value'] === null) {
                     $valueToStr = $boolToStr[$item['value']];
                } else {
                     $valueToStr = toStr($item['value'], $boolToStr);
                }
                return "Property '{$property}' was added with value: {$valueToStr}";

            case 'deleted':
                $property = join('.', [...$anchestry, $item['key']]);
                return "Property '{$property}' was removed";

            case 'changed':
                $property = join('.', [...$anchestry, $item['key']]);
                $valueToStrAfter = toStr($item['value2'], $boolToStr);
                $valueToStrBefore = toStr($item['value'], $boolToStr);
                return "Property '{$property}' was updated. From {$valueToStrBefore} to {$valueToStrAfter}";

            case 'nested':
                $result = iter($item['children'], [...$anchestry, $item['key']]);
                return "{$result}";

            default:
                throw new \Exception("Unknown format: '{$item['type']}'!");
        }
    }, $currentValue);
        return join("\n", array_filter($result, fn($item) => $item !== ''));
}
function renderPlain($ast)
{
    return iter($ast);
}
