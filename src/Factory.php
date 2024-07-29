<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\CurrencySymbolFormatter;
use Budgegeria\IntlFormat\Formatter\ExceptionFormatter;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\Formatter\LocaleFormatter;
use Budgegeria\IntlFormat\Formatter\MessageFormatter;
use Budgegeria\IntlFormat\Formatter\PrecisionNumberFormatter;
use Budgegeria\IntlFormat\Formatter\SprintfFormatter;
use Budgegeria\IntlFormat\Formatter\TimeZoneFormatter;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;

use function array_merge;

class Factory
{
    /** @param list<FormatterInterface> $addFormatter */
    public function createIntlFormat(string $locale, array $addFormatter = []): IntlFormatInterface
    {
        $formatter = [
            new SprintfFormatter(),
            new ExceptionFormatter(),
            MessageFormatter::createDateValueFormatter($locale),
            MessageFormatter::createNumberValueFormatter($locale),
            new PrecisionNumberFormatter($locale),
            new TimeZoneFormatter($locale),
            new LocaleFormatter($locale),
            new CurrencySymbolFormatter($locale),
        ];
        $formatter = array_merge($formatter, $addFormatter);

        return new IntlFormat($formatter, new SprintfParser());
    }
}
