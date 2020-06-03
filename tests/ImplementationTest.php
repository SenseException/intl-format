<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Formatter\SprintfFormatter;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;
use PHPUnit\Framework\TestCase;

class ImplementationTest extends TestCase
{
    /**
     * @dataProvider formattingWorksProvider
     *
     * @param array<int|string> $args
     */
    public function testFormattingWorks(string $expected, string $message, array $args): void
    {
        $formatter = [
            new SprintfFormatter(),
        ];

        $intlFormat = new IntlFormat($formatter, new SprintfParser());

        self::assertSame($expected, $intlFormat->format($message, ...$args));
    }

    /**
     * @return array<array{string, string, array<int|string>}>
     */
    public function formattingWorksProvider(): array
    {
        return [
            ['there are 12 monkeys on the 002 trees', 'there are %d %s on the %03d trees', [12, 'monkeys', 2]],
        ];
    }
}
