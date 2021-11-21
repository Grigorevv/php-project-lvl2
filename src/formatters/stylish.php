<?php

namespace Stylish\Stylish;

function getIndent ($depth)
{
  $replacer = ' ';
  $spacesCount = 4;
  $indentSize = $depth * $spacesCount;
  $currentIndent = str_repeat($replacer, $indentSize - 2);
  $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);
  return ['currentIndent' => $currentIndent, 'bracketIndent' => $bracketIndent];
};

function toStr ($depth, $value)
{
 ['currentIndent' => $currentIndent, 'bracketIndent' => $bracketIndent] = getIndent($depth);
 
  if (is_array($value)) {
    $a = \Funct\Collection\pairs($value);
  
    $lines = array_map(function ($item) use ($currentIndent, $depth)
    { 
      [$key, $value] = $item;
      $mapValue = toStr($depth + 1, $value);
      
      return "{$currentIndent}  {$key}: {$mapValue}"; }, $a);

    return join("\n", ["{", ...$lines, "{$bracketIndent}}"]);
  }
  return $value;
};


function iter ($currentValue, $depth)
{
  ['currentIndent' => $currentIndent, 'bracketIndent' => $bracketIndent] = getIndent($depth);
  $lines = array_map(function ($item) use ($currentIndent, $depth)
  {
    switch ($item['type']) {
      case 'changed':
        $valueToStrBefore = toStr($depth + 1, $item['value']);
        $valueToStrAfter = toStr($depth + 1, $item['value2']);
        return "{$currentIndent}- {$item['key']}: {$valueToStrBefore}\n{$currentIndent}+ {$item['key']}: {$valueToStrAfter}";

      case 'added':
        $valueToStr = toStr($depth + 1, $item['value']);
        return "{$currentIndent}+ {$item['key']}: {$valueToStr}";

      case 'deleted':
        $valueToStr = toStr($depth + 1, $item['value']);
        return "{$currentIndent}- {$item['key']}: {$valueToStr}";

      case 'unchanged':
        $valueToStr = toStr($depth + 1, $item['value']);
        return "{$currentIndent}  {$item['key']}: {$valueToStr}";

      case 'nested':
        $valueToStr = iter($item['children'], $depth + 1);
        return "{$currentIndent}  {$item['key']}: {$valueToStr}";

      default:
        throw new \Exception("Unknown format: '{$item['type']}'!");
    }
  }, $currentValue);

  return join("\n", ["{", ...$lines, "{$bracketIndent}}"]);
};

function renderStylish ($ast) {
    return iter($ast, 1);
}

