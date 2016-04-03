<?php

namespace Budgegeria\IntlFormat\Formatter;

use IntlTimeZone;
use DateTime;
use DateTimeZone;
use Budgegeria\IntlFormat\Exception\InvalidValueException;

class TimeZoneFormatter implements FormatterInterface
{
    const TYPE_SPECIFIER_ID = 'timeseries_id';
    const TYPE_SPECIFIER_LONG_NAME = 'timeseries_name';
    const TYPE_SPECIFIER_SHORT_NAME = 'timeseries_gmt_diff';

    /**
     * @var string
     */
    private $locale;

    /**
     * @param string $locale
     */
    public function __construct($locale)
    {
        $this->locale = (string) $locale;
    }

    /**
     * @inheritdoc
     */
    public function formatValue($typeSpecifier, $value)
    {
        if ($value instanceof DateTime) {
            $value = $value->getTimezone();
        }

        if ($value instanceof DateTimeZone) {
            $value = IntlTimeZone::fromDateTimeZone($value);
        }

        if (!($value instanceof IntlTimeZone)) {
            throw InvalidValueException::invalidValueType($value, [DateTime::class, DateTimeZone::class, IntlTimeZone::class]);
        }

        // todo Get daylight time if possible. Check IntlCalendar
        $timeZoneMetaData = [
            self::TYPE_SPECIFIER_ID => $value->getID(),
            self::TYPE_SPECIFIER_LONG_NAME => $value->getDisplayName(null, IntlTimeZone::DISPLAY_LONG, $this->locale),
            self::TYPE_SPECIFIER_SHORT_NAME => $value->getDisplayName(null, IntlTimeZone::DISPLAY_SHORT, $this->locale),
        ];

        return $timeZoneMetaData[$typeSpecifier];
    }

    /**
     * @inheritdoc
     */
    public function has($typeSpecifier)
    {
        $typeSpecifiers = [
            self::TYPE_SPECIFIER_ID,
            self::TYPE_SPECIFIER_LONG_NAME,
            self::TYPE_SPECIFIER_SHORT_NAME,
        ];

        return in_array($typeSpecifier, $typeSpecifiers, true);
    }
}