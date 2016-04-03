<?php

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use MessageFormatter as Message;

class MessageFormatter implements FormatterInterface
{
    const TYPE_SPECIFIER_MESSAGE_NUMBER = [
        'number' => '{0,number}',
        'integer' => '{0,number,integer}',
        'currency' => '{0,number,currency}',
        'percent' => '{0,number,percent}',
        'spellout' => '{0,spellout}',
        'ordinal' => '{0,ordinal}',
        'duration' => '{0,duration}',
    ];

    const TYPE_SPECIFIER_MESSAGE_DATETIME = [
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
    ];

    /**
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $messageFormats = [];

    /**
     * @var Callable
     */
    private $valueTypeCheck;

    /**
     * @param string $locale
     * @param string[] $messageFormats
     * @param Callable $valueTypeCheck
     */
    public function __construct($locale, array $messageFormats, Callable $valueTypeCheck)
    {
        $this->locale = (string) $locale;
        $this->messageFormats = $messageFormats;
        $this->valueTypeCheck = $valueTypeCheck;
    }

    /**
     * @inheritDoc
     */
    public function formatValue($typeSpecifier, $value)
    {
        $valueTypeCheck = $this->valueTypeCheck;
        $valueTypeCheck($value);

        return Message::formatMessage($this->locale, $this->messageFormats[(string) $typeSpecifier], [$value]);
    }

    /**
     * @inheritDoc
     */
    public function has($typeSpecifier)
    {
        return array_key_exists((string) $typeSpecifier, $this->messageFormats);
    }

    /**
     * @param string $locale
     * @return MessageFormatter
     */
    public static function createNumberValueFormatter($locale)
    {
        $valueTypeCheck = function ($value) {
            if (!is_numeric($value)) {
                throw InvalidValueException::invalidValueType($value, ['integer', 'double']);
            }
        };

        return new self($locale, self::TYPE_SPECIFIER_MESSAGE_NUMBER, $valueTypeCheck);
    }

    /**
     * @param string $locale
     * @return MessageFormatter
     */
    public static function createDateValueFormatter($locale)
    {
        $valueTypeCheck = function ($value) {
            if (!is_int($value) && !($value instanceof \DateTime)) {
                throw InvalidValueException::invalidValueType($value, ['integer', 'DateTime']);
            }
        };

        return new self($locale, self::TYPE_SPECIFIER_MESSAGE_DATETIME, $valueTypeCheck);
    }
}