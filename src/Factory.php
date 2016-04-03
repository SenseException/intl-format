<?php

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\MessageFormatter;

class Factory
{
    /**
     * @param string $locale
     * @return IntlFormat
     */
    public function createIntlFormat($locale)
    {
        $formatter = [
            MessageFormatter::createDateValueFormatter($locale),
            MessageFormatter::createNumberValueFormatter($locale),
        ];

        return new IntlFormat($formatter);
    }
}