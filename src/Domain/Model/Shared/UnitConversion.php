<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Shared;

class UnitConversion
{
    public static function roundToIntWithDivisor(float|int $value, int $divisor): int
    {
        return self::roundToInt($value / $divisor);
    }

    public static function roundToInt(float|int $value): int
    {
        return \intval(\round($value, 1, \PHP_ROUND_HALF_UP));
    }

    public static function toBase100(float|int $naturalValue): int
    {
        return self::roundToInt($naturalValue * 100);
    }

    public static function fromBase100(int $base100Value): float
    {
        return $base100Value / 100;
    }
}
