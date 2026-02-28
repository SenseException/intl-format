<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\TimeZoneFormatter;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use IntlTimeZone;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function sprintf;

class TimeZoneFormatterTest extends TestCase
{
    #[DataProvider('provideTypeSpecifier')]
    public function testHas(string $typeSpecifier): void
    {
        $formatter = new TimeZoneFormatter('en_US');

        self::assertTrue($formatter->has($typeSpecifier));
    }

    public function testHasIsFalse(): void
    {
        $messageFormatter = new TimeZoneFormatter('de_DE');

        self::assertFalse($messageFormatter->has('int'));
    }

    #[DataProvider('provideTimeZones')]
    public function testFormatValue(string $expected, string $typeSpecifier, mixed $value): void
    {
        $formatter = new TimeZoneFormatter('en_US');

        self::assertSame($expected, $formatter->formatValue($typeSpecifier, $value));
    }

    #[DataProvider('provideTypeSpecifier')]
    public function testFormatValueInvalidValue(string $typeSpecifier): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageMatches(sprintf(
            '/"%s, %s, %s"/',
            DateTimeInterface::class,
            DateTimeZone::class,
            IntlTimeZone::class,
        ));
        $this->expectExceptionCode(10);

        $formatter = new TimeZoneFormatter('en_US');

        $formatter->formatValue($typeSpecifier, 'UTC');
    }

    /** @return array<array<string>> */
    public static function provideTypeSpecifier(): array
    {
        return [
            'timezone_id' => ['timezone_id'],
            'timezone_name' => ['timezone_name'],
            'timezone_short' => ['timezone_short'],
        ];
    }

    /** @return array<string, array{string, string, mixed}> */
    public static function provideTimeZones(): array
    {
        $datetime          = new DateTime('2016-08-01', new DateTimeZone('US/Arizona'));
        $datetimeImmutable = new DateTimeImmutable('2016-05-01', new DateTimeZone('US/Arizona'));
        $timezone          = $datetime->getTimezone();
        $intlTimezone      = IntlTimeZone::fromDateTimeZone($timezone);

        return [
            'timezone_id_ts' => ['US/Arizona', 'timezone_id', $timezone],
            'timezone_id_dt' => ['US/Arizona', 'timezone_id', $datetime],
            'timezone_id_dti' => ['US/Arizona', 'timezone_id', $datetimeImmutable],
            'timezone_id_its' => ['US/Arizona', 'timezone_id', $intlTimezone],

            'timezone_name_ts' => ['Mountain Standard Time', 'timezone_name', $timezone],
            'timezone_name_dt' => ['Mountain Standard Time', 'timezone_name', $datetime],
            'timezone_name_dti' => ['Mountain Standard Time', 'timezone_name', $datetimeImmutable],
            'timezone_name_its' => ['Mountain Standard Time', 'timezone_name', $intlTimezone],

            'timezone_short_ts' => ['MST', 'timezone_short', $timezone],
            'timezone_short_dt' => ['MST', 'timezone_short', $datetime],
            'timezone_short_dti' => ['MST', 'timezone_short', $datetimeImmutable],
            'timezone_short_its' => ['MST', 'timezone_short', $intlTimezone],

            'timezone_name_dst_start' => ['Central European Summer Time', 'timezone_name', new DateTime('2016-03-27 02:00:00', new DateTimeZone('Europe/Berlin'))],
            'timezone_name_dst_end' => ['Central European Summer Time', 'timezone_name', new DateTime('2016-10-30 01:59:59', new DateTimeZone('Europe/Berlin'))],
            'timezone_name_after_dst' => ['Central European Standard Time', 'timezone_name', new DateTime('2016-10-30 02:59:59', new DateTimeZone('Europe/Berlin'))],
        ];
    }
}
