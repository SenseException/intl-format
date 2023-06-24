<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\PrecisionNumberFormatter;
use PHPUnit\Framework\TestCase;

class PrecisionNumberFormatterTest extends TestCase
{
    /** @dataProvider provideTypeSpecifier */
    public function testHas(string $typeSpecifier): void
    {
        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        self::assertTrue($messageFormatter->has($typeSpecifier));
    }

    /** @dataProvider provideInvalidTypeSpecifier */
    public function testHasIsFalse(string $typeSpecifier): void
    {
        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        self::assertFalse($messageFormatter->has($typeSpecifier));
    }

    public function testFormatValueNotANumber(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageMatches('/"integer, double"/');
        $this->expectExceptionCode(10);

        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        $messageFormatter->formatValue('.2number', 'notANumber');
    }

    /** @dataProvider provideNumber */
    public function testFormatValueNumber(string $typeSpecifier, float $number, string $expects): void
    {
        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        self::assertSame($expects, $messageFormatter->formatValue($typeSpecifier, $number));
    }

    /** @return array<array{string, float, string}> */
    public static function provideNumber(): array
    {
        return [
            'comma' => ['.2number', 1.2, '1,20'],
            'thousand-separator' => ['.3number', 1001.2, '1.001,200'],
            'million-separator' => ['.3number', 1003001.2, '1.003.001,200'],
            'zero-comma' => ['.1number', 0.20001, '0,2'],
            'double-digit-fraction' => ['.10number', 1, '1,0000000000'],
            'prefix-comma-and-zero' => ['05.2number', 1.2, '01,20'],
            'prefix-comma' => ['02.2number', 1.2, '1,20'],
            'prefix' => ['03number', 1, '001'],
            'prefix-space' => ['3number', 1, '  1'],
            'prefix-space-double-digit' => ['10number', 1, '         1'],
            'prefix-zero' => ['0number', 1, '1'],
            'prefix-comma-no-round' => ['02.2number', 1.225, '1,22'],

            'prefix-comma-halfway-down' => ['02.2number_halfway_down', 1.225, '1,22'],
            'prefix-comma-halfway-down-odd' => ['02.2number_halfway_down', 1.235, '1,23'],

            'comma-round' => ['.2number_halfway_up', 1.2, '1,20'],
            'thousand-separator-round' => ['.3number_halfway_up', 1001.2, '1.001,200'],
            'million-separator-round' => ['.3number_halfway_up', 1003001.2, '1.003.001,200'],
            'zero-comma-round' => ['.1number_halfway_up', 0.20001, '0,2'],
            'double-digit-fraction-round' => ['.10number_halfway_up', 1, '1,0000000000'],
            'prefix-comma-and-zero-round' => ['05.2number_halfway_up', 1.2, '01,20'],
            'prefix-comma-round' => ['02.2number_halfway_up', 1.2, '1,20'],
            'prefix-round' => ['03number_halfway_up', 1, '001'],
            'prefix-space-round' => ['3number_halfway_up', 1, '  1'],
            'prefix-space-double-digit-round' => ['10number_halfway_up', 1, '         1'],
            'prefix-zero-round' => ['0number_halfway_up', 1, '1'],
            'prefix-comma-round-up' => ['02.2number_halfway_up', 1.225, '1,23'],

            'prefix-comma-round-always-up' => ['02.2number_ceil', 1.221, '1,23'],
            'prefix-comma-round-negative-up' => ['02.2number_ceil', -1.221, '-1,22'],
            'prefix-comma-round-no-up-on-zero' => ['02.2number_ceil', 1.220, '1,22'],
            'prefix-comma-up' => ['number_ceil', 1.190, '2'],

            'prefix-comma-round-always-down' => ['02.2number_floor', 1.229, '1,22'],
            'prefix-comma-round-negative-down' => ['02.2number_floor', -1.229, '-1,23'],
            'prefix-comma-down' => ['number_floor', 1.929, '1'],

            'prefix-comma-halfeven' => ['02.2number_halfeven', 1.225, '1,22'],
            'prefix-comma-halfeven-odd' => ['02.2number_halfeven', 1.235, '1,24'],
        ];
    }

    /** @return array<array<string>> */
    public static function provideTypeSpecifier(): array
    {
        return [
            ['.2number'],
            ['.0number'],
            ['.100number'],
            ['03number'],
            ['3number'],
            ['05.3number'],

            ['.2number_halfway_down'],
            ['.0number_halfway_down'],
            ['.100number_halfway_down'],
            ['03number_halfway_down'],
            ['3number_halfway_down'],
            ['05.3number_halfway_down'],

            ['.2number_halfway_up'],
            ['.0number_halfway_up'],
            ['.100number_halfway_up'],
            ['03number_halfway_up'],
            ['3number_halfway_up'],
            ['05.3number_halfway_up'],

            ['.2number_ceil'],
            ['.0number_ceil'],
            ['.100number_ceil'],
            ['03number_ceil'],
            ['3number_ceil'],
            ['05.3number_ceil'],

            ['.2number_floor'],
            ['.0number_floor'],
            ['.100number_floor'],
            ['03number_floor'],
            ['3number_floor'],
            ['05.3number_floor'],

            ['.2number_halfeven'],
            ['.0number_halfeven'],
            ['.100number_halfeven'],
            ['03number_halfeven'],
            ['3number_halfeven'],
            ['05.3number_halfeven'],
        ];
    }

    /** @return array<array<string>> */
    public static function provideInvalidTypeSpecifier(): array
    {
        return [
            ['number'],
            ['number_specifier'],
            ['.number'],
            ['3numbr'],
            ['.3numbr'],
        ];
    }
}
