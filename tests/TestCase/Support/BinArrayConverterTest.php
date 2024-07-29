<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Support;

use App\Support\BinArrayConverter;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class BinArrayConverterTest extends TestCase
{
    /**
     * Test.
     *
     * @return void
     */
    public function testMakeBin(): void
    {
        $array = [1, 11, 21, 31];
        $expected = 0b1000000000100000000010000000001;
        $actual = BinArrayConverter::makeBinFromArray($array);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testMakeBinInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        BinArrayConverter::makeBinFromArray([123456]);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testMakeArray(): void
    {
        $binary = 0b1000000000100000000010000000001;
        $expected = [1, 11, 21, 31];
        $actual = BinArrayConverter::makeArrayFromBin($binary);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testMakeArrayEmpty(): void
    {
        $binary = 0;
        $expected = [];
        $actual = BinArrayConverter::makeArrayFromBin($binary);
        $this->assertEquals($expected, $actual);
    }
}
