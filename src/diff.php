<?php

// php gendiff f1.json f2.json
// ./gendiff f1.json f2.json


namespace Differ\Differ;

//function genDiff($firstFilePath, $secondFilePath, $format)
function genDiff($a, $b)
{
    /*echo $firstFilePath, PHP_EOL;
    echo $secondFilePath, PHP_EOL;
    echo $format, PHP_EOL;*/
    //$b = file_get_contents($firstFilePath);

   // $a = json_decode($b, true);
   // print_r($a);
    return $a + $b;
}
