<?php

namespace Ast\Ast;

function buildAst($data1, $data2)
{
    $keys = array_keys(array_merge($data1, $data2));
    sort($keys);

    $diff = array_map(function ($key) use ($data1, $data2) {
    // ключа нет в 1ом объекте
        if (!array_key_exists($key, $data1)) {
            return ['key' => $key, 'type' => 'added', 'value' => $data2[$key]];
        }
    // ключа нет в 2ом объекте
        if (!array_key_exists($key, $data2)) {
             return ['key' => $key, 'type' => 'deleted', 'value' => $data1[$key]];
        }
    // значения равны
        if ($data1[$key] === $data2[$key]) {
             return ['key' => $key, 'type' => 'unchanged', 'value' => $data1[$key]];
        }
    // оба значения объекты
        if (is_array($data1[$key]) && is_array($data2[$key])) {
             return ['key' => $key, 'type' => 'nested', 'children' => buildAst($data1[$key], $data2[$key])];
        }
        return ['key' => $key, 'type' => 'changed', 'value' => $data1[$key], 'value2' => $data2[$key]];
    }, $keys);
    return $diff;
}
