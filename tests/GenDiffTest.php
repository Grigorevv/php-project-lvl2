<?php

namespace Php\Project\Lvl2\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public static $fileNameJson1 = 'f1.json';
    public static $fileNameJson2 = 'f2.json';
    public static $fileNameYml1 = 'f1.yml';
    public static $fileNameYml2 = 'f2.yml';
    public static $fileNameExpectedStylish = 'exp_stylish.txt';
    public static $fileNameExpectedPlain = 'exp_plain.txt';
    public static $fileNameExpectedJson = 'exp_json.txt';
    public static $fixtures = './tests/fixtures/';

    public function buildPath($fileName)
    {
        $path = self::$fixtures .$fileName;
        return realpath($path);
    }

    public function testStylish(): void
    {
        $dataExpectedFile = file_get_contents($this->buildPath(self::$fileNameExpectedStylish));
        $diff = genDiff($this->buildPath(self::$fileNameJson1), $this->buildPath(self::$fileNameJson2), 'stylish');
        $this->assertEquals($dataExpectedFile, $diff);
    }

    public function testPlain(): void
    {
        $dataExpectedFile = file_get_contents($this->buildPath(self::$fileNameExpectedPlain));
        $diff = genDiff($this->buildPath(self::$fileNameJson1), $this->buildPath(self::$fileNameJson2), 'plain');
        $this->assertEquals($dataExpectedFile, $diff);
    }

    public function testStylishYml(): void
    {
        $dataExpectedFile = file_get_contents($this->buildPath(self::$fileNameExpectedStylish));
        $diff = genDiff($this->buildPath(self::$fileNameYml1), $this->buildPath(self::$fileNameYml2), 'stylish');
        $this->assertEquals($dataExpectedFile, $diff);
    }

    public function testJson(): void
    {
        $dataExpectedFile = file_get_contents($this->buildPath(self::$fileNameExpectedJson));
        $diff = genDiff($this->buildPath(self::$fileNameYml1), $this->buildPath(self::$fileNameJson2), 'json');
        $this->assertEquals($dataExpectedFile, $diff);
    }
}
