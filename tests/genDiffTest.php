<?php

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class genDiffTest extends TestCase
{
    public static $fileNameJson1 = 'f1.json';
    public static $fileNameJson2 = 'f2.json';
    public static $fileNameYml1 = 'f1.yml';
    public static $fileNameYml2 = 'f2.yml';
    public static $fileNameExpectedStylish = 'exp_stylish.txt';
    public static $fileNameExpectedPlain = 'exp_plain.txt';
    public static $fixtures = './tests/fixtures/';

    public function buildPath($fileName)
    {
        $path = self::$fixtures.$fileName;
        return realpath($path);
    }

    public function testStylishJson(): void
    {
       $dataExpectedFile = file_get_contents($this->buildPath(self::$fileNameExpectedStylish));
       $diff = genDiff($this->buildPath(self::$fileNameJson1), $this->buildPath(self::$fileNameYml2), 'stylish');
       $this->assertEquals($dataExpectedFile, $diff);
    }

    public function testPlainJson(): void
    {
       $dataExpectedFile = file_get_contents($this->buildPath(self::$fileNameExpectedPlain));
       $diff = genDiff($this->buildPath(self::$fileNameYml1), $this->buildPath(self::$fileNameJson2), 'plain');
       $this->assertEquals($dataExpectedFile, $diff);
    }

}
