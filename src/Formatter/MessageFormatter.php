<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Closure;
use DateTimeInterface;
use IntlCalendar;
use MessageFormatter as Message;
use function is_numeric;
use function is_int;

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

        $formattedValue = Message::formatMessage($this->locale, $this->messageFormats[$typeSpecifier], [$value]);
        if (false === $formattedValue) {
            throw InvalidValueException::invalidReturnType($formattedValue);
        }

        return $formattedValue;
    }

    /**
     * @inheritDoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return isset($this->messageFormats[$typeSpecifier]);
    }

    /**
     * @param string $locale
     * @return MessageFormatter
     */
    public static function createNumberValueFormatter(string $locale) : MessageFormatter
    {
        $valueTypeCheck = static function($value) : void {
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
    public static function createDateValueFormatter(string $locale) : MessageFormatter
    {
        $valueTypeCheck = static function($value) : void {
            if (!is_int($value) && !($value instanceof DateTimeInterface) && !($value instanceof IntlCalendar)) {
                throw InvalidValueException::invalidValueType($value, ['integer', DateTimeInterface::class, IntlCalendar::class]);
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
            'date_weekday_abbr' => '{0,date,EEEEEE}',
            'quarter' => '{0,date,Q}',
            'quarter_abbr' => '{0,date,QQQ}',
            'quarter_name' => '{0,date,QQQQ}',
        ];

        return new self($locale, $dateTypeSpecifier, $valueTypeCheck);
    }
}