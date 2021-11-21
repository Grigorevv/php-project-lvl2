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
    ////////////////////попробовать убрать деструктуризацию заменить на обращением по ключу в массиве
    ['key' => $key, 'type' => $type] = $item;

    if ($type === 'nested') ['children' => $children] = $item;
    elseif ($type === 'changed') ['value' => $value, 'value2' => $value2] = $item;
    else ['value' => $value] = $item;
///////////////////////////////

    switch ($type) {
      case 'changed':
        $before = toStr($depth + 1, $value);
        $after = toStr($depth + 1, $value2);

        return "{$currentIndent}- {$key}: {$before}\n{$currentIndent}+ {$key}: {$after}";
        //return `${currentIndent}- ${key}: ${toStr(depth + 1, value)}\n${currentIndent}+ ${key}: ${toStr(depth + 1, value2)}`;

      case 'added':
        $after = toStr($depth + 1, $value);
        return "{$currentIndent}+ {$key}: {$after}";
        //return `${currentIndent}+ ${key}: ${toStr(depth + 1, value)}`;

      case 'deleted':
        $after = toStr($depth + 1, $value);
        return "{$currentIndent}- {$key}: {$after}";

      case 'unchanged':
        $after = toStr($depth + 1, $value);
        return "{$currentIndent}  {$key}: {$after}";

      case 'nested':
        $after = iter($children, $depth + 1);
        return "{$currentIndent}  {$key}: {$after}";

      default:
      //  throw new Error(`Unknown type: '${type}'!`);
    }
  }, $currentValue);

  return join("\n", ["{", ...$lines, "{$bracketIndent}}"]);
};

function stylish ($ast) {
    return iter($ast, 1);
}

