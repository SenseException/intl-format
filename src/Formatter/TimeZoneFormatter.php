<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use DateTimeInterface;
use DateTimeZone;
use IntlCalendar;
use IntlTimeZone;
use Override;

use function assert;

class TimeZoneFormatter implements FormatterInterface
{
    private const TYPE_SPECIFIER_TZ_ID         = 'timezone_id';
    private const TYPE_SPECIFIER_TZ_LONG_NAME  = 'timezone_name';
    private const TYPE_SPECIFIER_TZ_SHORT_NAME = 'timezone_short';

    public function __construct(private string $locale)
    {
    }

    #[Override]
    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        $intlCalendar = IntlCalendar::createInstance(null, $this->locale);

        assert($intlCalendar !== null);

        if ($value instanceof DateTimeInterface) {
            $intlCalendar->setTime($value->getTimestamp() * 1000);
            $value = $value->getTimezone();
        }

        if (! ($value instanceof IntlTimeZone) && ! ($value instanceof DateTimeZone)) {
            throw InvalidValueException::invalidValueType($value, [DateTimeInterface::class, DateTimeZone::class, IntlTimeZone::class]);
        }

        $intlCalendar->setTimeZone($value);
        $inDaylight = $intlCalendar->inDaylightTime();
        $value      = $intlCalendar->getTimeZone();

        $timeZoneMetaData = [
            self::TYPE_SPECIFIER_TZ_ID => $value->getID(),
            self::TYPE_SPECIFIER_TZ_LONG_NAME => $value->getDisplayName($inDaylight, IntlTimeZone::DISPLAY_LONG, $this->locale),
            self::TYPE_SPECIFIER_TZ_SHORT_NAME => $value->getDisplayName($inDaylight, IntlTimeZone::DISPLAY_SHORT, $this->locale),
        ];

        return $timeZoneMetaData[$typeSpecifier];
    }

    #[Override]
    public function has(string $typeSpecifier): bool
    {
        return $typeSpecifier === self::TYPE_SPECIFIER_TZ_ID ||
            $typeSpecifier === self::TYPE_SPECIFIER_TZ_LONG_NAME ||
            $typeSpecifier === self::TYPE_SPECIFIER_TZ_SHORT_NAME;
    }
}
