<?php
declare(strict_types=1);

namespace Proyecto\Tests\Domain\Model\Shared;

use Proyecto\Domain\Model\Shared\UnitConversion;
use PHPUnit\Framework\TestCase;

class UnitConversionTest extends TestCase
{
    public function test_roundToIntWithDivisorTest(): void
    {
        $input = 7;
        $expected = 4;
        $output =  UnitConversion::roundToIntWithDivisor($input, 2);
        $this->assertEquals($expected, $output);
    }

    public function test_roundToIntTest(): void
    {
        $input = 3.5;
        $expected = 4;
        $output =  UnitConversion::roundToInt($input);
        $this->assertEquals($expected, $output);
    }

    public function test_toBase100Test(): void
    {
        $input = 1;
        $expected = 100;
        $output =  UnitConversion::toBase100($input);
        $this->assertEquals($expected, $output);
    }

    public function test_fromBase100(): void
    {
        $input = 100;
        $expected = 1;
        $output =  UnitConversion::fromBase100($input);
        $this->assertEquals($expected, $output);
    }
}
