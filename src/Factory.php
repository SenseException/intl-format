<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\CurrencySymbolFormatter;
use Budgegeria\IntlFormat\Formatter\LocaleFormatter;
use Budgegeria\IntlFormat\Formatter\MessageFormatter;
use Budgegeria\IntlFormat\Formatter\PrecisionNumberFormatter;
use Budgegeria\IntlFormat\Formatter\TimeZoneFormatter;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;

class Factory
{
    public function createIntlFormat(string $locale): IntlFormatInterface
    {
        $formatter = [
            MessageFormatter::createDateValueFormatter($locale),
            MessageFormatter::createNumberValueFormatter($locale),
            new PrecisionNumberFormatter($locale),
            new TimeZoneFormatter($locale),
            new LocaleFormatter($locale),
            new CurrencySymbolFormatter($locale),
        ];

        return new IntlFormat($formatter, new SprintfParser());
    }
}
