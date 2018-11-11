<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Formatter\SymbolFormatter;
use PHPUnit\Framework\TestCase;

class SymbolFormatterTest extends TestCase
{
    /**
     * @dataProvider provideTypeSpecifier
     *
     * @param string $typeSpecifier
     */
    public function testHas(string $typeSpecifier) : void
    {
        $formatter = new SymbolFormatter();

        self::assertTrue($formatter->has($typeSpecifier));
    }

    public function testHasIsFalse() : void
    {
        $messageFormatter = new SymbolFormatter();

        self::assertFalse($messageFormatter->has('int'));
    }

    /**
     * @dataProvider provideSymbols
     *
     * @param string $typeSpecifier
     * @param string $expected
     */
    public function testFormatValue(string $typeSpecifier, string $expected) : void
    {
        $formatter = new SymbolFormatter();

        self::assertSame($expected, $formatter->formatValue($typeSpecifier, 'en_US'));
    }

    /**
     * @return string[][]
     */
    public function provideTypeSpecifier() : array
    {
        return [
            'currency_symbol' => ['currency_symbol'],
            'currency_code' => ['currency_code'],
            'percent_symbol' => ['percent_symbol'],
            'permill_symbol' => ['permill_symbol'],
        ];
    }

    /**
     * @return string[][]
     */
    public function provideSymbols() : array
    {
        return [
            'currency_symbol' => ['currency_symbol', '$'],
            'currency_code' => ['currency_code', 'USD'],
            'percent_symbol' => ['percent_symbol', '%'],
            'permill_symbol' => ['permill_symbol', '‰'],
        ];
    }
}
