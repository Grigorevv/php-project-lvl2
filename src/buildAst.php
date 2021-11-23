<?php

namespace Ast\Ast;

use function Functional\sort;

function buildAst(array $data1, array $data2): array
{
    $keys = array_keys(array_merge($data1, $data2));
    $sortKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    $diff = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {   // ключа нет в 1ом объекте
            return ['key' => $key, 'type' => 'added', 'value' => $data2[$key]];
        }

        if (!array_key_exists($key, $data2)) {  // ключа нет в 2ом объекте
            return ['key' => $key, 'type' => 'deleted', 'value' => $data1[$key]];
        }

        if ($data1[$key] === $data2[$key]) {  // значения равны
            return ['key' => $key, 'type' => 'unchanged', 'value' => $data1[$key]];
        }

        if (is_array($data1[$key]) && is_array($data2[$key])) {  // оба значения объекты
            return ['key' => $key, 'type' => 'nested', 'children' => buildAst($data1[$key], $data2[$key])];
        }
        return ['key' => $key, 'type' => 'changed', 'value' => $data1[$key], 'value2' => $data2[$key]];
    }, $sortKeys);
    return $diff;
}
