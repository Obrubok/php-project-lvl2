<?php

namespace Gen\Diff\Tests;

use PHPUnit\Framework\TestCase;
use function Gen\Diff\Differ\genDiff;

class DifferTest extends TestCase
{
    public $beforePath;
    public $afterPath;
    public $diffPath;

    public function setUp(): void
    {
        $this->before = __DIR__ . '/fixtures/before.json';
        $this->after = __DIR__ . '/fixtures/after.json';
        $this->diff = __DIR__ . '/fixtures/diff';
    }

    public function testGenDiff()
    {
        $actual = genDiff($this->before, $this->after);
        $expected = file_get_contents($this->diff);
        $this->assertEquals($expected, $actual);
    }
}
