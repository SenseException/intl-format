<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\PrecisionNumberFormatter;
use PHPUnit\Framework\TestCase;

class PrecisionNumberFormatterTest extends TestCase
{
    /**
     * @dataProvider provideTypeSpecifier
     *
     * @param string $typeSpecifier
     */
    public function testHas(string $typeSpecifier)
    {
        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        self::assertTrue($messageFormatter->has($typeSpecifier));
    }

    public function testHasIsFalse()
    {
        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        self::assertFalse($messageFormatter->has('.number'));
        self::assertFalse($messageFormatter->has('3number'));
        self::assertFalse($messageFormatter->has('.3numbr'));
    }

    public function testFormatValueNotANumber()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(10);

        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        $messageFormatter->formatValue('.2number', 'notANumber');
    }

    /**
     * @dataProvider provideNumber
     *
     * @param string $typeSpecifier
     * @param float $number
     * @param string $expects
     */
    public function testFormatValueNumber(string $typeSpecifier, float $number, string $expects)
    {
        $messageFormatter = new PrecisionNumberFormatter('de_DE');

        self::assertSame($expects, $messageFormatter->formatValue($typeSpecifier, $number));
    }

    /**
     * @return array
     */
    public function provideNumber() : array
    {
        return [
            'comma' => ['.2number', 1.2, '1,20'],
            'thousand-separator' => ['.3number', 1001.2, '1.001,200'],
            'million-separator' => ['.3number', 1003001.2, '1.003.001,200'],
            'zero-comma' => ['.1number', 0.20001, '0,2'],
        ];
    }

    /**
     * @return array
     */
    public function provideTypeSpecifier() : array
    {
        return [
            ['.2number'],
            ['.0number'],
            ['.100number'],
        ];
    }
}
