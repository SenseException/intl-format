<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Locale;

function sprintf(string $message, mixed ...$values): string
{
    return (new Factory())->createIntlFormat(Locale::getDefault())->format($message, ...$values);
}
