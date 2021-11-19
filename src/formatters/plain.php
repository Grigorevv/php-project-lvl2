<?php

namespace Plain\Plain;

function plain($ast)
{
    function iter($currentValue, $anchestry = [])
    {
        $toStr = function ($value) {
            if (is_array($value)) {
                return '[complex value]';
            } elseif (gettype($value) === 'string') {
                return "'{$value}'";
            }
                return $value;
        };

         $result = array_map(function ($item) use ($anchestry, $toStr) {
             ['key' => $key, 'type' => $type] = $item;
            if ($type === 'nested') {
                ['children' => $children] = $item;
            } elseif ($type === 'changed') {
                ['value' => $value, 'value2' => $value2] = $item;
            } else {
                ['value' => $value] = $item;
            }

            switch ($type) {
                case 'unchanged':
                    return '';

                case 'added':
                    $property = join('.', [...$anchestry, $key]);
                    $after = $toStr($value);
                    return "Property '{$property}' was added with value: {$after}";

                case 'deleted':
                    $property = join('.', [...$anchestry, $key]);
                    return "Property '{$property}' was removed";

                case 'changed':
                    $property = join('.', [...$anchestry, $key]);
                    $after = $toStr($value2);
                    $before = $toStr($value);
                    return "Property '{$property}' was updated. From {$before} to {$after}";

                case 'nested':
                    $result = iter($children, [...$anchestry, $key]);
                    return "{$result}";

                default:
                    throw new \Exception("Unknown format: '{$type}'!");
            }
         }, $currentValue);
        return join("\n", array_filter($result, fn($item) => $item !== ''));
    }
    return iter($ast);
}
