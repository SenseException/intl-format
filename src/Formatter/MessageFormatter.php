<?php

namespace Budgegeria\IntlFormat\Formatter;

use MessageFormatter as Message;

class MessageFormatter implements FormatterInterface
{
    /**
     * @var array
     */
    private $messageFormats = [
        'number' => '{0,number}',
        'integer' => '{0,number,integer}',
        'currency' => '{0,number,currency}',
        'percent' => '{0,number,percent}',
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
        'spellout' => '{0,spellout}',
        'ordinal' => '{0,ordinal}',
        'duration' => '{0,duration}',
    ];

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
     * @inheritDoc
     */
    public function formatValue($typeSpecifier, $value)
    {
        return Message::formatMessage($this->locale, $this->messageFormats[(string) $typeSpecifier], [$value]);
    }

    /**
     * @inheritDoc
     */
    public function has($typeSpecifier)
    {
        return array_key_exists((string) $typeSpecifier, $this->messageFormats);
    }
}