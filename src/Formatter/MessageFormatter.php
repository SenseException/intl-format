<?php
declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Closure;
use DateTime;
use IntlCalendar;
use MessageFormatter as Message;

class MessageFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $messageFormats = [];

    /**
     * @var Closure
     */
    private $valueTypeCheck;

    /**
     * @param string $locale
     * @param string[] $messageFormats
     * @param Closure $valueTypeCheck
     */
    public function __construct(string $locale, array $messageFormats, Closure $valueTypeCheck)
    {
        $this->locale = $locale;
        $this->messageFormats = $messageFormats;
        $this->valueTypeCheck = $valueTypeCheck;
    }

    /**
     * @inheritDoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        $valueTypeCheck = $this->valueTypeCheck;
        $valueTypeCheck($value);

        // MessageFormatter supports IntlCalendar, but HHVM does a strange date format.
        if ($value instanceof IntlCalendar) {
            $value = $value->toDateTime();
        }

        return Message::formatMessage($this->locale, $this->messageFormats[$typeSpecifier], [$value]);
    }

    /**
     * @inheritDoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return array_key_exists($typeSpecifier, $this->messageFormats);
    }

    /**
     * @param string $locale
     * @return MessageFormatter
     */
    public static function createNumberValueFormatter(string $locale)
    {
        $valueTypeCheck = function($value) {
            if (!is_numeric($value)) {
                throw InvalidValueException::invalidValueType($value, ['integer', 'double']);
            }
        };

        $numberTypeSpecifier = [
            'number' => '{0,number}',
            'integer' => '{0,number,integer}',
            'currency' => '{0,number,currency}',
            'percent' => '{0,number,percent}',
            'spellout' => '{0,spellout}',
            'ordinal' => '{0,ordinal}',
            'duration' => '{0,duration}',
        ];

        return new self($locale, $numberTypeSpecifier, $valueTypeCheck);
    }

    /**
     * @param string $locale
     * @return MessageFormatter
     */
    public static function createDateValueFormatter(string $locale)
    {
        $valueTypeCheck = function($value) {
            if (!is_int($value) && !($value instanceof DateTime) && !($value instanceof IntlCalendar)) {
                throw InvalidValueException::invalidValueType($value, ['integer', DateTime::class, IntlCalendar::class]);
            }
        };

        $dateTypeSpecifier = [
            'date' => '{0,date}',
            'date_short' => '{0,date,short}',
            'date_medium' => '{0,date,medium}',
            'date_long' => '{0,date,long}',
            'date_full' => '{0,date,full}',
            'time' => '{0,time}',
            'time_short' => '{0,time,short}',
            'time_medium' => '{0,time,medium}',
            'time_long' => '{0,time,long}',
            'time_full' => '{0,time,full}',
            'date_year' => '{0,date,y}',
            'date_month' => '{0,date,M}',
            'date_month_name' => '{0,date,MMMM}',
            'date_day' => '{0,date,d}',
            'date_weekday' => '{0,date,EEEE}',
        ];

        return new self($locale, $dateTypeSpecifier, $valueTypeCheck);
    }
}