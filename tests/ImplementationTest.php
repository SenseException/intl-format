<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Formatter\SprintfFormatter;
use Budgegeria\IntlFormat\IntlFormat;
use PHPUnit\Framework\TestCase;

class ImplementationTest extends TestCase
{
    /**
     * @dataProvider formattingWorksProvider
     */
    public function testFormattingWorks($expected, $message, ...$args)
    {
        $formatter = [
            new SprintfFormatter(),
        ];

        $intlFormat = new IntlFormat($formatter);

        self::assertSame($expected, $intlFormat->format($message, ...$args));
    }

    public function formattingWorksProvider()
    {
        return [
            ['there are 12 monkeys on the 002 trees', 'there are %d %s on the %03d trees', 12, 'monkeys', 2],
        ];
    }
}
