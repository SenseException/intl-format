<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Factory;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\IntlFormatInterface;
use DateTime;
use DateTimeZone;
use Exception;
use Locale;
use PHPUnit\Framework\TestCase;

use function Budgegeria\IntlFormat\sprintf;

class FactoryTest extends TestCase
{
    public function testCreateIntlFormat(): void
    {
        $intlFormat = (new Factory())->createIntlFormat('de_DE');

        self::assertInstanceOf(IntlFormat::class, $intlFormat);
    }

    public function testCreateIntlFormatIntegration(): void
    {
        $intlFormat = (new Factory())->createIntlFormat('en_US');

        $this->assertFormats($intlFormat);
    }

    public function testCreateIntlFormatWithAdditionalFormatter(): void
    {
        $formatter  = $this->createMock(FormatterInterface::class);
        $intlFormat = (new Factory())->createIntlFormat('en_US', [$formatter]);

        $this->assertFormats($intlFormat);

        $formatter->expects(self::once())->method('has')->willReturn(true);
        $formatter->expects(self::once())->method('formatValue')->willReturn('foo');

        self::assertSame('First there was foo', $intlFormat->format('First there was %foo', 'foo'));
    }

    public function testCreateIntlFormatWithAdditionalOverridingFormatter(): void
    {
        $formatter  = new class implements FormatterInterface {
            public function formatValue(string $typeSpecifier, mixed $value): string
            {
                return 'foo';
            }

            public function has(string $typeSpecifier): bool
            {
                return $typeSpecifier === 'date';
            }
        };
        $intlFormat = (new Factory())->createIntlFormat('en_US', [$formatter]);

        $this->assertFormats($intlFormat);

        self::assertSame('First there was foo', $intlFormat->format('First there was %date', 'foo'));
    }

    private function assertFormats(IntlFormatInterface $intlFormat): void
    {
        $date = new DateTime();
        $date->setDate(2016, 3, 1);
        $date->setTime(5, 30);
        $date->setTimezone(new DateTimeZone('US/Arizona'));

        self::assertSame('Today is 3/1/16', $intlFormat->format('Today is %date_short', $date));
        self::assertSame('I got 1,002.25 as average value', $intlFormat->format('I got %number as average value', 1002.25));
        self::assertSame('I got 1,002.2500 as average value', $intlFormat->format('I got %.4number as average value', 1002.25));
        self::assertSame('I got 01,002.2500 as average value', $intlFormat->format('I got %011.4number as average value', 1002.25));
        self::assertSame('I got 1,002.3 as average value', $intlFormat->format('I got %.1number_halfway_up as average value', 1002.25));
        self::assertSame('The timezone id is US/Arizona.', $intlFormat->format('The timezone id is %timezone_id.', $date));
        self::assertSame('I am from Italy.', $intlFormat->format('I am from %region.', 'it_IT'));
        self::assertSame('You have 10$.', $intlFormat->format('You have 10%currency_symbol.', ''));
        self::assertSame('Error: "test"', $intlFormat->format('Error: "%emessage"', new Exception('test')));
        self::assertSame('User Foo has 10 points', $intlFormat->format('User %s has %d points', 'Foo', 10));
    }

    public function testSprintF(): void
    {
        Locale::setDefault('en_US');
        $date = new DateTime();
        $date->setDate(2016, 3, 1);
        $date->setTime(5, 30);
        $date->setTimezone(new DateTimeZone('US/Arizona'));

        self::assertSame('Today is 3/1/16', sprintf('Today is %date_short', $date));
        self::assertSame('I got 1,002.25 as average value', sprintf('I got %number as average value', 1002.25));
        self::assertSame('I got 1,002.2500 as average value', sprintf('I got %.4number as average value', 1002.25));
        self::assertSame('I got 01,002.2500 as average value', sprintf('I got %011.4number as average value', 1002.25));
        self::assertSame('I got 1,002.3 as average value', sprintf('I got %.1number_halfway_up as average value', 1002.25));
        self::assertSame('The timezone id is US/Arizona.', sprintf('The timezone id is %timezone_id.', $date));
        self::assertSame('I am from Italy.', sprintf('I am from %region.', 'it_IT'));
        self::assertSame('You have 10$.', sprintf('You have 10%currency_symbol.', ''));
        self::assertSame('Error: "test"', sprintf('Error: "%emessage"', new Exception('test')));
        self::assertSame('User Foo has 10 points', sprintf('User %s has %d points', 'Foo', 10));
    }
}
