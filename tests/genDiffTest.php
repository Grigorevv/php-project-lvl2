<?php

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class genDiffTest extends TestCase
{
    public function testGendiff(): void
    {
        $this->assertEquals(10, genDiff(5, 5));
    }


}